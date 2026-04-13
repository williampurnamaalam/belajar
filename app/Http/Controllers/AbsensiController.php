<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\Areakerja;
use App\Models\Cuti;
use App\Models\Lembur;
use Illuminate\Http\Request;
use Carbon\Carbon;

class AbsensiController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        $hariIni = now()->toDateString();
        $bulanIni = now()->month;
        $tahunIni = now()->year;

        // 1. CEK CUTI HARI INI
        $cutiAktif = Cuti::where('karyawan_id', $user->id)
            ->where('status', 'disetujui')
            ->where('tanggal_mulai', '<=', $hariIni)
            ->where('tanggal_selesai', '>=', $hariIni)
            ->first();

        // 2. CEK ABSEN HARI INI
        $absenHariIni = Absensi::where('karyawan_id', $user->id)
            ->where('tanggal', $hariIni)
            ->first();

        $area = $user->areakerja()->first();

        // 3. AMBIL RIWAYAT ABSEN BULAN INI
        $riwayat = Absensi::where('karyawan_id', $user->id)
            ->whereMonth('tanggal', $bulanIni)
            ->whereYear('tanggal', $tahunIni)
            ->orderBy('tanggal', 'desc')
            ->get();

        // 4. HITUNG TOTAL HARI CUTI/IZIN BULAN INI
        $dataCutiBulanIni = Cuti::where('karyawan_id', $user->id)
            ->where('status', 'disetujui')
            ->whereMonth('tanggal_mulai', $bulanIni)
            ->whereYear('tanggal_mulai', $tahunIni)
            ->get();

        $totalHariCuti = 0;
        foreach ($dataCutiBulanIni as $cuti) {
            $start = Carbon::parse($cuti->tanggal_mulai);
            $end = Carbon::parse($cuti->tanggal_selesai);
            $totalHariCuti += $start->diffInDays($end) + 1;
        }

        // 5. HITUNG TOTAL JAM LEMBUR DARI TABEL LEMBURS
        $totalMenitLembur = Lembur::where('karyawan_id', $user->id)
            ->where('status', 'disetujui')
            ->whereMonth('tanggal', $bulanIni)
            ->whereYear('tanggal', $tahunIni)
            ->sum('durasi_aktual_menit');

        $totalJamLembur = floor($totalMenitLembur / 60); 

        // 6. UPDATE STATS
        $stats = [
            'hadir'  => $riwayat->where('status', 'hadir')->count(),
            'cuti'   => $totalHariCuti, 
            'alpa'   => $riwayat->where('status', 'alpa')->count(),
            'lembur' => $totalJamLembur,
        ];

        return view('absensi.index', compact('area', 'riwayat', 'stats', 'absenHariIni', 'cutiAktif'));
    }

    public function store(Request $request)
    {
        $hariIni = now()->toDateString();
        $jamSekarang = now();
        
        // --- KONFIGURASI JAM MASUK RESMI ---
        $jamMasukResmi = Carbon::createFromTimeString('08:00:00');

        // 1. CEK STATUS CUTI (Lapis Keamanan)
        $sedangCuti = Cuti::where('karyawan_id', auth()->id())
            ->where('status', 'disetujui')
            ->where('tanggal_mulai', '<=', $hariIni)
            ->where('tanggal_selesai', '>=', $hariIni)
            ->exists();

        if ($sedangCuti) {
            return redirect()->back()->with('error', 'Gagal! Anda sedang dalam masa cuti/izin resmi.');
        } 

        // 2. VALIDASI REQUEST
        $request->validate([
            'area_id' => 'required',
            'lat' => 'required|numeric',
            'lon' => 'required|numeric',
        ]);

        $userIp = $request->ip();
        $area = Areakerja::findOrFail($request->area_id);
        $rawIps = $area->ip_address;
        
        // 3. CEK FORMAT DATA IP
        if (is_array($rawIps)) {
            $allowedIps = $rawIps;
        } else {
            $allowedIps = !empty($rawIps) ? array_map('trim', explode(',', $rawIps)) : [];
        }

        // 4. VALIDASI IP KANTOR
        if (!empty($allowedIps) && !in_array($userIp, $allowedIps)) {
            return redirect()->back()->with('error', 'Gagal! Gunakan Wi-Fi kantor untuk absen.');
        }

        // 5. VALIDASI GPS (GEOFENCING)
        $jarak = $this->hitungJarak($request->lat, $request->lon, $area->latitude, $area->longitude);   
        if ($jarak > $area->radius) {
            return redirect()->back()->with('error', 'Gagal! Anda berada di luar radius.');
        }

        // 6. CEK DOUBLE ABSEN
        $exists = Absensi::where('karyawan_id', auth()->id())
            ->where('tanggal', $hariIni)
            ->exists();
            
        if($exists) {
            return redirect()->back()->with('error', 'Anda sudah absen hari ini.');
        }

        // 7. LOGIKA KETERLAMBATAN
        $keterangan = 'Hadir Tepat Waktu';
        if ($jamSekarang->gt($jamMasukResmi)) {
            $menitTelat = $jamSekarang->diffInMinutes($jamMasukResmi);
            $keterangan = "Terlambat $menitTelat menit";
        }

        // 8. SIMPAN DATA
        Absensi::create([
            'karyawan_id' => auth()->id(),
            'tanggal'     => $hariIni,
            'jam_masuk'   => $jamSekarang->toTimeString(),
            'status'      => 'hadir',
            'keterangan'  => $keterangan, // Menyimpan info telat
            'area_id'     => $request->area_id,
            'latitude'    => $request->lat,
            'longitude'   => $request->lon,
            'jam_lembur'  => 0,
        ]);

        return redirect()->back()->with('success', 'Berhasil Absen Masuk. ' . $keterangan);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'lat' => 'required',
            'lon' => 'required',
        ]);

        $absensi = Absensi::findOrFail($id);
        $hariIni = $absensi->tanggal;
        $waktuSekarang = now();

        // 1. Logika Jeda Waktu (Cooldown)
        $jedaMenit = 10; 
        $waktuMasuk = Carbon::parse($absensi->jam_masuk);

        if ($waktuMasuk->diffInMinutes($waktuSekarang) < $jedaMenit) {
            $sisaMenit = $jedaMenit - $waktuMasuk->diffInMinutes($waktuSekarang);
            return redirect()->back()->with('error', "Harap tunggu $sisaMenit menit lagi.");
        }

        // 2. Validasi IP & GPS
        $userIp = $request->ip();
        $area = Areakerja::findOrFail($absensi->area_id);
        
        $rawIps = $area->ip_address;
        $allowedIps = is_array($rawIps) ? $rawIps : (!empty($rawIps) ? array_map('trim', explode(',', $rawIps)) : []);

        if (!empty($allowedIps) && !in_array($userIp, $allowedIps)) {
            return redirect()->back()->with('error', 'Gunakan Wi-Fi kantor.');
        }

        $jarak = $this->hitungJarak($request->lat, $request->lon, $area->latitude, $area->longitude);
        if ($jarak > $area->radius) {
            return redirect()->back()->with('error', 'Anda di luar radius.');
        }

        // 3. Update Jam Keluar
        $absensi->update([
            'jam_keluar' => $waktuSekarang->toTimeString(),
        ]);

        // 4. Logika Hitung Lembur
        $lembur = Lembur::where('karyawan_id', $absensi->karyawan_id)
                    ->where('tanggal', $hariIni)
                    ->where('status', 'disetujui')
                    ->first();

        if ($lembur) {
            $rencanaMulaiLembur = Carbon::parse($hariIni . ' ' . $lembur->jam_mulai);
            if ($waktuSekarang->gt($rencanaMulaiLembur)) {
                $durasiMenit = $rencanaMulaiLembur->diffInMinutes($waktuSekarang);
                $lembur->update(['durasi_aktual_menit' => $durasiMenit]);
            }
        }

        return redirect()->back()->with('success', 'Berhasil Absen Pulang.');
    }

    private function hitungJarak($lat1, $lon1, $lat2, $lon2)
    {
        $earth_radius = 6371000; 
        $dLat = deg2rad((float)$lat2 - (float)$lat1);
        $dLon = deg2rad((float)$lon2 - (float)$lon1);
        $a = sin($dLat/2) * sin($dLat/2) + 
            cos(deg2rad((float)$lat1)) * cos(deg2rad((float)$lat2)) * sin($dLon/2) * sin($dLon/2);
        $c = 2 * atan2(sqrt($a), sqrt(1-$a));
        return $earth_radius * $c;
    }
}
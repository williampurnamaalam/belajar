<?php

namespace App\Http\Controllers;

use App\Models\Absensi;
use App\Models\AreaKerja;
use App\Models\User;
use App\Models\Cuti; // Wajib ditambahkan agar model Cuti bisa terbaca
use Illuminate\Http\Request;
use Carbon\Carbon;

class PresensiController extends Controller
{
    public function index()
    {
        $areakerjas = AreaKerja::withCount('karyawans')->get();
        return view('presensi.index', compact('areakerjas'));
    }

    public function daftarKaryawan($area_id)
    {
        $area = AreaKerja::findOrFail($area_id);
        $karyawans = User::whereIn('id', function($query) use ($area_id) {
            $query->select('karyawan_id')
                ->from('team')
                ->where('area_id', $area_id);
        })->with('jabatan')->get(); 

        return view('presensi.karyawan', compact('area', 'karyawans'));
    }
    
public function detailKaryawan($user_id)
    {
        $karyawan = User::with(['jabatan', 'areaKerja'])->findOrFail($user_id);
        
        // TANGKAP INPUT FILTER (Jika kosong, otomatis default ke bulan & tahun berjalan)
        $bulan = request('bulan', Carbon::now()->month);
        $tahun = request('tahun', Carbon::now()->year);

        // SET TANGGAL AWAL & AKHIR BERDASARKAN FILTER
        $start_date = Carbon::createFromDate($tahun, $bulan, 1)->startOfMonth(); 
        $end_date = Carbon::createFromDate($tahun, $bulan, 1)->endOfMonth();

        // Ambil data absensi sesuai range tanggal filter
        $dataAbsensi = Absensi::where('karyawan_id', $user_id)
                    ->whereBetween('tanggal', [$start_date, $end_date])
                    ->get()
                    ->map(function ($item) {
                        return (object) [
                            'tanggal'    => $item->tanggal,
                            'jam_masuk'  => $item->jam_masuk,
                            'jam_pulang' => $item->jam_keluar, 
                            'status'     => $item->status,
                            'keterangan' => '-'
                        ];
                    });

        // Ambil data cuti sesuai range tanggal filter
        $dataCuti = Cuti::where('karyawan_id', $user_id)
                    ->where('status', 'disetujui')
                    ->where(function ($query) use ($start_date, $end_date) {
                        $query->whereBetween('tanggal_mulai', [$start_date, $end_date])
                              ->orWhereBetween('tanggal_selesai', [$start_date, $end_date])
                              ->orWhere(function ($q) use ($start_date, $end_date) {
                                  $q->where('tanggal_mulai', '<=', $start_date)
                                    ->where('tanggal_selesai', '>=', $end_date);
                              });
                    })->get();

        $riwayatCuti = collect(); 

        // Pecah rentang cuti menjadi data per-hari
        foreach ($dataCuti as $cuti) {
            $start = Carbon::parse($cuti->tanggal_mulai);
            $end = Carbon::parse($cuti->tanggal_selesai);

            for ($date = $start; $date->lte($end); $date->addDay()) {
                if ($date->betweenIncluded($start_date, $end_date)) {
                    $riwayatCuti->push((object)[
                        'tanggal'    => $date->toDateString(),
                        'jam_masuk'  => null,
                        'jam_pulang' => null,
                        'status'     => $cuti->jenis_cuti, 
                        'keterangan' => $cuti->alasan
                    ]);
                }
            }
        }

        // 3. GABUNGKAN ABSENSI & CUTI, LALU URUTKAN DARI TANGGAL TERBARU
        $riwayat = collect($dataAbsensi)->concat($riwayatCuti)->sortByDesc('tanggal')->values();

        // 4. OPER DATA FILTER (bulan & tahun) KE VIEW AGAR PILIHAN SELECT TIDAK BERUBAH
        return view('presensi.detail', compact('karyawan', 'riwayat', 'bulan', 'tahun'));
    }
}
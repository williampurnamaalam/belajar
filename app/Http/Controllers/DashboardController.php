<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Kriteria;
use App\Models\Penilaian;
use Carbon\Carbon;
use App\Models\Absensi;
use App\Models\Cuti;
use App\Models\Dana;
use App\Models\Presensi;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();
        
        // Memastikan nama role diubah menjadi huruf kecil semua agar mudah dicek (admin, hrd, manager, karyawan)
        $userRole = strtolower($user->role->role);

        // =================================================================
        // 1. DASHBOARD ADMIN / HRD / KEPALA CABANG (MANAGER)
        // =================================================================
        if (in_array($userRole, ['admin', 'hrd', 'manager'])) {
            
            // --- STATISTIK ATAS ---
            $totalKaryawan = User::whereHas('role', function($q) {
                $q->where('role', 'karyawan');
            })->count();

            // Gunakan class_exists untuk mengecek apakah modelnya sudah Anda buat atau belum.
            // Jika sudah ada, sistem akan menghitung aslinya. Jika belum, akan ditampilkan angka 0.
            $cutiPending = class_exists(Cuti::class) ? Cuti::where('status', 'pending')->count() : 0;
            
            // Asumsi tabel presensi mencatat 'tanggal' dan 'status' (Hadir/Terlambat)
            $hadirHariIni = class_exists(Absensi::class) ? Absensi::whereDate('tanggal', date('Y-m-d'))->where('status', 'Hadir')->count() : 0;
            $totalLaporan = class_exists(Laporan::class) ? Laporan::whereMonth('created_at', date('m'))->whereYear('created_at', date('Y'))->count() : 0;
            $bulan = date('m');
            $tahun = date('Y');
            
            $kriterias = Kriteria::orderBy('kode_kriteria', 'asc')->get();
            $penilaians = Penilaian::with('karyawan')
                ->where('bulan', $bulan)
                ->where('tahun', $tahun)
                ->get();

            $hasil_akhir = [];
            $chart_labels = [];
            $chart_data = [];

            if ($penilaians->isNotEmpty()) {
                $nilai_max_min = [];
                // Cari Nilai Max & Min
                foreach ($kriterias as $k) {
                    $skor_kriteria = $penilaians->where('kriteria_id', $k->id)->pluck('nilai')->toArray();
                    if (!empty($skor_kriteria)) {
                        $nilai_max_min[$k->id]['max'] = max($skor_kriteria);
                        $nilai_max_min[$k->id]['min'] = min($skor_kriteria);
                    }
                }

                // Normalisasi dan Perangkingan
                $data_karyawan = $penilaians->groupBy('karyawan_id');
                foreach ($data_karyawan as $karyawan_id => $nilai_list) {
                    $karyawan_obj = $nilai_list->first()->karyawan;
                    $total_skor = 0;
                    
                    foreach ($kriterias as $k) {
                        $skor_obj = $nilai_list->where('kriteria_id', $k->id)->first();
                        $skor_asli = $skor_obj ? $skor_obj->nilai : 0;
                        $normalisasi = 0;
                        
                        if (isset($nilai_max_min[$k->id])) {
                            if ($k->jenis == 'Benefit') {
                                $normalisasi = $nilai_max_min[$k->id]['max'] != 0 ? $skor_asli / $nilai_max_min[$k->id]['max'] : 0;
                            } else {
                                $normalisasi = $skor_asli != 0 ? $nilai_max_min[$k->id]['min'] / $skor_asli : 0;
                            }
                        }
                        $total_skor += ($normalisasi * ($k->bobot / 100));
                    }
                    $hasil_akhir[] = [
                        'nama' => $karyawan_obj->nama, 
                        'skor' => round($total_skor, 3)
                    ];
                }

                // Urutkan ranking dari yang tertinggi
                usort($hasil_akhir, function($a, $b) { 
                    return $b['skor'] <=> $a['skor']; 
                });
                
                // Ambil Top 10 Karyawan untuk Grafik
                $top_karyawan = array_slice($hasil_akhir, 0, 10); 
                foreach($top_karyawan as $k) {
                    $chart_labels[] = $k['nama'];
                    $chart_data[] = $k['skor'];
                }
            }

            // --- AKTIVITAS PENGAMBILAN CUTI & DANA ---
            $latestCuti = collect([]);
            $latestDana = collect([]);

            // Cek jika model Cuti sudah ada
            if(class_exists(Cuti::class)) {
                $latestCuti = Cuti::with('user')->latest()->take(5)->get()->map(function($item) {
                    return [
                        'tipe' => 'Cuti',
                        'nama' => $item->user->nama ?? 'Karyawan',
                        'waktu' => $item->created_at,
                        'keterangan' => 'mengajukan cuti',
                        'icon' => 'fas fa-calendar-alt',
                        'color' => 'bg-warning'
                    ];
                });
            }

            // Cek jika model Dana sudah ada
            if(class_exists(Dana::class)) {
                $latestDana = Dana::with('user')->latest()->take(5)->get()->map(function($item) {
                    return [
                        'tipe' => 'Dana',
                        'nama' => $item->user->nama ?? 'Karyawan',
                        'waktu' => $item->created_at,
                        'keterangan' => 'mengajukan pencairan dana',
                        'icon' => 'fas fa-money-bill-wave',
                        'color' => 'bg-success'
                    ];
                });
            }

            // Gabungkan kedua aktivitas, urutkan dari yang terbaru, dan ambil 5 data teratas
            $aktivitasTerbaru = $latestCuti->concat($latestDana)
                ->sortByDesc('waktu')
                ->take(5);

            return view('dashboard', compact(
                'userRole', 'totalKaryawan', 'cutiPending', 'hadirHariIni', 'totalLaporan', 
                'chart_labels', 'chart_data', 'aktivitasTerbaru'
            ));

        } 
        
        // =================================================================
        // 2. DASHBOARD KHUSUS KARYAWAN
        // =================================================================
        else {
            
            // Hitung data absensi bulan ini khusus untuk karyawan yang sedang login
            $hadirBulanIni = class_exists(Absensi::class) 
                ? Absensi::where('karyawan_id', $user->id)
                    ->whereMonth('tanggal', date('m'))
                    ->where('status', 'Hadir')
                    ->count() 
                : 0;

            // Sisa Cuti (Bisa diubah dinamis jika ada kolom sisa_cuti di tabel users)
            $sisaCuti = 12; 
            
            // Pengajuan yang sedang diproses
            $pengajuanDiproses = class_exists(Cuti::class)
                ? Cuti::where('karyawan_id', $user->id)->where('status', 'pending')->count()
                : 0;

            // Tarik rata-rata nilai SAW bulan lalu (agar karyawan bisa melihat performanya)
            $bulanLalu = date('m', strtotime('-1 month'));
            $tahunLalu = date('Y', strtotime('-1 month'));
            $nilaiKinerjaBulanLalu = Penilaian::where('karyawan_id', $user->id)
                ->where('bulan', $bulanLalu)
                ->where('tahun', $tahunLalu)
                ->avg('nilai');

            return view('dashboard', compact(
                'userRole', 'hadirBulanIni', 'sisaCuti', 'pengajuanDiproses', 'nilaiKinerjaBulanLalu'
            ));
        }
    }
}
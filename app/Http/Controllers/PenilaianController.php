<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Kriteria;
use App\Models\Penilaian;
use Carbon\Carbon;

class PenilaianController extends Controller
{
    public function inputNilai(Request $request)
    {
        $bulan = $request->bulan ?? Carbon::now()->format('m');
        $tahun = $request->tahun ?? Carbon::now()->format('Y');

        $kriterias =Kriteria::orderBy('kode_kriteria', 'asc')->get();

        // 1. CARI AREA ID PENILAI DARI TABEL TEAM
        $userLoginId = auth()->id();
        $timPenilai = Team::where('karyawan_id', $userLoginId)->first();
        
        $area_id_penilai = $timPenilai ? $timPenilai->area_id : null;

        // 2. KUNCI QUERY KARYAWAN BERDASARKAN AREA DI TABEL TEAM
        $karyawans = User::whereHas('role', function($q) {
                $q->where('role', 'karyawan');
            })
            // Mencari karyawan yang ada di tabel team DENGAN area_id yang sama dengan penilai
            ->whereHas('team', function($query) use ($area_id_penilai) {
                $query->where('area_id', $area_id_penilai);
            })
            ->get();

        $penilaian_existing = Penilaian::where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->get()
            ->groupBy('karyawan_id');

        // Tambahkan variabel $area_id_penilai ke compact
        return view('penilaian.index', compact('kriterias', 'karyawans', 'bulan', 'tahun', 'penilaian_existing', 'area_id_penilai'));
    }

    public function storeNilai(Request $request)
    {
        $bulan = $request->bulan;
        $tahun = $request->tahun;
        $data_nilai = $request->nilai; // Format Array dari view

        // Validasi jika belum ada yang diinput
        if(!$data_nilai) {
            return redirect()->back()->with('error', 'Tidak ada data nilai yang diinput.');
        }

        // Looping data Array Matrix: nilai[karyawan_id][kriteria_id] = skor
        foreach ($data_nilai as $karyawan_id => $kriteria_array) {
            foreach ($kriteria_array as $kriteria_id => $skor) {
                // Jika input tidak kosong, simpan atau update ke database
                if ($skor !== null) {
                    Penilaian::updateOrCreate(
                        [
                            'karyawan_id' => $karyawan_id,
                            'kriteria_id' => $kriteria_id,
                            'bulan'       => $bulan,
                            'tahun'       => $tahun,
                        ],
                        [
                            'nilai'       => $skor
                        ]
                    );
                }
            }
        }

        return redirect()->back()->with('success', 'Data Penilaian Karyawan Bulan ' . $bulan . ' Tahun ' . $tahun . ' Berhasil Disimpan!');
    }


    public function hasilRanking(Request $request)
    {
        // 1. Ambil filter bulan & tahun (Default: bulan ini)
        $bulan = $request->bulan ?? date('m');
        $tahun = $request->tahun ?? date('Y');

        // 2. Ambil data Kriteria beserta bobotnya
        $kriterias = Kriteria::orderBy('kode_kriteria', 'asc')->get();

        // 3. Ambil semua data nilai yang sudah diinput Kepala Cabang pada bulan tersebut
        $penilaians = Penilaian::with(['karyawan', 'kriteria'])
            ->where('bulan', $bulan)
            ->where('tahun', $tahun)
            ->get();

        $hasil_akhir = [];
        $nilai_max_min = [];

        if ($penilaians->isNotEmpty()) {
            // 4. Cari nilai Max (Untuk Benefit) dan Min (Untuk Cost) dari setiap kriteria
            foreach ($kriterias as $k) {
                $skor_kriteria = $penilaians->where('kriteria_id', $k->id)->pluck('nilai')->toArray();
                if (!empty($skor_kriteria)) {
                    $nilai_max_min[$k->id]['max'] = max($skor_kriteria);
                    $nilai_max_min[$k->id]['min'] = min($skor_kriteria);
                }
            }

            // Kelompokkan data nilai berdasarkan Karyawan
            $data_karyawan = $penilaians->groupBy('karyawan_id');

            // 5. PROSES RUMUS SAW (Normalisasi R & Preferensi V)
            foreach ($data_karyawan as $karyawan_id => $nilai_list) {
                $user = $nilai_list->first()->karyawan;
                $total_skor = 0;
                $detail_normalisasi = [];
                $detail_asli = [];

                foreach ($kriterias as $k) {
                    // Ambil nilai asli (X)
                    $skor_obj = $nilai_list->where('kriteria_id', $k->id)->first();
                    $skor_asli = $skor_obj ? $skor_obj->nilai : 0;
                    $detail_asli[$k->kode_kriteria] = $skor_asli;

                    // Hitung Normalisasi (R)
                    $normalisasi = 0;
                    if (isset($nilai_max_min[$k->id])) {
                        if ($k->jenis == 'Benefit') {
                            // Rumus Benefit: Nilai Asli / Nilai Max
                            $normalisasi = $nilai_max_min[$k->id]['max'] != 0 ? $skor_asli / $nilai_max_min[$k->id]['max'] : 0;
                        } else {
                            // Rumus Cost: Nilai Min / Nilai Asli
                            $normalisasi = $skor_asli != 0 ? $nilai_max_min[$k->id]['min'] / $skor_asli : 0;
                        }
                    }
                    $detail_normalisasi[$k->kode_kriteria] = round($normalisasi, 3);

                    // Hitung Nilai Akhir (V) = R * W (Bobot dalam persen)
                    $bobot_persen = $k->bobot / 100;
                    $total_skor += ($normalisasi * $bobot_persen);
                }

                // Simpan hasil per karyawan
                $hasil_akhir[] = [
                    'karyawan'    => $user,
                    'skor_asli'   => $detail_asli,        // Matriks Keputusan (X)
                    'normalisasi' => $detail_normalisasi, // Matriks Ternormalisasi (R)
                    'nilai_akhir' => round($total_skor, 3) // Nilai Akhir (V)
                ];
            }

            // 6. Urutkan Ranking (Dari nilai akhir terbesar ke terkecil)
            usort($hasil_akhir, function($a, $b) {
                return $b['nilai_akhir'] <=> $a['nilai_akhir'];
            });
        }

        return view('penilaian.ranking', compact('kriterias', 'bulan', 'tahun', 'hasil_akhir'));
    }
}
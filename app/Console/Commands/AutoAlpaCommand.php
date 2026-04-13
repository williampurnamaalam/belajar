<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class AutoAlpaCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:auto-alpa-command';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $hariIni = now()->toDateString();
        
        // 1. Ambil semua Karyawan aktif
        $karyawans = \App\Models\User::where('role_id', 'karyawan')->get();

        foreach ($karyawans as $user) {
            // 2. Cek apakah sudah ada absen hari ini
            $sudahAbsen = \App\Models\Absensi::where('karyawan_id', $user->id)
                            ->where('tanggal', $hariIni)
                            ->exists();

            // 3. Cek apakah sedang Cuti/Izin
            $sedangCuti = \App\Models\Cuti::where('karyawan_id', $user->id)
                            ->where('status', 'disetujui')
                            ->where('mulai_tanggal', '<=', $hariIni)
                            ->where('sampai_tanggal', '>=', $hariIni)
                            ->exists();

            // 4. Jika TIDAK absen DAN TIDAK cuti, maka masukkan sebagai ALPA
            if (!$sudahAbsen && !$sedangCuti) {
                \App\Models\Absensi::create([
                    'karyawan_id' => $user->id,
                    'tanggal'     => $hariIni,
                    'status'      => 'alpa',
                    'jam_masuk'   => null,
                    'jam_keluar'  => null,
                    'area_id'     => $user->area_id // Pastikan user punya default area
                ]);
            }
        }
        $this->info('Data Alpa berhasil diperbarui.');
    }
}

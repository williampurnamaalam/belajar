<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Presensi extends Model
{
    use HasFactory;

    // Nama tabel di database (sesuaikan dengan yang Anda buat di Navicat)
    protected $table = 'presensi';

    // Kolom yang boleh diisi secara massal
    protected $fillable = [
        'user_id',
        'tanggal',
        'jam_masuk',
        'jam_pulang',
        'status',
        'keterangan',

    ];

    /**
     * Relasi ke Model User (Karyawan)
     * Ini yang mencegah munculnya data JSON di Blade
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    /**
     * Scope untuk memfilter data dari bulan lalu sampai sekarang
     * Agar query di Controller lebih bersih
     */
    public function scopeRiwayatTerbaru($query, $userId)
    {
        return $query->where('user_id', $userId)
                     ->whereBetween('tanggal', [now()->subMonth()->startOfMonth(), now()])
                     ->orderBy('tanggal', 'desc');
    }
}
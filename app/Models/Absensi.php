<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Absensi extends Model
{
    use HasFactory;

    protected $table = 'absensi';

    protected $fillable = [
        'karyawan_id',
        'tanggal',
        'jam_masuk',
        'jam_keluar',
        'status',
        'area_id',
        'jam_lembur',
        'keterangan',
    ];
 
    protected $casts = [
        'tanggal' => 'date',
    ];

    public function karyawan()
    {
        return $this->belongsTo(User::class, 'karyawan_id');
    }

    public function areakerja()
    {
        return $this->belongsTo(Areakerja::class, 'area_id');
    }
    public function scopeRiwayatTerbaru($query, $userId)
    {
        return $query->where('user_id', $userId)
                     ->whereBetween('tanggal', [now()->subMonth()->startOfMonth(), now()])
                     ->orderBy('tanggal', 'desc');
    }

}
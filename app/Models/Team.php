<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Team extends Model
{
    use HasFactory;

    // 1. Definisikan nama tabel yang tepat sesuai di database Anda
    protected $table = 'team';

    // 2. Definisikan kolom apa saja yang boleh diisi (mass assignable)
    protected $fillable = [
        'area_id', 
        'karyawan_id'
    ];

    /**
     * Relasi ke tabel User (Karyawan)
     * Sebuah data tim/area dimiliki oleh satu karyawan
     */
    public function karyawan()
    {
        return $this->belongsTo(User::class, 'karyawan_id', 'id');
    }

    /**
     * Relasi ke tabel Area
     * (Asumsi Anda memiliki Model Area, jika tidak ada, baris ini boleh dibiarkan/dihapus)
     */
    public function area()
    {
        return $this->belongsTo(Areakerja::class, 'area_id', 'id');
    }
}
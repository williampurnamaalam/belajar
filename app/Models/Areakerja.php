<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Areakerja extends Model // Gunakan PascalCase (Areakerja) agar standar Laravel
{
    use HasFactory;

    // Pastikan nama tabel di Navicat memang 'area'
    protected $table = 'area'; 

    protected $fillable = [
        'lokasi', 
        'detail',
        'radius',
        'latitude',
        'longitude',
        'ip_address' ,  
    ];

    /**
     * Casting ip_address menjadi array agar Laravel otomatis mengubah 
     * format JSON di database menjadi array PHP.
     */
    protected $casts = [
        'ip_address' => 'array',
    ];

    public function karyawans()
    {
        // Tetap menggunakan tabel pivot 'team' sesuai struktur Anda
        return $this->belongsToMany(User::class, 'team', 'area_id', 'karyawan_id');
    }
}

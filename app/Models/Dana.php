<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Dana extends Model
{
    use HasFactory;

    // Sesuai nama tabel di ERD Anda
    protected $table = 'dana'; 

    protected $fillable = [
        'karyawan_id', 
        'nominal', 
        'keperluan', 
        'status', 
        'catatan_admin'
    ];

    // Relasi ke tabel users
    public function user()
    {
        return $this->belongsTo(User::class, 'karyawan_id', 'id');
    }
}

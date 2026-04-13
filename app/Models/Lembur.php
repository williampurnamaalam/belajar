<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lembur extends Model
{
    use HasFactory;

    
    protected $table = 'lembur'; 

    protected $fillable = [
        'karyawan_id', 
        'tanggal', 
        'jam_mulai', 
        'jam_selesai', 
        'alasan', 
        'status', 
        'durasi_aktual_menit', 
        'catatan_admin'
    ];

    // Relasi ke User (Karyawan)
    public function user()
    {
        return $this->belongsTo(User::class, 'karyawan_id');
    }
}

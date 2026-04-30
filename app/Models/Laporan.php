<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Laporan extends Model
{
    use HasFactory;
    protected $table = 'laporan';

    protected $fillable = [
        'karyawan_id',
        'judul_laporan',
        'deskripsi',
        'file',
        'tanggal_kirim',
        'nominal_transaksi',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'karyawan_id');
    }

    public function getFileUrlAttribute()
    {
        if ($this->file_bukti) {
            return asset('storage/laporan_tugas/' . $this->file_bukti);
        }
        return null;
    }
}
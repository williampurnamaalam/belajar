<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class areakerja extends Model
{
    use HasFactory;
    protected $table = 'area';
    protected $fillable = ['lokasi', 'detail'];

    public function karyawans()
    {
        // 'team' adalah nama tabel pivot Anda
        return $this->belongsToMany(User::class, 'team', 'area_id', 'karyawan_id');
    }
}

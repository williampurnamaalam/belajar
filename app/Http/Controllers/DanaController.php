<?php

namespace App\Http\Controllers;

use App\Models\Dana;
use Illuminate\Http\Request;

class DanaController extends Controller
{
        public function adminIndex()
    {
        $daftarDana = Dana::with(['user.jabatan'])
            ->orderByRaw("FIELD(status, 'pending', 'disetujui', 'ditolak')")
            ->orderBy('created_at', 'desc')
            ->get();

        return view('persetujuan.dana.list', compact('daftarDana'));
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Dana;
use Illuminate\Http\Request;

class DanaController extends Controller
{
        public function adminIndex()
    {
        $danas = Dana::with(['user.jabatan'])
            ->orderByRaw("FIELD(status, 'pending', 'disetujui', 'ditolak')")
            ->orderBy('created_at', 'desc')
            ->get();

        return view('persetujuan.dana.list', compact('danas'));
    }

    public function adminUpdate(Request $request, $id)
    {
        $request->validate([
            'status'        => 'required|in:Disetujui,Ditolak',
            'catatan_admin' => 'nullable|string|max:500'
        ]);

        $dana = Dana::findOrFail($id);
        
        $dana->update([
            'status'        => $request->status,
            'catatan_admin' => $request->catatan_admin
        ]);

        return redirect()->back()->with('success', 'Status pengajuan dana berhasil diperbarui!');
    }
    public function index()
    {
        $danas = Dana::where('karyawan_id', auth()->id())->latest()->get();
        return view('persetujuan.dana.index', compact('danas'));
    }

 
    public function store(Request $request)
    {
        $request->validate([
            'nominal'   => 'required|numeric|min:1000',
            'keperluan' => 'required|string|max:500',
        ]);

        Dana::create([
            'karyawan_id' => auth()->id(),
            'nominal'     => $request->nominal,
            'keperluan'   => $request->keperluan,
            'status'      => 'Pending',
        ]);

        return redirect()->back()->with('success', 'Pengajuan dana berhasil dikirim dan sedang menunggu persetujuan.');
    }   
}

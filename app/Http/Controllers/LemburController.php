<?php

namespace App\Http\Controllers;

use App\Models\Lembur;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class LemburController extends Controller
{
    // ==========================================
    // AREA KARYAWAN
    // ==========================================

    public function index()
    {
        $riwayatLembur = Lembur::where('karyawan_id', auth()->id())
                        ->orderBy('created_at', 'desc')
                        ->get();
                        
        return view('persetujuan.lembur.index', compact('riwayatLembur'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal'     => 'required|date|after_or_equal:today', 
            'jam_mulai'   => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'alasan'      => 'required|string|max:255'
        ], [
            'jam_selesai.after' => 'Jam selesai lembur harus lebih dari jam mulai.'
        ]);

        Lembur::create([
            'karyawan_id' => auth()->id(),
            'tanggal'     => $request->tanggal,
            'jam_mulai'   => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'alasan'      => $request->alasan,
            'status'      => 'pending'
        ]);

        return redirect()->back()->with('success', 'Pengajuan lembur berhasil dikirim dan menunggu persetujuan HRD/Manager.');
    }

    // ==========================================
    // AREA ADMIN / HRD
    // ==========================================

    public function adminIndex(Request $request)
    {
        $daftarLembur = Lembur::with(['user.jabatan'])
                ->orderByRaw("FIELD(status, 'pending', 'disetujui', 'ditolak')")
                ->orderBy('tanggal', 'desc')
                ->get();
                
        return view('persetujuan.lembur.list', compact('daftarLembur'));
    }

    public function approveReject(Request $request, $id)
    {
        $request->validate([
            'status'        => ['required', Rule::in(['disetujui', 'ditolak'])],
            'catatan_admin' => 'nullable|string|max:255'
        ]);

        $lembur = Lembur::findOrFail($id);
        
        $lembur->update([
            'status'        => $request->status,
            'catatan_admin' => $request->catatan_admin,
        ]);

        $pesan = $request->status == 'disetujui' ? 'Pengajuan lembur disetujui!' : 'Pengajuan lembur ditolak!';
        return redirect()->back()->with('success', $pesan);
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Cuti;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule; // Tambahkan ini untuk validasi rule

class CutiController extends Controller
{
    public function index()
    {
        $riwayatCuti = Cuti::where('karyawan_id', auth()->id())->orderBy('created_at', 'desc')->get();
        return view('persetujuan.cuti.index', compact('riwayatCuti'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tanggal_mulai'   => 'required|date',
            'tanggal_selesai' => 'required|date|after_or_equal:tanggal_mulai',
            'jenis_cuti'      => 'required',
            'alasan'          => 'required|string|max:255'
        ]);

        Cuti::create([
            'karyawan_id'     => auth()->id(),
            'tanggal_mulai'   => $request->tanggal_mulai,
            'tanggal_selesai' => $request->tanggal_selesai,
            'jenis_cuti'      => $request->jenis_cuti,
            'alasan'          => $request->alasan,
            'status'          => 'pending'
        ]);

        return redirect()->back()->with('success', 'Pengajuan berhasil dikirim dan menunggu persetujuan.');
    }
    public function adminIndex(Request $request)
    {
        // Pastikan di Model Cuti relasinya bernama 'user' bukan 'karyawan' agar ini tidak error
        $daftarCuti = Cuti::with(['user.jabatan'])
                ->orderByRaw("FIELD(status, 'pending', 'disetujui', 'ditolak')")
                ->orderBy('created_at', 'desc')
                ->get();
                
        return view('persetujuan.cuti.list', compact('daftarCuti'));
    }

    public function approveReject(Request $request, $id)
    {
        // VALIDASI KEAMANAN: Pastikan status hanya bisa diisi disetujui atau ditolak
        $request->validate([
            'status' => ['required', Rule::in(['disetujui', 'ditolak'])],
            'catatan_admin' => 'nullable|string|max:255'
        ]);

        $cuti = Cuti::findOrFail($id);
        
        $cuti->update([
            'status' => $request->status,
            'catatan_admin' => $request->catatan_admin,
        ]);

        $pesan = $request->status == 'disetujui' ? 'Pengajuan disetujui!' : 'Pengajuan ditolak!';
        return redirect()->back()->with('success', $pesan);
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class LaporanController extends Controller
{
    public function index()
    {
        $laporan = Laporan::where('karyawan_id', auth()->id())
                    ->orderBy('created_at', 'desc')
                    ->get();
                    
        return view('laporan.index', compact('laporan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'judul_laporan'     => 'required|string|max:255',
            'deskripsi'         => 'required',
            'nominal_transaksi' => 'nullable|numeric', 
            'file_bukti'        => 'nullable|file|mimes:jpg,png,pdf,docx,zip|max:5120',
        ]); 

        $data = new Laporan();   
        $data->karyawan_id       = auth()->id(); 
        $data->judul_laporan     = $request->judul_laporan;
        $data->deskripsi         = $request->deskripsi;
        $data->nominal_transaksi = $request->nominal_transaksi ?? 0; 
        
        $data->tanggal_kirim     = now()->toDateString();

        if ($request->hasFile('file_bukti')) {
            $file = $request->file('file_bukti');
            $namaFile = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('public/laporan_tugas', $namaFile);
            $data->file = $namaFile;
        }

        $data->save();
        return redirect()->back()->with('success', 'Laporan tugas berhasil dikirim!');
    }

    public function destroy($id)
    {   
    $data = Laporan::findOrFail($id);
    if ($data->file_bukti) {
        \Storage::delete('public/laporan_tugas/' . $data->file);
    }

    $data->delete();

    return redirect()->back()->with('success', 'Laporan berhasil dihapus!');
    }

    public function adminIndex()
    {
        $daftarLaporan = Laporan::with('user')->orderBy('created_at', 'desc')->get();
        $karyawans = User::where('role_id', 2)->get();
        return view('laporan.list', compact('daftarLaporan', 'karyawans'));
    }

    public function cetakLaporan(Request $request)
    {
        $query = Laporan::with('user');
        if ($request->filled('karyawan')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('nama', 'like', '%' . $request->karyawan . '%');
            });
        }
        if ($request->filled('bulan')) {
            $query->whereMonth('tanggal_kirim', $request->bulan);
        }

        if ($request->filled('tahun')) {
            $query->whereYear('tanggal_kirim', $request->tahun);
        }

        $laporan = $query->orderBy('tanggal_kirim', 'desc')->get();
        $total_nominal = $laporan->sum('nominal_transaksi');
        return view('laporan.print', compact('laporan', 'total_nominal', 'request'));
    }
}
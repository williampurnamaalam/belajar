<?php

namespace App\Http\Controllers;

use App\Models\Kriteria;
use Illuminate\Http\Request;

class KriteriaController extends Controller
{
    public function index()
    {
        $kriteria = Kriteria::orderBy('kode_kriteria', 'asc')->get();
        $totalBobot = Kriteria::sum('bobot'); 
        
        return view('kriteria.index', compact('kriteria', 'totalBobot'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_kriteria' => 'required|unique:kriteria,kode_kriteria|max:10',
            'nama_kriteria' => 'required|string|max:255',
            'jenis'         => 'required|in:Benefit,Cost',
            'bobot'         => 'required|numeric|min:1|max:100'
        ], [
            'kode_kriteria.required' => 'Kode kriteria wajib diisi.',
            'kode_kriteria.unique'   => 'Kode kriteria ini sudah digunakan, silakan gunakan kode lain.',
            'kode_kriteria.max'      => 'Kode kriteria maksimal berisi 10 karakter.',
            'nama_kriteria.required' => 'Nama kriteria wajib diisi.',
            'jenis.required'         => 'Jenis kriteria wajib dipilih.',
            'bobot.required'         => 'Bobot kriteria wajib diisi.',
            'bobot.numeric'          => 'Bobot harus berupa angka.',
            'bobot.min'              => 'Bobot minimal adalah 1.',
            'bobot.max'              => 'Bobot maksimal adalah 100.'
        ]);

        $data = new Kriteria();
        $data->kode_kriteria = $request->kode_kriteria;
        $data->nama_kriteria = $request->nama_kriteria;
        $data->jenis         = $request->jenis;
        $data->bobot         = $request->bobot;
        $data->save();

        return redirect()->back()->with('success', 'Data Kriteria berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'kode_kriteria' => 'required|max:10|unique:kriteria,kode_kriteria,' . $id,
            'nama_kriteria' => 'required|string|max:255',
            'jenis'         => 'required|in:Benefit,Cost',
            'bobot'         => 'required|numeric|min:1|max:100'
        ], [
            'kode_kriteria.required' => 'Kode kriteria tidak boleh kosong.',
            'kode_kriteria.unique'   => 'Kode kriteria ini sudah digunakan oleh kriteria lain.',
            'kode_kriteria.max'      => 'Kode kriteria maksimal berisi 10 karakter.',
            'nama_kriteria.required' => 'Nama kriteria tidak boleh kosong.',
            'jenis.required'         => 'Jenis kriteria wajib dipilih.',
            'bobot.required'         => 'Bobot wajib diisi.',
            'bobot.numeric'          => 'Bobot harus berupa angka.',
            'bobot.min'              => 'Bobot minimal adalah 1.',
            'bobot.max'              => 'Bobot maksimal adalah 100.'
        ]);

        $data = Kriteria::findOrFail($id);
        $data->kode_kriteria = $request->kode_kriteria;
        $data->nama_kriteria = $request->nama_kriteria;
        $data->jenis         = $request->jenis;
        $data->bobot         = $request->bobot;
        $data->save();

        return redirect()->back()->with('success', 'Data Kriteria berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $data = Kriteria::findOrFail($id);
        $data->delete();

        return redirect()->back()->with('success', 'Data Kriteria berhasil dihapus!');
    }
}
<?php

namespace App\Http\Controllers;

use App\Models\Divisi;
use Illuminate\Http\Request;

class DivisiController extends Controller
{
        public function index()
    {
        $divisis = Divisi::latest('id')->get();
        return view('management.divisi.index', compact('divisis'));
    }

    public function create()
    {
        return view('management.divisi.create');
    }

public function store(Request $request)
{
    $request->validate([
        'divisi' => 'required|string|max:100|unique:divisi,divisi',
    ], [
        'divisi.required' => 'Nama divisi wajib diisi.',
        'divisi.unique'   => 'Nama divisi ini sudah terdaftar di sistem.',
        'divisi.max'      => 'Nama divisi terlalu panjang (maksimal 100 karakter).',
    ]);

    Divisi::create([
        'divisi' => $request->divisi,
    ]);

    return redirect()->route('divisi')
        ->with('success', 'divisi baru berhasil ditambahkan!');
}

    public function show($id)
    {
        $divisi = Divisi::findOrFail($id);
        return view('management.divisi.show', compact('divisi'));
    }

    public function edit($id)
    {
        $divisi = Divisi::findOrFail($id);
        return view('management.divisi.edit', compact('divisi'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'divisi' => 'required|string|max:100|unique:divisi,divisi,' . $id,
        ], [
            'divisi.required' => 'Nama divisi wajib diisi.',
            'divisi.unique'   => 'Nama divisi ini sudah terdaftar di sistem.',
        ]);

        $divisi = Divisi::findOrFail($id);
        $divisi->update([
            'divisi' => $request->divisi,
        ]);

        return redirect()->route('divisi')
            ->with('success', 'Data divisi berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $divisi = Divisi::findOrFail($id);
        $divisi->delete();

        return redirect()->route('divisi')
            ->with('success', 'divisi berhasil dihapus dari sistem!');
    }
}
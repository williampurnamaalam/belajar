<?php

namespace App\Http\Controllers;

use App\Models\Jabatan;
use Illuminate\Http\Request;

class JabatanController extends Controller
{
    public function index()
    {
        $jabatans = Jabatan::latest('id')->get();
        return view('management.jabatan.index', compact('jabatans'));
    }

    public function create()
    {
        return view('management.jabatan.create');
    }

public function store(Request $request)
{
    $request->validate([
        'jabatan' => 'required|string|max:100|unique:jabatan,jabatan',
    ], [
        'jabatan.required' => 'Nama jabatan wajib diisi.',
        'jabatan.unique'   => 'Nama jabatan ini sudah terdaftar di sistem.',
        'jabatan.max'      => 'Nama jabatan terlalu panjang (maksimal 100 karakter).',
    ]);

    Jabatan::create([
        'jabatan' => $request->jabatan,
    ]);

    return redirect()->route('jabatan')
        ->with('success', 'Jabatan baru berhasil ditambahkan!');
}

    public function show($id)
    {
        $jabatan = Jabatan::findOrFail($id);
        return view('management.jabatan.show', compact('jabatan'));
    }

    public function edit($id)
    {
        $jabatan = Jabatan::findOrFail($id);
        return view('management.jabatan.edit', compact('jabatan'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'jabatan' => 'required|string|max:100|unique:jabatan,jabatan,' . $id,
        ], [
            'jabatan.required' => 'Nama jabatan wajib diisi.',
            'jabatan.unique'   => 'Nama jabatan ini sudah terdaftar di sistem.',
        ]);

        $jabatan = Jabatan::findOrFail($id);
        $jabatan->update([
            'jabatan' => $request->jabatan,
        ]);

        return redirect()->route('jabatan')
            ->with('success', 'Data jabatan berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $jabatan = Jabatan::findOrFail($id);
        $jabatan->delete();

        return redirect()->route('jabatan')
            ->with('success', 'Jabatan berhasil dihapus dari sistem!');
    }
}
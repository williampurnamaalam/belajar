<?php

namespace App\Http\Controllers;

use App\Models\Areakerja;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AreakerjaController extends Controller
{
    public function index()
    {
        $areakerjas = Areakerja::latest('id')->get();
        
        return view('areakerja.index', compact('areakerjas'));
    }
    public function show($id)
    {
        $area = Areakerja::with(['karyawans.jabatan', 'karyawans.divisi'])->findOrFail($id);
        return view('areakerja.show', compact('area'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'lokasi' => 'required|string|max:255',
            'detail' => 'nullable|string|max:255',
        ], [
            'lokasi.required' => 'Lokasi area harus diisi.',
        ]);

        Areakerja::create([
            'lokasi' => $request->lokasi,
            'detail' => $request->detail,
        ]);

        return redirect()->route('areakerja')->with('success', 'Area kerja berhasil ditambahkan.');
    }


    public function edit($id)
    {
        $area = Areakerja::findOrFail($id);
        return response()->json($area);
    }

    public function update(Request $request, $id)
    {
        $area = Areakerja::findOrFail($id);
        
        $request->validate([
            'lokasi' => 'required|string|max:255',
            'detail' => 'nullable|string|max:255',
        ]);

        $area->update([
            'lokasi' => $request->lokasi,
            'detail' => $request->detail,
        ]);

        return redirect()->route('areakerja')->with('success', 'Area kerja berhasil diperbarui.');
    }


    public function destroy($id)
    {
        $area = Areakerja::findOrFail($id);
        $area->delete();

        return redirect()->route('areakerja')->with('success', 'Area kerja berhasil dihapus.');
    }

    public function insert($id)
    {
        $area = Areakerja::findOrFail($id);
        $karyawans = User::with(['jabatan', 'divisi'])->get();
        $currentTeamIds = $area->karyawans->pluck('id')->toArray();

        return view('areakerja.insert', compact('area', 'karyawans', 'currentTeamIds'));
    }

    public function storeTeam(Request $request, $id)
    {
        $area = Areakerja::findOrFail($id);
        $area->karyawans()->sync($request->input('karyawan_ids', []));

        return redirect()->route('areakerja')
                        ->with('success', 'Anggota tim area berhasil diperbarui!');
    }
}
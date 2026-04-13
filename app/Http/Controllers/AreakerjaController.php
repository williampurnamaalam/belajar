<?php

namespace App\Http\Controllers;

use App\Models\Areakerja;
use App\Models\User;
use Illuminate\Http\Request;

class AreakerjaController extends Controller
{
    public function index()
    {

        $areakerjas = Areakerja::latest()->get();
        return view('areakerja.index', compact('areakerjas'));
    }

    public function show($id)
    {
        $area = Areakerja::with(['karyawans.jabatan', 'karyawans.divisi'])->findOrFail($id);
        return view('areakerja.show', compact('area'));
    }

    public function store(Request $request)
    {
        dd($request->all());
        $request->validate([
            'lokasi'     => 'required|string|max:255',
            'ip_address' => 'required', 
            'latitude'   => 'required',
            'longitude'  => 'required',
            'radius'     => 'required|numeric',
        ]);

       
        $ips = array_map('trim', explode(',', $request->ip_address));

        Areakerja::create([
            'lokasi'     => $request->lokasi,
            'ip_address' => $ips,
            'latitude'   => $request->latitude,
            'longitude'  => $request->longitude,
            'radius'     => $request->radius,
            'detail'     => $request->detail,
        ]);

        return redirect()->route('areakerja')->with('success', 'Area kerja dan parameter GPS berhasil ditambah.');
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
            'lokasi'     => 'required|string|max:255',
            'latitude'   => 'required',
            'longitude'  => 'required',
            'radius'     => 'required|numeric',
            'detail'     => 'nullable|string|max:255',
        ]);

        $area->update([
            'lokasi'    => $request->lokasi,
            'latitude'  => $request->latitude,
            'longitude' => $request->longitude,
            'radius'    => $request->radius,
            'detail'    => $request->detail,
        ]);

        return redirect()->route('areakerja')->with('success', 'Data area berhasil diperbarui.');
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

        return redirect()->route('areakerja')->with('success', 'Anggota tim area berhasil diperbarui!');
    }

    public function updateIp(Request $request, $id)
    {
        $request->validate([
            'ip_address' => 'required',
        ]);

        $ips = array_map('trim', explode(',', $request->ip_address));

        $area = Areakerja::findOrFail($id);
        $area->update([
            'ip_address' => $ips 
        ]);

        return redirect()->back()->with('success', 'Daftar IP kantor berhasil diperbarui!');
    }
}
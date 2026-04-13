<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::latest()->get(); 
        return view('management.role.index', compact('roles'));
    }

    public function create()
    {
        return view('management.role.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'role' => 'required|string|max:100|unique:role,role',
        ], [
            'role.required' => 'Nama role wajib diisi.',
            'role.unique'   => 'Nama role ini sudah terdaftar di sistem.',
            'role.max'      => 'Nama role terlalu panjang (maksimal 100 karakter).',
        ]);

        Role::create([
            'role' => $request->role,
        ]);

        return redirect()->route('role')
            ->with('success', 'Role baru berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $role = Role::findOrFail($id);
        return view('management.role.edit', compact('role'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
        
            'role' => 'required|string|max:100|unique:role,role,' . $id,
        ], [
            'role.required' => 'Nama role wajib diisi.',
            'role.unique'   => 'Nama role ini sudah terdaftar di sistem.',
        ]);

        $role = Role::findOrFail($id);
        $role->update([
            'role' => $request->role,
        ]);

        return redirect()->route('role')
            ->with('success', 'Data role berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $role = Role::findOrFail($id);
        if (strtolower($role->role) == 'admin') {
            return redirect()->route('role')->with('error', 'Role Admin tidak boleh dihapus!');
        }

        $role->delete();
        return redirect()->route('role')
            ->with('success', 'Role berhasil dihapus dari sistem!');
    }
}
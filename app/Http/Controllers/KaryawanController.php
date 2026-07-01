<?php

namespace App\Http\Controllers;

use App\Models\Divisi;
use App\Models\Jabatan;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;

class KaryawanController extends Controller
{
    public function index()
    {
        $karyawans = User::with(['jabatan', 'divisi', 'role'])->latest()->get();

        return view('management.karyawan.index', compact('karyawans'));
    }

    public function create()
    {
        $roles = Role::all();
        $jabatans = Jabatan::all();
        $divisis = Divisi::all();

        
        return view('management.karyawan.create', compact('roles', 'jabatans', 'divisis'));
    }
       public function edit($id)
    {
        $karyawan = User::findOrFail($id); 
        $jabatans = Jabatan::all();       
        $divisis = Divisi::all();          
        $roles = Role::all(); // TAMBAHKAN INI: Ambil data role
        
        // TAMBAHKAN $roles ke dalam compact
        return view('management.karyawan.edit', compact('karyawan', 'jabatans', 'divisis', 'roles'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        $request->validate([
            'nama'          => ['required', 'string', 'max:100'],
            'email'         => ['required', 'email', 'max:100', 'unique:users,email,' . $id],
            'password'      => ['nullable', 'min:4'],
            'tanggal_lahir' => ['required', 'date'],
            'telepon'       => ['required', 'max:15'],
            'jabatan_id'    => ['required'], 
            'divisi_id'     => ['required'], 
            'role_id'       => ['required'], 
            'nik'           => ['required', 'max:50'],
            'nip'           => ['required', 'max:50'],
            'gender'        => ['required', Rule::in(['Pria', 'Wanita'])], 
            'profile_picture' => ['nullable', 'image', 'mimes:jpg,png,jpeg', 'max:2048'],
        ]);

        try {
            $data = $request->except(['password', 'profile_picture']); 
            if ($request->filled('password')) {
                $data['password'] = Hash::make($request->password);
            }

            if ($request->hasFile('profile_picture')) {
                if ($user->profile_picture && Storage::exists('public/' . $user->profile_picture)) {
                    Storage::delete('public/' . $user->profile_picture);
                }
                $path = $request->file('profile_picture')->store('profile_pictures', 'public');
                $data['profile_picture'] = $path;
            }

            $user->update($data);
            return redirect('/karyawan')->with('success', 'Data karyawan berhasil diperbarui');

        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan sistem: ' . $e->getMessage());
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama'          => ['required', 'max:100'],
            'email'         => ['required', 'email', 'max:100', 'unique:users,email'],
            'tanggal_lahir' => ['required', 'date'],
            'telepon'       => ['required', 'max:15'],
            'jabatan_id'    => ['required', 'exists:jabatan,id'], 
            'divisi_id'     => ['required', 'exists:divisi,id'],
            'role_id'       => ['required', 'exists:role,id'],
            'nik'           => ['required', 'max:50'],
            'gender'        => ['required', Rule::in(['Pria', 'Wanita'])],
        ]);

        // 2. LOGIKA MEMBUAT NIP OTOMATIS
        $tahun = date('Y'); 
        $prefix = "CH-" . $tahun . "-"; 
        $lastUser = User::where('nip', 'LIKE', $prefix . '%')
                        ->orderBy('nip', 'desc')
                        ->first();

        if ($lastUser) {
  
            $lastSerialNumber = substr($lastUser->nip, -4); 
            $nextSerialNumber = sprintf('%04d', intval($lastSerialNumber) + 1);
        } else {
            $nextSerialNumber = '0001';
        }

        $autoNip = $prefix . $nextSerialNumber; // Hasil akhir: EMP-2026-0001


        // 3. Simpan data ke Database
        $data = new User();
        $data->nama = $request->nama;
        $data->email = $request->email;
        $data->password = Hash::make($request->password);
        $data->nip = $autoNip; 
        $data->role_id = $request->role_id;
        $data->jabatan_id = $request->jabatan_id;
        $data->divisi_id = $request->divisi_id;
        $data->tanggal_lahir = $request->tanggal_lahir;
        $data->telepon = $request->telepon;
        $data->nik = $request->nik;
        $data->gender = $request->gender;
        $data->save();

        return redirect('/karyawan')->with('success', 'Berhasil menambah data karyawan dengan NIP: ' . $autoNip);
    }


    public function destroy($id)
    {
        $user = User::findOrFail($id);
        if ($user->id === auth()->id()) {
            return redirect('/karyawan')->with('error', 'Anda tidak dapat menghapus akun Anda sendiri!');
        }

        if ($user->profile_picture) {
            Storage::delete('public/' . $user->profile_picture);
        }

        $user->delete();

        return redirect('/karyawan')->with('success', 'Karyawan berhasil dihapus');
    }

    public function show($id)
    {

        $user = User::with(['role', 'jabatan', 'divisi'])->findOrFail($id);
        return view('profile.index', compact('user'));
    }

    public function showProfile($id = null) 
    {
        $userId = $id ?: auth()->id(); 

        $user = User::findOrFail($userId);
        return view('profile.index', compact('user'));
    }

    public function editProfile(){
        $user = Auth::user();
        return view('profile.edit', compact('user'));
    }

   public function updateProfile(Request $request)
    {
        $user = auth()->user();
        $request->validate([
            'nama'            => ['required', 'string', 'max:100'],
            'telepon'         => ['required', 'max:15'],
            'nik'             => ['required', 'max:50'],
            'tanggal_lahir'   => ['required', 'date'],
            'password'        => ['nullable', 'min:4'], 
            'profile_picture' => ['nullable', 'image', 'mimes:jpg,png,jpeg', 'max:2048'],
        ]);

        if ($request->hasFile('profile_picture')) {
            if ($user->profile_picture) {
                Storage::delete('public/' . $user->profile_picture);
            }
            $path = $request->file('profile_picture')->store('profile_pictures', 'public');
            $user->profile_picture = $path;
        }

        $user->nama          = $request->nama;
        $user->telepon       = $request->telepon;
        $user->nik           = $request->nik;
        $user->tanggal_lahir = $request->tanggal_lahir;

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('profile')->with('success', 'Profil Anda berhasil diperbarui!');
    }




}


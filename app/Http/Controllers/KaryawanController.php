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
    public function index(){
        return view('management.karyawan.index',);
    }

    public function create()
    {
        // 1. Ambil semua data dari tabel masing-masing
        $roles = Role::all();
        $jabatans = Jabatan::all();
        $divisis = Divisi::all();

        
        return view('management.karyawan.create', compact('roles', 'jabatans', 'divisis'));
    }

    public function store (Request $request)
    {
        $request->validate([
            'nama'=>['required','max:100'],
            'email'=>['required','max:100'],
            'password'=>['required','min:4'],
            'tanggal_lahir'=>['required','max:100'],
            'telepon'=>['required','max:15'],
            'jabatan_id'=>['required','exists:jabatans,id'],
            'divisi_id'=>['required','exists:divisis,id'],
            'nik'=>['required','max:50'],
            'nip'=>['required','max:50'],
            'gender'=>['required',Rule::in(['pria','wanita'])],
            'role_id'=>['required','exists:roles,id'],
        ]);
        User::create($request->validated());
        return redirect('/karyawan')->with('success','berhasil menambah data');


    }


















    public function showProfile($id = null) 
    {
        // Jika $id kosong, ambil ID dari user yang sedang login
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


<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;


class LoginContoller extends Controller
{
    public function showLogin()
    {
        return view('page_login.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->username)
        ->orWhere('telepon', $request->username)
        ->first();

        if ($user && Hash::check($request->password, $user->password)){
            Auth::login($user);
            $request->session()->regenerate();

            // return redirect('das')
            return redirect('dashboard')->with('success', 'Login Berhasil');
        }
        \Log::info($request->all);
        return back()->withErrors(['login' => 'No Telpon / email atau password salah'])->withInput();
    }


    // public function login(Request $request)
    // {
    //     request()->validate([
    //         'telepon'=>'required',
    //         'password'=>'required',
    //     ]);
    //     $credentials = $request->only('telepon','password');

    //     $user = User::where('telepon', $request->telepon)
    //     ->first();
    //         if(!$user)
    //             {
    //                 return back()->withErrors([
    //                     'telepon'=> 'telepon tidak terdaftar atau salah'
    //                 ])->withInput();
    //             }
    //         if(!Auth::validate($credentials))
    //         {
    //             return back()->withErrors([
    //                 'password' => 'Password salah'
    //             ])->withInput();
    //         }
    //         if(Auth::attempt($credentials, $request->remember))
    //         {
    //             $request->session()->regenerate();
    //             return redirect()->intended('dashboard')->with('success', 'Login Berhasil');
    //         }
    //         \Log::info($credentials);
    //     return back()->withErrors([
    //         'login' => 'Terjadi kesalahan saat login']);

    // }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/');
    }

    public function dashboard(){
        return view ('home');
    }



}

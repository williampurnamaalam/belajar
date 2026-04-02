<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;

class LoginContoller extends Controller
{
    public function showLogin()
    {
        return view('page_login.login');
    }


    public function login(Request $request)
    {
        request()->validate([
            'email'=>'required',
            'password'=>'required',
        ]);
        $credentials = $request->only('email','password');

        $user = User::where('email', $request->email)->first();
            if(!$user)
                {
                    return back()->withErrors([
                        'email'=> 'Email tidak terdaftar atau salah'
                    ])->withInput();
                }
            if(!Auth::validate($credentials))
            {
                return back()->withErrors([
                    'password' => 'Password salah'
                ])->withInput();
            }
            if(Auth::attempt($credentials, $request->remember))
            {
                $request->session()->regenerate();
                return redirect()->intended('dashboard')->with('success', 'Login Berhasil');
            }
            \Log::info($credentials);
        return back()->withErrors([
            'login' => 'Terjadi kesalahan saat login']);

    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }

    public function dashboard(){
        return view ('home');
    }



}

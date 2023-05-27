<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function index()
    {
        return view('auth.login');
    }

    public function auth(Request $request): RedirectResponse
    {
        $user = $request->validate([
            'username' => 'required',
            'password' => 'required'
        ]);

        if (Auth::attempt($user)) {
            $user = Auth::user();
            $request->session()->regenerate();

            if ($user->status == 'Aktif') {
                return redirect()->intended('dashboard');
            } else if ($user->status == 'Blokir') {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect()->route('login')->with('failed', 'Akun anda telah terblokir. Silahkan hubungi Admin.');
            } else {
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return redirect()->route('login')->with('failed', 'Akun anda belum terverifikasi. Silahkan melakukan verifikasi terlebih dahulu');
            }
        } else {
            return redirect()->route('login')->with('failed', 'Username atau password salah!')->withInput();
        }

        return redirect()->route('login');
    }

    public function logout(Request $request): RedirectResponse
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login');
    }
}

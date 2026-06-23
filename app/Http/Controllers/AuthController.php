<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        if ($request->username === 'admingaji' && $request->password === 'admingaji') {
            // Mock authentication
            session(['user_role' => 'admin_penggajian', 'is_logged_in' => true]);
            return redirect()->route('dashboard.index');
        } elseif ($request->username === 'pimpinan' && $request->password === 'pimpinan') {
            session(['user_role' => 'pimpinan', 'is_logged_in' => true]);
            return redirect()->route('pimpinan.dashboard');
        }

        return back()->withErrors([
            'loginError' => 'ID Pengguna atau Kata Sandi salah.',
        ])->withInput();
    }
    public function logout(Request $request)
    {
        $request->session()->flush();
        return redirect()->route('login');
    }
}

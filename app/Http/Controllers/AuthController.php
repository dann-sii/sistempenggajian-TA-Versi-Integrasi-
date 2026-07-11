<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AuthController extends Controller
{
    public static function getInitialUsers()
    {
        return [
            [
                'id' => 1,
                'nama' => 'Admin Penggajian',
                'username' => 'admingaji',
                'password' => 'admingaji',
                'role' => 'admin_penggajian',
                'status' => 'Aktif',
                'foto' => '', // (e.g.: 'admin.jpg').
            ],
            [
                'id' => 2,
                'nama' => 'Pimpinan',
                'username' => 'pimpinan',
                'password' => 'pimpinan',
                'role' => 'pimpinan',
                'status' => 'Aktif',
                'foto' => '', // Tulis nama file foto di sini (contoh: 'pimpinan.jpg'). Pastikan fotonya ada di folder public/images/
            ],
        ];
    }

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

        if (!session()->has('users_data')) {
            session()->put('users_data', self::getInitialUsers());
        }

        $users = session('users_data');

        $authenticatedUser = collect($users)->first(function ($user) use ($request) {
            return $user['username'] === $request->username &&
                $user['password'] === $request->password &&
                strtolower($user['status']) === 'aktif';
        });

        if ($authenticatedUser) {
            session([
                'user_role' => $authenticatedUser['role'],
                'is_logged_in' => true,
                'user_name' => $authenticatedUser['nama'],
                'user_foto' => $authenticatedUser['foto'] ?? null
            ]);

            if ($authenticatedUser['role'] === 'admin_penggajian') {
                return redirect()->route('dashboard.index');
            } elseif ($authenticatedUser['role'] === 'pimpinan') {
                return redirect()->route('pimpinan.dashboard');
            }
        }

        return back()->withErrors([
            'loginError' => 'ID Pengguna atau Kata Sandi salah, atau akun Anda sedang tidak aktif.',
        ])->withInput();
    }

    public function logout(Request $request)
    {
        $request->session()->flush();
        return redirect()->route('login');
    }
}

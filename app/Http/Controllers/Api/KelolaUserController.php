<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class KelolaUserController extends Controller
{
    private function getUsers()
    {
        if (!session()->has('users_data')) {
            session()->put('users_data', \App\Http\Controllers\AuthController::getInitialUsers());
        }
        return session('users_data');
    }

    private function saveUsers($users)
    {
        session()->put('users_data', $users);
    }

    public function indexAdmin(Request $request)
    {
        $users = collect($this->getUsers());

        if ($request->has('search')) {
            $search = strtolower($request->search);
            $users = $users->filter(function ($user) use ($search) {
                return str_contains(strtolower($user['nama']), $search) ||
                    str_contains(strtolower($user['username']), $search);
            });
        }

        if ($request->has('role') && $request->role != '') {
            $users = $users->filter(function ($user) use ($request) {
                return $user['role'] == $request->role;
            });
        }

        if ($request->has('status') && $request->status != '') {
            $users = $users->filter(function ($user) use ($request) {
                return strtolower($user['status']) == strtolower($request->status);
            });
        }

        return view('admin_penggajian.kelola_user', [
            'users' => $users->values()->all(),
            'search' => $request->search,
            'roleFilter' => $request->role,
            'statusFilter' => $request->status
        ]);
    }

    public function indexPimpinan(Request $request)
    {
        $users = collect($this->getUsers());

        if ($request->has('search')) {
            $search = strtolower($request->search);
            $users = $users->filter(function ($user) use ($search) {
                return str_contains(strtolower($user['nama']), $search) ||
                    str_contains(strtolower($user['username']), $search);
            });
        }

        if ($request->has('role') && $request->role != '') {
            $users = $users->filter(function ($user) use ($request) {
                return $user['role'] == $request->role;
            });
        }

        if ($request->has('status') && $request->status != '') {
            $users = $users->filter(function ($user) use ($request) {
                return strtolower($user['status']) == strtolower($request->status);
            });
        }

        return view('pimpinan.kelola_user', [
            'users' => $users->values()->all(),
            'search' => $request->search,
            'roleFilter' => $request->role,
            'statusFilter' => $request->status
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required',
            'username' => 'required',
            'password' => 'required|min:8|confirmed',
            'role' => 'required',
            'status' => 'required',
        ], [
            'password.confirmed' => 'Konfirmasi password tidak cocok!'
        ]);

        $users = collect($this->getUsers());

        // Cek username unik
        if ($users->contains('username', $request->username)) {
            return back()->with('error', 'Username sudah digunakan!');
        }

        $newId = $users->max('id') + 1;
        $newUser = [
            'id' => $newId,
            'nama' => $request->nama,
            'username' => $request->username,
            'password' => $request->password,
            'role' => $request->role,
            'status' => $request->status,
        ];

        $users->push($newUser);
        $this->saveUsers($users->all());

        return back()->with('success', 'User berhasil ditambahkan!');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required',
            'username' => 'required',
            'password' => 'nullable|min:8|confirmed',
            'role' => 'required',
            'status' => 'required',
        ], [
            'password.confirmed' => 'Konfirmasi password tidak cocok!'
        ]);

        $users = collect($this->getUsers());

        // Cek username unik (kecuali milik sendiri)
        $existing = $users->firstWhere('username', $request->username);
        if ($existing && $existing['id'] != $id) {
            return back()->with('error', 'Username sudah digunakan!');
        }

        $users = $users->map(function ($user) use ($id, $request) {
            if ($user['id'] == $id) {
                $user['nama'] = $request->nama;
                $user['username'] = $request->username;
                $user['role'] = $request->role;
                $user['status'] = $request->status;
                if ($request->filled('password')) {
                    $user['password'] = $request->password;
                }
            }
            return $user;
        });

        $this->saveUsers($users->all());

        return back()->with('success', 'Data user berhasil diperbarui!');
    }

    public function destroy($id)
    {
        $users = collect($this->getUsers());

        $userToDelete = $users->firstWhere('id', $id);

        if (!$userToDelete) {
            return back()->with('error', 'User tidak ditemukan!');
        }

        // Validasi: pastikan setidaknya ada 1 admin dan 1 pimpinan yang aktif
        $role = $userToDelete['role'];
        if ($userToDelete['status'] == 'Aktif') {
            $activeUsersInRole = $users->filter(function ($u) use ($role) {
                return $u['role'] == $role && $u['status'] == 'Aktif';
            })->count();

            if ($activeUsersInRole <= 1) {
                return back()->with('error', 'Gagal dihapus! Harus ada minimal 1 ' . str_replace('_', ' ', $role) . ' yang aktif.');
            }
        }

        $users = $users->reject(function ($user) use ($id) {
            return $user['id'] == $id;
        });

        $this->saveUsers($users->all());

        return back()->with('success', 'User berhasil dihapus!');
    }
}

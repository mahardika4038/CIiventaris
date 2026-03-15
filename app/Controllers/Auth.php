<?php

namespace App\Controllers;

use App\Models\UserModel;

class Auth extends BaseController
{
    public function login()
    {
        // kalau sudah login, jangan bisa akses login lagi
        if (session()->get('isLoggedIn')) {
            $role = session()->get('role');

            if (in_array($role, ['superadmin', 'admin'])) {
                return redirect()->to('/dashboard');
            } else {
                return redirect()->to('/client');
            }
        }

        return view('auth/login');
    }

    public function attempt()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        $user = (new UserModel())
            ->where('username', $username)
            ->first();

        if (!$user || !password_verify($password, $user['password_hash'])) {
            return redirect()->back()->with('error', 'Username atau password salah');
        }

        // set session
        session()->set([
            'id'         => $user['id'],       // Tambahkan ini untuk kompatibilitas
            'user_id'    => $user['id'],
            'nama'       => $user['nama'],
            'role'       => $user['role'],
            'isLoggedIn' => true,
        ]);

        // 🔥 Redirect berdasarkan role
        if (in_array($user['role'], ['superadmin', 'admin'])) {
            return redirect()->to('/dashboard');
        } else {
            return redirect()->to('/client');
        }
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/login');
    }
}
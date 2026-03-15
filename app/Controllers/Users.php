<?php

namespace App\Controllers;

use App\Models\UserModel;

class Users extends BaseController
{
    protected $uModel;

    public function __construct()
    {
        $this->uModel = new UserModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Manajemen Akun',
            'rows'  => $this->uModel->findAll()
        ];
        return view('users/index', $data);
    }

    public function create()
    {
        return view('users/create', ['title' => 'Tambah User']);
    }

    public function store()
    {
        // Validasi agar username tidak kembar
        if (!$this->validate([
            'username' => 'required|is_unique[users.username]',
            'password' => 'required|min_length[5]'
        ])) {
            return redirect()->back()->withInput()->with('error', 'Username sudah terdaftar atau password kurang panjang.');
        }

        $this->uModel->save([
            'nama'          => $this->request->getPost('nama'),
            'username'      => $this->request->getPost('username'),
            'password_hash' => password_hash($this->request->getPost('password'), PASSWORD_DEFAULT),
            'role'          => $this->request->getPost('role')
        ]);

        return redirect()->to('/users')->with('msg', 'User berhasil disimpan!');
    }

    public function edit($id)
    {
        $data = [
            'title' => 'Edit User',
            'row'   => $this->uModel->find($id)
        ];
        return view('users/create', $data);
    }

    public function update($id)
    {
        $user = $this->uModel->find($id);
        $usernameBaru = $this->request->getPost('username');

        // Validasi username unik jika diubah
        $rules = [];
        if ($usernameBaru !== $user['username']) {
            $rules['username'] = 'required|is_unique[users.username]';
        }

        if (!empty($this->request->getPost('password'))) {
            $rules['password'] = 'min_length[5]';
        }

        if (!empty($rules) && !$this->validate($rules)) {
            return redirect()->back()->withInput()->with('error', 'Username sudah terdaftar atau password kurang panjang.');
        }

        $data = [
            'nama'     => $this->request->getPost('nama'),
            'username' => $usernameBaru,
            'role'     => $this->request->getPost('role')
        ];

        // Update password jika diisi
        if (!empty($this->request->getPost('password'))) {
            $data['password_hash'] = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
        }

        $this->uModel->update($id, $data);

        return redirect()->to('/users')->with('msg', 'User berhasil diupdate!');
    }

    public function delete($id)
    {
        $this->uModel->delete($id);
        return redirect()->to('/users')->with('msg', 'User dihapus.');
    }
}


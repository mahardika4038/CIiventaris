<?php

namespace App\Controllers;

use App\Models\LokasiModel;

class Lokasi extends BaseController
{
    public function index()
    {
        $model = new LokasiModel();

        $data['rows'] = $model->findAll();

        return view('lokasi/index', $data);
    }

    public function create()
    {
        return view('lokasi/create');
    }

    public function store()
    {
        $model = new LokasiModel();

        $model->insert([
            'nama_lokasi' => $this->request->getPost('nama_lokasi')
        ]);

        return redirect()->to('/lokasi')->with('msg', 'Lokasi berhasil ditambahkan!');
    }

    public function edit($id)
    {
        $model = new LokasiModel();
        $data['row'] = $model->find($id);
        
        return view('lokasi/create', $data);
    }

    public function update($id)
    {
        $model = new LokasiModel();

        $model->update($id, [
            'nama_lokasi' => $this->request->getPost('nama_lokasi')
        ]);

        return redirect()->to('/lokasi')->with('msg', 'Lokasi berhasil diupdate!');
    }

    public function delete($id)
    {
        $model = new LokasiModel();
        $model->delete($id);

        return redirect()->to('/lokasi')->with('msg', 'Lokasi berhasil dihapus!');
    }
}

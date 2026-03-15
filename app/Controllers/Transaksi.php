<?php

namespace App\Controllers;

use App\Models\BarangModel;
use App\Models\TransaksiModel;
use App\Models\UserModel;

class Transaksi extends BaseController
{
    protected $tModel;
    protected $bModel;
    protected $uModel;

    public function __construct()
    {
        $this->tModel = new TransaksiModel();
        $this->bModel = new BarangModel();
        $this->uModel = new UserModel();
    }

    public function index()
    {
        $data['rows'] = $this->tModel->select('transaksi.*, barang.nama_barang, barang.kode_barang, users.nama as nama_user')
            ->join('barang', 'barang.id = transaksi.barang_id', 'left')
            ->join('users', 'users.id = transaksi.user_id', 'left')
            ->orderBy('transaksi.id', 'DESC')
            ->findAll();

        return view('transaksi/index', $data);
    }

    public function create()
    {
        $data = [
            'title'       => 'Tambah Transaksi',
            'list_barang' => $this->bModel->findAll(),
            'list_user'   => $this->uModel->where('role', 'client')->findAll()
        ];
        return view('transaksi/create', $data);
    }

    public function store()
    {
        $barang = $this->bModel->find($this->request->getPost('barang_id'));
        $qty = (int)$this->request->getPost('qty');

        // Validasi stok
        if ($barang['stok'] < $qty) {
            return redirect()->back()->withInput()->with('error', 'Stok tidak mencukupi!');
        }

        // Kurangi stok
        $this->bModel->update($barang['id'], [
            'stok' => $barang['stok'] - $qty
        ]);

        // Simpan transaksi
        $this->tModel->save([
            'kode_transaksi' => 'TRX-' . strtoupper(substr(uniqid(), 7)),
            'user_id'        => $this->request->getPost('user_id'),
            'barang_id'      => $this->request->getPost('barang_id'),
            'qty'            => $qty,
            'jenis'          => $this->request->getPost('jenis'),
            'status'         => 'dipinjam',
            'tgl'            => date('Y-m-d H:i:s'),
            'keperluan'      => $this->request->getPost('keperluan')
        ]);

        return redirect()->to('/transaksi')->with('msg', 'Transaksi berhasil ditambahkan!');
    }

    public function approve($id)
    {
        $trx = $this->tModel->find($id);

        if (!$trx) {
            return redirect()->back()->with('error', 'Data transaksi tidak ditemukan!');
        }

        $brg = $this->bModel->find($trx['barang_id']);

        if ($brg['stok'] < $trx['qty']) {
            return redirect()->back()->with('error', 'Stok tidak mencukupi!');
        }

        // Kurangi stok
        $this->bModel->update($brg['id'], [
            'stok' => $brg['stok'] - $trx['qty']
        ]);

        // Update status
        $statusBaru = ($trx['jenis'] == 'pinjam') ? 'dipinjam' : 'selesai';

        $this->tModel->update($id, [
            'status' => $statusBaru,
            'tgl'    => date('Y-m-d H:i:s')
        ]);

        return redirect()->to('/transaksi')->with('msg', 'Transaksi disetujui!');
    }

    public function reject($id)
    {
        $trx = $this->tModel->find($id);

        if (!$trx) {
            return redirect()->back()->with('error', 'Data transaksi tidak ditemukan!');
        }

        $this->tModel->update($id, [
            'status'   => 'ditolak',
            'catatan'  => $this->request->getGet('catatan') ?? 'Ditolak oleh admin'
        ]);

        return redirect()->to('/transaksi')->with('msg', 'Transaksi ditolak!');
    }

    public function kembali($id)
    {
        $trx = $this->tModel->find($id);

        if (!$trx) {
            return redirect()->back()->with('error', 'Data transaksi tidak ditemukan!');
        }

        $brg = $this->bModel->find($trx['barang_id']);

        // Kembalikan stok
        $this->bModel->update($brg['id'], [
            'stok' => $brg['stok'] + $trx['qty']
        ]);

        // Update status
        $this->tModel->update($id, [
            'status'      => 'dikembalikan',
            'tgl_kembali' => date('Y-m-d H:i:s')
        ]);

        return redirect()->to('/transaksi')->with('msg', 'Barang telah dikembalikan!');
    }

    public function delete($id)
    {
        $trx = $this->tModel->find($id);

        // Jika masih dipinjam, kembalikan stok dulu
        if ($trx && $trx['status'] === 'dipinjam') {
            $brg = $this->bModel->find($trx['barang_id']);
            $this->bModel->update($brg['id'], [
                'stok' => $brg['stok'] + $trx['qty']
            ]);
        }

        $this->tModel->delete($id);
        return redirect()->to('/transaksi')->with('msg', 'Transaksi dihapus!');
    }

    public function edit($id)
    {
        $trx = $this->tModel->find($id);
        if (!$trx) {
            return redirect()->to('/transaksi')->with('error', 'Transaksi tidak ditemukan!');
        }

        $data = [
            'title'       => 'Edit Transaksi',
            'row'         => $trx,
            'list_barang' => $this->bModel->findAll(),
            'list_user'   => $this->uModel->findAll()
        ];
        return view('transaksi/create', $data);
    }

    public function update($id)
    {
        $trx = $this->tModel->find($id);
        if (!$trx) {
            return redirect()->to('/transaksi')->with('error', 'Transaksi tidak ditemukan!');
        }

        $qtyBaru = (int)$this->request->getPost('qty');
        $barang = $this->bModel->find($this->request->getPost('barang_id'));

        // Hitung selisih qty
        $selisih = $qtyBaru - $trx['qty'];

        // Validasi stok jika qty ditambah
        if ($selisih > 0 && $barang['stok'] < $selisih) {
            return redirect()->back()->with('error', 'Stok tidak mencukupi!');
        }

        // Update stok barang
        $this->bModel->update($barang['id'], [
            'stok' => $barang['stok'] - $selisih
        ]);

        // Update transaksi
        $this->tModel->update($id, [
            'user_id'   => $this->request->getPost('user_id'),
            'barang_id' => $this->request->getPost('barang_id'),
            'qty'       => $qtyBaru,
            'jenis'     => $this->request->getPost('jenis'),
            'keperluan' => $this->request->getPost('keperluan')
        ]);

        return redirect()->to('/transaksi')->with('msg', 'Transaksi berhasil diupdate!');
    }
}


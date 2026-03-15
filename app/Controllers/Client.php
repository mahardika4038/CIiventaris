<?php

namespace App\Controllers;

use App\Models\BarangModel;
use App\Models\TransaksiModel;

class Client extends BaseController
{
    protected $bModel;
    protected $tModel;

    public function __construct()
    {
        $this->bModel = new BarangModel();
        $this->tModel = new TransaksiModel();
    }

    public function index()
    {
        // Pastikan session ID ada, jika tidak, arahkan ke login
        $userId = session()->get('user_id');
        if (!$userId) return redirect()->to('/login');

        $data = [
            'title'   => 'Sistem Mandiri Sarpras',
            // Gunakan leftJoin agar jika barang terhapus, riwayat tetap muncul
            'history' => $this->tModel->select('transaksi.*, barang.nama_barang')
                        ->join('barang', 'barang.id = transaksi.barang_id', 'left')
                        ->where('user_id', $userId)
                        ->orderBy('tgl', 'DESC')
                        ->findAll()
        ];
        return view('client/index', $data);
    }

    public function create()
    {
        $data = [
            'title'       => 'Form Pengajuan Barang',
            'list_barang' => $this->bModel->where('stok >', 0)->findAll()
        ];
        return view('client/create', $data);
    }

    public function createPinjam()
    {
        $data = [
            'title'       => 'Form Peminjaman Barang',
            'jenis'       => 'pinjam',
            'list_barang' => $this->bModel->where('stok >', 0)->findAll()
        ];
        return view('client/create', $data);
    }

    public function createMinta()
    {
        $data = [
            'title'       => 'Form Permintaan Barang (Permanen)',
            'jenis'       => 'minta',
            'list_barang' => $this->bModel->where('stok >', 0)->findAll()
        ];
        return view('client/create', $data);
    }

   public function store() 
    {
        $userId = session()->get('user_id');

        if (!$userId) {
            return redirect()->to('/login')->with('error', 'Sesi login tidak ditemukan.');
        }

        $dataInsert = [
            'kode_transaksi' => 'REQ-' . strtoupper(substr(uniqid(), 7)),
            'user_id'        => $userId,
            'barang_id'      => $this->request->getPost('barang_id'),
            'qty'            => (int)$this->request->getPost('qty'),
            'jenis'          => $this->request->getPost('jenis') ?? 'pinjam',
            'status'         => 'pending',
            'keperluan'      => $this->request->getPost('keperluan'),
            'tgl'            => date('Y-m-d H:i:s')
        ];

        // DEBUG: Aktifkan ini kalau lo mau liat isi datanya sebelum masuk DB
        // dd($dataInsert); 

        if ($this->tModel->save($dataInsert)) {
            return redirect()->to('/client')->with('msg', 'Berhasil dikirim!');
        } else {
            // Tampilkan error validasi model secara spesifik
            $errors = $this->tModel->errors();
            return redirect()->back()->withInput()->with('error', 'Gagal: ' . implode(', ', $errors));
        }
    }

    public function scan_check()
    {
        $kode = $this->request->getGet('kode');
        if (!$kode) return redirect()->to('/client');

        $barang = $this->bModel->where('kode_barang', $kode)->first();

        if (!$barang) {
            return redirect()->to('/client')->with('error', 'Aset tidak terdaftar di sistem!');
        }

        $userId = session()->get('user_id');
        
        // Cek apakah user sudah meminjam barang ini dan belum dikembalikan
        // Status 'dipinjam' = sedang dipinjam, belum dikembalikan
        $activePinjam = $this->tModel->where('user_id', $userId)
            ->where('barang_id', $barang['id'])
            ->where('status', 'dipinjam')
            ->where('jenis', 'pinjam')
            ->first();

        // Jika sudah ada transaksi pinjam aktif (dipinjam), tampilkan halaman konfirmasi pengembalian
        if ($activePinjam) {
            $data = [
                'title'  => 'Konfirmasi Pengembalian',
                'barang' => $barang,
                'trx'    => $activePinjam
            ];
            return view('client/halaman_kembali', $data);
        }

        // Jika belum pernah pinjam atau sudah dikembalikan, tampilkan halaman konfirmasi pinjam
        $data = [
            'title'  => 'Konfirmasi Aset',
            'barang' => $barang
        ];

        return view('client/halaman_pinjam', $data);
    }

    public function proses_pinjam()
    {
        $userId = session()->get('user_id');
        if (!$userId) return redirect()->to('/login');

        $barangId = $this->request->getPost('id_barang');
        $barang = $this->bModel->find($barangId);

        if (!$barang) {
            return redirect()->to('/client')->with('error', 'Barang tidak ditemukan!');
        }

        // Cek stok
        if ($barang['stok'] < 1) {
            return redirect()->to('/client')->with('error', 'Stok barang tidak tersedia!');
        }

        // Simpan transaksi pinjam
        $dataInsert = [
            'kode_transaksi' => 'PNJ-' . strtoupper(substr(uniqid(), 7)),
            'user_id'        => $userId,
            'barang_id'      => $barangId,
            'qty'            => 1,
            'jenis'          => 'pinjam',
            'status'         => 'dipinjam',
            'keperluan'      => 'Peminjaman via scan QR',
            'tgl'            => date('Y-m-d H:i:s')
        ];

        if ($this->tModel->save($dataInsert)) {
            // Kurangi stok barang
            $this->bModel->update($barangId, [
                'stok' => $barang['stok'] - 1
            ]);

            return redirect()->to('/client')->with('msg', 'Berhasil meminjam "' . $barang['nama_barang'] . '"!');
        }

        return redirect()->to('/client')->with('error', 'Gagal menyimpan transaksi!');
    }

    public function proses_aksi()
    {
        $userId = session()->get('user_id');
        if (!$userId) return redirect()->to('/login');

        $barangId = $this->request->getPost('barang_id');
        $action = $this->request->getPost('action');

        $barang = $this->bModel->find($barangId);
        if (!$barang) {
            return redirect()->to('/client')->with('error', 'Barang tidak ditemukan!');
        }

        // Proses Pengembalian
        if ($action === 'kembali') {
            // Cari transaksi aktif user untuk barang ini
            $activePinjam = $this->tModel->where('user_id', $userId)
                ->where('barang_id', $barangId)
                ->where('status', 'dipinjam')
                ->where('jenis', 'pinjam')
                ->first();

            if ($activePinjam) {
                // Update transaksi: tandai kembali
                $this->tModel->update($activePinjam['id'], [
                    'status'      => 'dikembalikan',
                    'tgl_kembali' => date('Y-m-d H:i:s')
                ]);

                // Update stok barang (tambah kembali)
                $this->bModel->update($barangId, [
                    'stok' => $barang['stok'] + 1
                ]);

                return redirect()->to('/client')->with('msg', 'Barang "' . $barang['nama_barang'] . '" berhasil dikembalikan!');
            }

            return redirect()->to('/client')->with('error', 'Transaksi pinjam tidak ditemukan!');
        }

        return redirect()->to('/client');
    }
}

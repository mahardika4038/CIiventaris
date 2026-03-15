<?php

namespace App\Controllers;

use App\Models\BarangModel;
use App\Models\TransaksiModel;
use App\Models\UserModel;

class Dashboard extends BaseController
{
    public function index()
    {
        // 1. Proteksi Role
        if (!in_array(session()->get('role'), ['superadmin', 'admin'])) {
            return redirect()->to('/client');
        }

        $db          = \Config\Database::connect();
        $barangModel = new BarangModel();
        $trxModel    = new TransaksiModel();
        $userModel   = new UserModel();

        // 2. Query Grafik Lokasi (Bar Chart)
        $lokasiChart = $db->table('barang b')
            ->select('l.nama_lokasi as label, COUNT(b.id) as total')
            ->join('lokasi l', 'l.id = b.lokasi_id', 'left')
            ->groupBy('l.nama_lokasi')
            ->get()->getResultArray();

        // 3. Query Grafik Kondisi (Pie Chart)
        $kondisiChart = $db->table('barang')
            ->select('kondisi as label, COUNT(id) as total')
            ->groupBy('kondisi')
            ->get()->getResultArray();

        // 4. Query Grafik Transaksi Bulanan (Line Chart)
        $tahunIni = date('Y');
        $grafikBulanan = [];
        for ($m = 1; $m <= 12; $m++) {
            $total = $trxModel->where('YEAR(created_at)', $tahunIni)
                              ->where('MONTH(created_at)', $m)
                              ->countAllResults();
            $grafikBulanan[] = (int)$total;
        }

        // 5. Data untuk View
        $data = [
            'role'           => session()->get('role'),
            'totalBarang'    => $barangModel->countAllResults(),
            'totalUser'      => $userModel->countAllResults(),
            'totalTransaksi' => $trxModel->countAllResults(),
            'pending'        => $trxModel->where('status', 'pending')->countAllResults(),
            'dipinjam'       => $trxModel->where('status', 'dipinjam')->countAllResults(),
            'selesai'        => $trxModel->whereIn('status', ['selesai', 'dikembalikan'])->countAllResults(),
            'stokHabis'      => $barangModel->where('stok <=', 0)->countAllResults(),
            
            // Data Grafik
            'lokasiChart'    => $lokasiChart, 
            'kondisiChart'   => $kondisiChart,
            'chartBulanan'   => $grafikBulanan,
            'chartStatus'    => [
                (int)$trxModel->where('status', 'pending')->countAllResults(),
                (int)$trxModel->where('status', 'dipinjam')->countAllResults(),
                (int)$trxModel->where('status', 'selesai')->countAllResults(),
                (int)$trxModel->where('status', 'ditolak')->countAllResults(),
            ]
        ];

        return view('dashboard/index', $data);
    }
}
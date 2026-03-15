<?php

namespace App\Models;

use CodeIgniter\Model;

class TransaksiModel extends Model
{
    protected $table      = 'transaksi';
    protected $primaryKey = 'id';

    // Tambahkan 'kode_transaksi' dan 'tgl_approve' ke dalam daftar ini
    protected $allowedFields = [
        'kode_transaksi',
        'user_id', 
        'barang_id', 
        'jenis', 
        'qty', 
        'keperluan', 
        'status', 
        'tgl',
        'tgl_kembali',
        'catatan'
    ];

    // Opsional: Aktifkan fitur otomatis waktu jika kolomnya ada di DB
    protected $useTimestamps = true;
}
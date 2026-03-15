<?php

namespace App\Models;

use CodeIgniter\Model;

class BarangModel extends Model
{
    protected $table      = 'barang';
    protected $primaryKey = 'id';

    protected $allowedFields = [
        'kode_barang',
        'nama_barang',
        'kategori',
        'lokasi_id',
        'kondisi',
        'stok',
        'status', // <--- WAJIB TAMBAHKAN INI
        'created_at',
        'updated_at',
    ];

    // Opsional: Tambahkan auto timestamps agar Admin tahu kapan data terakhir diubah
    protected $useTimestamps = true;
}
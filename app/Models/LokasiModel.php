<?php

namespace App\Models;

use CodeIgniter\Model;

class LokasiModel extends \CodeIgniter\Model
{
    protected $table = 'lokasi';
    protected $primaryKey = 'id';
    protected $allowedFields = ['nama_lokasi','created_at','updated_at'];
}

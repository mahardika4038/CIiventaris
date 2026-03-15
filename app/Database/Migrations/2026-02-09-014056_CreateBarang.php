<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBarang extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'kode_barang' => ['type' => 'VARCHAR', 'constraint' => 30, 'unique' => true],
            'nama_barang' => ['type' => 'VARCHAR', 'constraint' => 150],
            'kategori' => ['type' => 'VARCHAR', 'constraint' => 80, 'null' => true],
            'lokasi_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'null' => true],
            'kondisi' => ['type' => 'ENUM', 'constraint' => ['baik','rusak_ringan','rusak_berat'], 'default' => 'baik'],
            'stok' => ['type' => 'INT', 'constraint' => 11, 'default' => 0],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('lokasi_id', 'lokasi', 'id', 'SET NULL', 'CASCADE');
        $this->forge->createTable('barang', true);
    }

    public function down()
    {
        $this->forge->dropTable('barang', true);
    }
}

<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTransaksi extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'user_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'barang_id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true],
            'jenis' => ['type' => 'ENUM', 'constraint' => ['pinjam','minta']],
            'qty' => ['type' => 'INT', 'constraint' => 11, 'default' => 1],
            'status' => ['type' => 'ENUM', 'constraint' => ['pending','dipinjam','dikembalikan','ditolak','selesai'], 'default' => 'pending'],
            'tgl' => ['type' => 'DATETIME'],
            'tgl_kembali' => ['type' => 'DATETIME', 'null' => true],
            'catatan' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);

        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('user_id', 'users', 'id', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('barang_id', 'barang', 'id', 'CASCADE', 'CASCADE');
        $this->forge->createTable('transaksi', true);
    }

    public function down()
    {
        $this->forge->dropTable('transaksi', true);
    }
}

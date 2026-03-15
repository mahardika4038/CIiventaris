<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateLokasi extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id' => ['type' => 'INT', 'constraint' => 11, 'unsigned' => true, 'auto_increment' => true],
            'nama_lokasi' => ['type' => 'VARCHAR', 'constraint' => 120],
            'keterangan' => ['type' => 'VARCHAR', 'constraint' => 255, 'null' => true],
            'created_at' => ['type' => 'DATETIME', 'null' => true],
            'updated_at' => ['type' => 'DATETIME', 'null' => true],
        ]);
        $this->forge->addKey('id', true);
        $this->forge->createTable('lokasi', true);
    }

    public function down()
    {
        $this->forge->dropTable('lokasi', true);
    }
}

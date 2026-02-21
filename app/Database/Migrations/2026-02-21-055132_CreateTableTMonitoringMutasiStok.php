<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableTMonitoringMutasiStok extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'IDMONITORINGMUTASISTOK' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => false,
                'auto_increment' => true,
            ],
            'IDREGIONAL' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'IDBARANG' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'NAMABARANG' => [
                'type'       => 'VARCHAR',
                'constraint' => '150',
            ],
            'JENISMUTASI' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'STOKAWAL' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'STOKMUTASI' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'STOKDITAHAN' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'STOKTERSEDIA' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
            ],
            'TANGGALWAKTU' => [
                'type' => 'DATETIME',
            ],
        ]);

        $this->forge->addKey('IDMONITORINGMUTASISTOK', true);
        $this->forge->addKey([
            'IDREGIONAL',
            'IDBARANG'
        ], false, false, 'idx_regional_barang');

        $this->forge->createTable('t_monitoringmutasistok', true, [
            'ENGINE'  => 'InnoDB',
            'CHARSET' => 'utf8mb4',
            'COLLATE' => 'utf8mb4_unicode_ci',
        ]);
    }

    public function down()
    {
        $this->forge->dropTable('t_monitoringmutasistok');
    }
}

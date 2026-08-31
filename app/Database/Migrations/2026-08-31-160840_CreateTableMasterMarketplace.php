<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableMasterMarketplace extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'IDMARKETPLACE' => [
                'type'           => 'INT',
                'auto_increment' => true,
            ],
            'NAMAMARKETPLACE' => [
                'type'       => 'VARCHAR',
                'constraint' => 75,
                'default'    => '',
            ],
            'URUTAN' => [
                'type'       => 'TINYINT',
                'constraint' => 3,
                'default'    => 99,
            ],
            'STATUS' => [
                'type'       => 'TINYINT',
                'constraint' => 1,
                'default'    => -1,
            ],
        ]);

        $this->forge->addPrimaryKey('IDMARKETPLACE');
        $this->forge->addUniqueKey('NAMAMARKETPLACE', 'idx_unique_namamarketplace');
        $this->forge->createTable('m_marketplace', true);

        $this->db->query('ALTER TABLE `m_marketplace` AUTO_INCREMENT = 80');

        $this->db->table('m_marketplace')->insertBatch([
            ['NAMAMARKETPLACE' => 'Tiktok Shop', 'URUTAN' => 1, 'STATUS' =>  1],
            ['NAMAMARKETPLACE' => 'Tokopedia',   'URUTAN' => 2, 'STATUS' => -1],
            ['NAMAMARKETPLACE' => 'Shopee',      'URUTAN' => 3, 'STATUS' => -1],
        ]);
    }

    public function down()
    {
        $this->forge->dropTable('m_marketplace', true);
    }
}

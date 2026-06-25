<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddMasterPlatform extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'IDPLATFORM' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => false,
                'auto_increment' => true,
            ],
            'NAMAPLATFORM' => [
                'type'       => 'VARCHAR',
                'constraint' => '50',
            ],
            'STATUS' => [
                'type'    => 'BOOLEAN',
                'default' => true,
            ],
        ]);

        $this->forge->addKey('IDPLATFORM', true);
        $this->forge->createTable('m_platform', true, [
            'ENGINE'  => 'InnoDB',
        ]);

        $this->db->query("ALTER TABLE `m_platform` AUTO_INCREMENT = 100");
        $this->forge->addColumn('m_menuadmin', [
            'IDPLATFORM' => [
                'type'       => 'INT',
                'constraint' => 11,
                'default'    => 0,
                'after'      => 'IDMENUADMIN',
            ],
        ]);

        $this->db->table('m_menuadmin')
            ->set('GROUPNAME', 'Produk')
            ->whereIn('IDMENUADMIN', [4, 5, 6])
            ->update();
    }

    public function down()
    {
        $this->forge->dropTable('m_platform');
        $this->forge->dropColumn('m_menuadmin', 'IDPLATFORM');
        $this->db->table('m_menuadmin')
            ->set('GROUPNAME', 'Katalog Produk')
            ->where('IDMENUADMIN', 4)
            ->update();
        $this->db->table('m_menuadmin')
            ->set('GROUPNAME', 'Daftar Harga')
            ->where('IDMENUADMIN', 5)
            ->update();
        $this->db->table('m_menuadmin')
            ->set('GROUPNAME', 'Stok Barang')
            ->where('IDMENUADMIN', 6)
            ->update();
    }
}

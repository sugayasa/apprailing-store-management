<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateTableAPIProvider extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'IDAPIPROVIDER' =>  [
                'type'          =>  'INT',
                'auto_increment'=>  true,
            ],
            'TIPEPROVIDER'  =>  [
                'type'      =>  'VARCHAR',
                'constraint'=>  20,
            ],
            'NAMAPROVIDER'  =>  [
                'type'      =>  'VARCHAR',
                'constraint'=>  50,
            ],
        ]);
        $this->forge->addPrimaryKey('IDAPIPROVIDER');
        $this->forge->addUniqueKey(['TIPEPROVIDER', 'NAMAPROVIDER'], 'idx_unique_tipeprovider_namaprovider');
        $this->forge->createTable('a_apiprovider');
        $this->db->query("ALTER TABLE `a_apiprovider` AUTO_INCREMENT = 30");

        $this->db->table('a_apiprovider')->insert([
            'TIPEPROVIDER'  =>  'Ongkos Kirim',
            'NAMAPROVIDER'  =>  'api.co.id',
        ]);
    }

    public function down()
    {
        $this->forge->dropTable('a_apiprovider');
    }
}

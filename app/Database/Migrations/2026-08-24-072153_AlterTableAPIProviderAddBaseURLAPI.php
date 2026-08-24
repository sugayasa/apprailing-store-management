<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTableAPIProviderAddBaseURLAPI extends Migration
{
    public function up()
    {
        $this->forge->addColumn('a_apiprovider', [
            'BASEURLAPI'    =>  [
                'type'          =>  'VARCHAR',
                'constraint'    =>  100,
                'after'         =>  'NAMAPROVIDER'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('a_apiprovider', 'BASEURLAPI');
    }
}

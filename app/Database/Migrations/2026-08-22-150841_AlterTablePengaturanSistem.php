<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AlterTablePengaturanSistem extends Migration
{
    public function up()
    {
        $this->forge->renameTable('a_systemsettings', 'a_pengaturansistem');

        $this->forge->modifyColumn('a_pengaturansistem', [
            'IDSYSTEMSETTINGS'  =>  [
                'name'          =>  'IDPENGATURANSISTEM',
                'type'          =>  'INT',
                'auto_increment'=>  true
            ],
            'NAME'  =>  [
                'name'      =>  'NAMA',
                'type'      =>  'VARCHAR',
                'constraint'=>  50
            ],
            'DESCRIPTION'   =>  [
                'name'      =>  'DESKRIPSI',
                'type'      =>  'VARCHAR',
                'constraint'=>  255
            ],
            'DATASETTING'   =>  [
                'name'  =>  'DATA',
                'type'  =>  'LONGTEXT'
            ]
        ]);

        $this->forge->addColumn('a_pengaturansistem', [
            'URUTAN'    =>  [
                'type'      =>  'TINYINT',
                'default'   =>  99,
                'after'     =>  'DATA'
            ]
        ]);
    }

    public function down()
    {
        $this->forge->dropColumn('a_pengaturansistem', 'URUTAN');
        $this->forge->modifyColumn('a_pengaturansistem', [
            'IDPENGATURANSISTEM'=>  [
                'name'          =>  'IDSYSTEMSETTINGS',
                'type'          =>  'INT',
                'auto_increment'=>  true
            ],
            'NAMA'  =>  [
                'name'      =>  'NAME',
                'type'      =>  'VARCHAR',
                'constraint'=>  50
            ],
            'DESKRIPSI'   =>  [
                'name'      =>  'DESCRIPTION',
                'type'      =>  'VARCHAR',
                'constraint'=>  255
            ],
            'DATA'  =>  [
                'name'  =>  'DATASETTING',
                'type'  =>  'LONGTEXT'
            ]
        ]);

        $this->forge->renameTable('a_pengaturansistem', 'a_systemsettings');
    }
}

<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateMenuCustomerKontenUrutan extends Migration
{
    public function up()
    {
        $this->db->table('m_menuadmin')
            ->where('IDMENUADMIN >', 11)
            ->where('IDPLATFORM', 100)
            ->like('GROUPNAME', 'Konten')
            ->set('ORDERMENU', 'ORDERMENU + 1', false)
            ->orderBy('ORDERMENU', 'DESC')
            ->update();
    }

    public function down()
    {
        $this->db->table('m_menuadmin')
            ->where('IDMENUADMIN >', 11)
            ->where('IDPLATFORM', 100)
            ->like('GROUPNAME', 'Konten')
            ->set('ORDERMENU', 'ORDERMENU - 1', false)
            ->orderBy('ORDERMENU', 'ASC')
            ->update();
    }
}

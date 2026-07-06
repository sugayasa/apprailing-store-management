<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class UpdateMenuReelsToFeed extends Migration
{
    public function up()
    {
        $this->db->table('m_menuadmin')
            ->where('GROUPNAME', 'Konten')
            ->where('MENUNAME', 'Reels')
            ->update([
                'MENUNAME'    => 'Feed',
                'DESCRIPTION' => 'Daftar video feed yang ditampilkan di aplikasi customer',
                'MENUALIAS'   => 'CSKF',
                'URL'         => 'customer-konten-feed',
            ]);
    }

    public function down()
    {
        $this->db->table('m_menuadmin')
            ->where('GROUPNAME', 'Konten')
            ->where('MENUNAME', 'Feed')
            ->update([
                'MENUNAME'    => 'Reels',
                'DESCRIPTION' => 'Daftar video reels yang ditampilkan di aplikasi customer',
                'MENUALIAS'   => 'CSKR',
                'URL'         => 'customer-konten-reels',
            ]);
    }
}

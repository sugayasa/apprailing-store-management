<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MenuCustomerKontenBerita extends Seeder
{
    public function run()
    {
        $data = [
            'IDMENUADMIN'  => 16,
            'IDPLATFORM'   => 100,
            'GROUPNAME'    => 'Konten',
            'MENUNAME'     => 'Berita & Informasi',
            'DESCRIPTION'  => 'Daftar berita dan informasi dalam bentuk artikel yang ditampilkan di aplikasi customer',
            'MENUALIAS'    => 'CSKBI',
            'URL'          => 'customer-konten-berita-informasi',
            'ICON'         => 'fa-video-camera',
            'ORDERGROUP'   => 2,
            'ORDERMENU'    => 6,
            'SUPERADMIN'   => 0,
            'PERMISSION1'  => '',
            'PERMISSION2'  => '',
            'PERMISSION3'  => '',
        ];

        $this->db->table('m_menuadmin')->insert($data);

        $menuLevelData = [
            [
                'IDUSERADMINLEVEL' => 1,
                'IDMENUADMIN'      => 16,
                'ALLOWPERMISSION1' => 0,
                'ALLOWPERMISSION2' => 0,
                'ALLOWPERMISSION3' => 0,
            ]
        ];

        $this->db->table('m_menuleveladmin')->insertBatch($menuLevelData);
    }
}

<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MenuCustomerKontenSlideKolaborasi extends Seeder
{
    public function run()
    {
        $data = [
            'IDMENUADMIN'   =>  26,
            'IDPLATFORM'    =>  100,
            'GROUPNAME'     =>  'Konten',
            'MENUNAME'      =>  'Kolaborasi',
            'DESCRIPTION'   =>  'Daftar konten kolaborasi brand berisi konten berita dan video',
            'MENUALIAS'     =>  'CSKSK',
            'URL'           =>  'customer-konten-slide-kolaborasi',
            'ICON'          =>  'fa-video-camera',
            'ORDERGROUP'    =>  2,
            'ORDERMENU'     =>  8,
            'SUPERADMIN'    =>  0,
            'PERMISSION1'   =>  '',
            'PERMISSION2'   =>  '',
            'PERMISSION3'   =>  '',
        ];

        $this->db->table('m_menuadmin')->insert($data);

        $menuLevelData = [
            [
                'IDUSERADMINLEVEL' => 1,
                'IDMENUADMIN'      => 26,
                'ALLOWPERMISSION1' => 0,
                'ALLOWPERMISSION2' => 0,
                'ALLOWPERMISSION3' => 0,
            ]
        ];

        $this->db->table('m_menuleveladmin')->insertBatch($menuLevelData);
    }
}

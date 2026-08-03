<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MenuCustomerKontenGaleriKlien extends Seeder
{
    public function run()
    {
        $data = [
            'IDMENUADMIN'   => 24,
            'IDPLATFORM'    => 100,
            'GROUPNAME'     => 'Konten',
            'MENUNAME'      => 'Galeri Klien - Big Project',
            'DESCRIPTION'   => 'Daftar galeri proyek klien besar',
            'MENUALIAS'     => 'CSKGK',
            'URL'           => 'customer-konten-galeri-klien',
            'ICON'          => 'fa-video-camera',
            'ORDERGROUP'    => 2,
            'ORDERMENU'     => 2,
            'SUPERADMIN'    => 0,
            'PERMISSION1'   => '',
            'PERMISSION2'   => '',
            'PERMISSION3'   => '',
        ];

        $this->db->table('m_menuadmin')->insert($data);

        $menuLevelData = [
            [
                'IDUSERADMINLEVEL' => 1,
                'IDMENUADMIN'      => 24,
                'ALLOWPERMISSION1' => 0,
                'ALLOWPERMISSION2' => 0,
                'ALLOWPERMISSION3' => 0,
            ]
        ];

        $this->db->table('m_menuleveladmin')->insertBatch($menuLevelData);
    }
}

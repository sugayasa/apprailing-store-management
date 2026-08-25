<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MenuUtilitasCekOngkosKirim extends Seeder
{
    public function run()
    {
        $data = [
            'IDMENUADMIN'  => 25,
            'IDPLATFORM'   => 0,
            'GROUPNAME'    => 'Utilitas',
            'MENUNAME'     => 'Cek Ongkos Kirim',
            'DESCRIPTION'  => 'Cek ongkos kirim menggunakan provider pihak ketiga',
            'MENUALIAS'    => 'UCOK',
            'URL'          => 'utilitas-cek-ongkos-kirim',
            'ICON'         => 'fa-wrench',
            'ORDERGROUP'   => 98,
            'ORDERMENU'    => 1,
            'SUPERADMIN'   => 1,
            'PERMISSION1'  => '',
            'PERMISSION2'  => '',
            'PERMISSION3'  => '',
        ];

        $this->db->table('m_menuadmin')->insert($data);

        $menuLevelData = [
            [
                'IDUSERADMINLEVEL' => 1,
                'IDMENUADMIN'      => 25,
                'ALLOWPERMISSION1' => 0,
                'ALLOWPERMISSION2' => 0,
                'ALLOWPERMISSION3' => 0,
            ]
        ];

        $this->db->table('m_menuleveladmin')->insertBatch($menuLevelData);
    }
}

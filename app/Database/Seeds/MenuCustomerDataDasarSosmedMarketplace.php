<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MenuCustomerDataDasarSosmedMarketplace extends Seeder
{
    public function run()
    {
        $data = [
            'IDMENUADMIN'  => 17,
            'IDPLATFORM'   => 100,
            'GROUPNAME'    => 'Data Dasar',
            'MENUNAME'     => 'Akun Sosmed & Marketplace',
            'DESCRIPTION'  => 'Pengaturan data akun sosmed & marketplace',
            'MENUALIAS'    => 'CSDDASM',
            'URL'          => 'customer-data-dasar-sosmed-marketplace',
            'ICON'         => 'fa-database',
            'ORDERGROUP'   => 1,
            'ORDERMENU'    => 4,
            'SUPERADMIN'   => 0,
            'PERMISSION1'  => '',
            'PERMISSION2'  => '',
            'PERMISSION3'  => '',
        ];

        $this->db->table('m_menuadmin')->insert($data);

        $menuLevelData = [
            [
                'IDUSERADMINLEVEL' => 1,
                'IDMENUADMIN'      => 17,
                'ALLOWPERMISSION1' => 0,
                'ALLOWPERMISSION2' => 0,
                'ALLOWPERMISSION3' => 0,
            ]
        ];

        $this->db->table('m_menuleveladmin')->insertBatch($menuLevelData);
    }
}

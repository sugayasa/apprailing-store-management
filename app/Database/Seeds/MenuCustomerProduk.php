<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MenuCustomerProduk extends Seeder
{
    public function run()
    {
        $data = [
            'IDMENUADMIN'  => 18,
            'IDPLATFORM'   => 100,
            'GROUPNAME'    => 'Produk',
            'MENUNAME'     => 'Katalog Produk',
            'DESCRIPTION'  => 'Data produk di katalog platform customer',
            'MENUALIAS'    => 'CSKP',
            'URL'          => 'customer-produk-katalog',
            'ICON'         => 'fa-list-ul',
            'ORDERGROUP'   => 3,
            'ORDERMENU'    => 1,
            'SUPERADMIN'   => 0,
            'PERMISSION1'  => '',
            'PERMISSION2'  => '',
            'PERMISSION3'  => '',
        ];

        $this->db->table('m_menuadmin')->insert($data);

        $menuLevelData = [
            [
                'IDUSERADMINLEVEL' => 1,
                'IDMENUADMIN'      => 18,
                'ALLOWPERMISSION1' => 0,
                'ALLOWPERMISSION2' => 0,
                'ALLOWPERMISSION3' => 0,
            ]
        ];

        $this->db->table('m_menuleveladmin')->insertBatch($menuLevelData);
    }
}
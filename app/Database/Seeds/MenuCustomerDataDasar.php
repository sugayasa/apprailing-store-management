<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MenuCustomerDataDasar extends Seeder
{
    public function run()
    {
        $data = [
            [
                'IDMENUADMIN'  => 8,
                'IDPLATFORM'   => 100,
                'GROUPNAME'    => 'Data Dasar',
                'MENUNAME'     => 'Merk',
                'DESCRIPTION'  => 'Daftar merk yang ada di platform customer',
                'MENUALIAS'    => 'CSDDM',
                'URL'          => 'customer-data-dasar-merk',
                'ICON'         => 'fa-database',
                'ORDERGROUP'   => 1,
                'ORDERMENU'    => 1,
                'SUPERADMIN'   => 0,
                'PERMISSION1'  => '',
                'PERMISSION2'  => '',
                'PERMISSION3'  => '',
            ],
            [
                'IDMENUADMIN'  => 9,
                'IDPLATFORM'   => 100,
                'GROUPNAME'    => 'Data Dasar',
                'MENUNAME'     => 'Kategori Produk',
                'DESCRIPTION'  => 'Daftar kategori produk yang ditampilkan di platform customer',
                'MENUALIAS'    => 'CSDDKP',
                'URL'          => 'customer-data-dasar-kategori-produk',
                'ICON'         => 'fa-database',
                'ORDERGROUP'   => 1,
                'ORDERMENU'    => 2,
                'SUPERADMIN'   => 0,
                'PERMISSION1'  => '',
                'PERMISSION2'  => '',
                'PERMISSION3'  => '',
            ],
            [
                'IDMENUADMIN'  => 10,
                'IDPLATFORM'   => 100,
                'GROUPNAME'    => 'Data Dasar',
                'MENUNAME'     => 'Level Loyalti',
                'DESCRIPTION'  => 'Pengaturan detail level loyalty customer',
                'MENUALIAS'    => 'CSDDLL',
                'URL'          => 'customer-data-dasar-level-loyalti',
                'ICON'         => 'fa-database',
                'ORDERGROUP'   => 1,
                'ORDERMENU'    => 3,
                'SUPERADMIN'   => 0,
                'PERMISSION1'  => '',
                'PERMISSION2'  => '',
                'PERMISSION3'  => '',
            ],
        ];

        $this->db->table('m_menuadmin')->insertBatch($data);

        $menuLevelData = [
            [
                'IDUSERADMINLEVEL' => 1,
                'IDMENUADMIN'      => 8,
                'ALLOWPERMISSION1' => 0,
                'ALLOWPERMISSION2' => 0,
                'ALLOWPERMISSION3' => 0,
            ],
            [
                'IDUSERADMINLEVEL' => 1,
                'IDMENUADMIN'      => 9,
                'ALLOWPERMISSION1' => 0,
                'ALLOWPERMISSION2' => 0,
                'ALLOWPERMISSION3' => 0,
            ],
            [
                'IDUSERADMINLEVEL' => 1,
                'IDMENUADMIN'      => 10,
                'ALLOWPERMISSION1' => 0,
                'ALLOWPERMISSION2' => 0,
                'ALLOWPERMISSION3' => 0,
            ],
        ];

        $this->db->table('m_menuleveladmin')->insertBatch($menuLevelData);
    }
}

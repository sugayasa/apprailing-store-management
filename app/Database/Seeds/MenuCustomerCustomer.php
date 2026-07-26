<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MenuCustomerCustomer extends Seeder
{
    public function run()
    {
        $data = [
            [
                'IDMENUADMIN'  => 19,
                'IDPLATFORM'   => 100,
                'GROUPNAME'    => 'Customer',
                'MENUNAME'     => 'Statistik Customer',
                'DESCRIPTION'  => 'Data statistik customer yang terdaftar',
                'MENUALIAS'    => 'CCSC',
                'URL'          => 'customer-customer-statistik',
                'ICON'         => 'fa-users',
                'ORDERGROUP'   => 4,
                'ORDERMENU'    => 1,
                'SUPERADMIN'   => 0,
                'PERMISSION1'  => '',
                'PERMISSION2'  => '',
                'PERMISSION3'  => '',
            ],
            [
                'IDMENUADMIN'  => 20,
                'IDPLATFORM'   => 100,
                'GROUPNAME'    => 'Customer',
                'MENUNAME'     => 'Daftar Customer',
                'DESCRIPTION'  => 'Daftar customer yang ada di platform',
                'MENUALIAS'    => 'CSDC',
                'URL'          => 'customer-customer-daftar',
                'ICON'         => 'fa-users',
                'ORDERGROUP'   => 4,
                'ORDERMENU'    => 2,
                'SUPERADMIN'   => 0,
                'PERMISSION1'  => '',
                'PERMISSION2'  => '',
                'PERMISSION3'  => '',
            ],
            [
                'IDMENUADMIN'  => 21,
                'IDPLATFORM'   => 100,
                'GROUPNAME'    => 'Customer',
                'MENUNAME'     => 'Kritik & Saran',
                'DESCRIPTION'  => 'Data kritik & saran dari customer di aplikasi',
                'MENUALIAS'    => 'CSKS',
                'URL'          => 'customer-customer-kritik-saran',
                'ICON'         => 'fa-users',
                'ORDERGROUP'   => 4,
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
                'IDMENUADMIN'      => 19,
                'ALLOWPERMISSION1' => 0,
                'ALLOWPERMISSION2' => 0,
                'ALLOWPERMISSION3' => 0,
            ],
            [
                'IDUSERADMINLEVEL' => 1,
                'IDMENUADMIN'      => 20,
                'ALLOWPERMISSION1' => 0,
                'ALLOWPERMISSION2' => 0,
                'ALLOWPERMISSION3' => 0,
            ],
            [
                'IDUSERADMINLEVEL' => 1,
                'IDMENUADMIN'      => 21,
                'ALLOWPERMISSION1' => 0,
                'ALLOWPERMISSION2' => 0,
                'ALLOWPERMISSION3' => 0,
            ]
        ];

        $this->db->table('m_menuleveladmin')->insertBatch($menuLevelData);
    }
}

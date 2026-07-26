<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MenuCustomerTransaksi extends Seeder
{
    public function run()
    {
        $data = [
            [
                'IDMENUADMIN'  => 22,
                'IDPLATFORM'   => 100,
                'GROUPNAME'    => 'Transaksi',
                'MENUNAME'     => 'Statistik Transaksi',
                'DESCRIPTION'  => 'Data statistik transaksi yang terjadi',
                'MENUALIAS'    => 'CSST',
                'URL'          => 'customer-transaksi-statistik',
                'ICON'         => 'fa-file-text',
                'ORDERGROUP'   => 5,
                'ORDERMENU'    => 1,
                'SUPERADMIN'   => 0,
                'PERMISSION1'  => '',
                'PERMISSION2'  => '',
                'PERMISSION3'  => '',
            ],
            [
                'IDMENUADMIN'  => 23,
                'IDPLATFORM'   => 100,
                'GROUPNAME'    => 'Transaksi',
                'MENUNAME'     => 'Daftar Transaksi',
                'DESCRIPTION'  => 'Daftar transaksi yang terjadi di platform',
                'MENUALIAS'    => 'CSDT',
                'URL'          => 'customer-transaksi-daftar',
                'ICON'         => 'fa-file-text',
                'ORDERGROUP'   => 5,
                'ORDERMENU'    => 2,
                'SUPERADMIN'   => 0,
                'PERMISSION1'  => '',
                'PERMISSION2'  => '',
                'PERMISSION3'  => '',
            ]
        ];

        $this->db->table('m_menuadmin')->insertBatch($data);

        $menuLevelData = [
            [
                'IDUSERADMINLEVEL' => 1,
                'IDMENUADMIN'      => 22,
                'ALLOWPERMISSION1' => 0,
                'ALLOWPERMISSION2' => 0,
                'ALLOWPERMISSION3' => 0,
            ],
            [
                'IDUSERADMINLEVEL' => 1,
                'IDMENUADMIN'      => 23,
                'ALLOWPERMISSION1' => 0,
                'ALLOWPERMISSION2' => 0,
                'ALLOWPERMISSION3' => 0,
            ]
        ];

        $this->db->table('m_menuleveladmin')->insertBatch($menuLevelData);
    }
}

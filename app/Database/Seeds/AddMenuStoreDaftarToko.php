<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AddMenuStoreDaftarToko extends Seeder
{
    public function run()
    {
        $this->db->table('m_menuadmin')->where('IDPLATFORM', 100)->update(['IDPLATFORM' => 101]);
        $this->db->table('m_menuadmin')->whereIn('IDMENUADMIN', [4, 5, 6, 7])->update(['IDPLATFORM' => 100]);
        $this->db->table('m_menuadmin')->whereIn('IDMENUADMIN', [4, 5, 6])->update(['ORDERGROUP' => 2]);
        $this->db->table('m_menuadmin')->where('IDMENUADMIN', 7)->update(['ORDERGROUP' => 3]);

        $dataDaftarToko = [
            'IDMENUADMIN'  => 29,
            'IDPLATFORM'   => 100,
            'GROUPNAME'    => 'Daftar Toko',
            'MENUNAME'     => 'Daftar Toko',
            'DESCRIPTION'  => 'Daftar toko yang terdaftar di marketplace',
            'MENUALIAS'    => 'STDDDT',
            'URL'          => 'store-data-dasar-daftar-toko',
            'ICON'         => 'fa-database',
            'ORDERGROUP'   => 1,
            'ORDERMENU'    => 1,
            'SUPERADMIN'   => 0,
            'PERMISSION1'  => '',
            'PERMISSION2'  => '',
            'PERMISSION3'  => '',
        ];

        $this->db->table('m_menuadmin')->insert($dataDaftarToko);

        $this->db->table('m_menuleveladmin')->insert([
            'IDUSERADMINLEVEL' => 1,
            'IDMENUADMIN'      => 29,
            'ALLOWPERMISSION1' => 1,
            'ALLOWPERMISSION2' => 0,
            'ALLOWPERMISSION3' => 0,
        ]);
    }
}

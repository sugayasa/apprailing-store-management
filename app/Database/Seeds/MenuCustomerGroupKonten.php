<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MenuCustomerGroupKonten extends Seeder
{
    public function run()
    {
        $data = [
            [
                'IDMENUADMIN'  => 11,
                'IDPLATFORM'   => 100,
                'GROUPNAME'    => 'Konten',
                'MENUNAME'     => 'Pengenalan Aplikasi',
                'DESCRIPTION'  => 'Daftar konten pengenalan aplikasi dalam bentuk slide',
                'MENUALIAS'    => 'CSKPA',
                'URL'          => 'customer-konten-pengenalan-aplikasi',
                'ICON'         => 'fa-video-camera',
                'ORDERGROUP'   => 2,
                'ORDERMENU'    => 1,
                'SUPERADMIN'   => 0,
                'PERMISSION1'  => '',
                'PERMISSION2'  => '',
                'PERMISSION3'  => '',
            ],
            [
                'IDMENUADMIN'  => 12,
                'IDPLATFORM'   => 100,
                'GROUPNAME'    => 'Konten',
                'MENUNAME'     => 'Galeri Proyek',
                'DESCRIPTION'  => 'Daftar galeri proyek dan detail klien',
                'MENUALIAS'    => 'CSKGP',
                'URL'          => 'customer-konten-galeri-proyek',
                'ICON'         => 'fa-video-camera',
                'ORDERGROUP'   => 2,
                'ORDERMENU'    => 2,
                'SUPERADMIN'   => 0,
                'PERMISSION1'  => '',
                'PERMISSION2'  => '',
                'PERMISSION3'  => '',
            ],
            [
                'IDMENUADMIN'  => 13,
                'IDPLATFORM'   => 100,
                'GROUPNAME'    => 'Konten',
                'MENUNAME'     => 'Tutorial Pemasangan',
                'DESCRIPTION'  => 'Daftar konten tutorial pemasangan railing, aksesoris, dan lainnya',
                'MENUALIAS'    => 'CSKTP',
                'URL'          => 'customer-konten-tutorial-pemasangan',
                'ICON'         => 'fa-video-camera',
                'ORDERGROUP'   => 2,
                'ORDERMENU'    => 3,
                'SUPERADMIN'   => 0,
                'PERMISSION1'  => '',
                'PERMISSION2'  => '',
                'PERMISSION3'  => '',
            ],
            [
                'IDMENUADMIN'  => 14,
                'IDPLATFORM'   => 100,
                'GROUPNAME'    => 'Konten',
                'MENUNAME'     => 'Profil Perusahaan',
                'DESCRIPTION'  => 'Daftar konten profil perusahaan',
                'MENUALIAS'    => 'CSKPP',
                'URL'          => 'customer-konten-profil-perusahaan',
                'ICON'         => 'fa-video-camera',
                'ORDERGROUP'   => 2,
                'ORDERMENU'    => 4,
                'SUPERADMIN'   => 0,
                'PERMISSION1'  => '',
                'PERMISSION2'  => '',
                'PERMISSION3'  => '',
            ],
            [
                'IDMENUADMIN'  => 15,
                'IDPLATFORM'   => 100,
                'GROUPNAME'    => 'Konten',
                'MENUNAME'     => 'Reels',
                'DESCRIPTION'  => 'Daftar video reels yang ditampilkan di aplikasi customer',
                'MENUALIAS'    => 'CSKR',
                'URL'          => 'customer-konten-reels',
                'ICON'         => 'fa-video-camera',
                'ORDERGROUP'   => 2,
                'ORDERMENU'    => 5,
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
                'IDMENUADMIN'      => 11,
                'ALLOWPERMISSION1' => 0,
                'ALLOWPERMISSION2' => 0,
                'ALLOWPERMISSION3' => 0,
            ],
            [
                'IDUSERADMINLEVEL' => 1,
                'IDMENUADMIN'      => 12,
                'ALLOWPERMISSION1' => 0,
                'ALLOWPERMISSION2' => 0,
                'ALLOWPERMISSION3' => 0,
            ],
            [
                'IDUSERADMINLEVEL' => 1,
                'IDMENUADMIN'      => 13,
                'ALLOWPERMISSION1' => 0,
                'ALLOWPERMISSION2' => 0,
                'ALLOWPERMISSION3' => 0,
            ],
            [
                'IDUSERADMINLEVEL' => 1,
                'IDMENUADMIN'      => 14,
                'ALLOWPERMISSION1' => 0,
                'ALLOWPERMISSION2' => 0,
                'ALLOWPERMISSION3' => 0,
            ],
            [
                'IDUSERADMINLEVEL' => 1,
                'IDMENUADMIN'      => 15,
                'ALLOWPERMISSION1' => 0,
                'ALLOWPERMISSION2' => 0,
                'ALLOWPERMISSION3' => 0,
            ]
        ];

        $this->db->table('m_menuleveladmin')->insertBatch($menuLevelData);
    }
}

<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class AddCustomerDataMasterStatistikReviewMarketing extends Seeder
{
    public function run()
    {
        $data = [
            'IDMENUADMIN'  => 27,
            'IDPLATFORM'   => 100,
            'GROUPNAME'    => 'Data Dasar',
            'MENUNAME'     => 'Daftar Marketing',
            'DESCRIPTION'  => 'Data marketing yang terdaftar pada platform customer dan digunakan sebagai acuan review',
            'MENUALIAS'    => 'CSDDDM',
            'URL'          => 'customer-data-dasar-daftar-marketing',
            'ICON'         => 'fa-database',
            'ORDERGROUP'   => 1,
            'ORDERMENU'    => 5,
            'SUPERADMIN'   => 0,
            'PERMISSION1'  => '',
            'PERMISSION2'  => '',
            'PERMISSION3'  => '',
        ];

        $this->db->table('m_menuadmin')->insert($data);

        $this->db->table('m_menuadmin')->where('IDMENUADMIN', 21)->update(['ORDERMENU' => 4]);

        $dataReviewMarketing = [
            'IDMENUADMIN'  => 28,
            'IDPLATFORM'   => 100,
            'GROUPNAME'    => 'Customer',
            'MENUNAME'     => 'Review Marketing',
            'DESCRIPTION'  => 'Statistik dan data review pelayanan marketing yang diberikan oleh customer',
            'MENUALIAS'    => 'CSRM',
            'URL'          => 'customer-customer-review-marketing',
            'ICON'         => 'fa-users',
            'ORDERGROUP'   => 3,
            'ORDERMENU'    => 4,
            'SUPERADMIN'   => 0,
            'PERMISSION1'  => '',
            'PERMISSION2'  => '',
            'PERMISSION3'  => '',
        ];

        $this->db->table('m_menuadmin')->insert($dataReviewMarketing);

        $dataMenuLevelAdmin = [
            [
                'IDUSERADMINLEVEL' => 1,
                'IDMENUADMIN'      => 27,
                'ALLOWPERMISSION1' => 1,
                'ALLOWPERMISSION2' => 0,
                'ALLOWPERMISSION3' => 0,
            ],
            [
                'IDUSERADMINLEVEL' => 1,
                'IDMENUADMIN'      => 28,
                'ALLOWPERMISSION1' => 1,
                'ALLOWPERMISSION2' => 0,
                'ALLOWPERMISSION3' => 0,
            ],
        ];

        $this->db->table('m_menuleveladmin')->insertBatch($dataMenuLevelAdmin);
    }
}

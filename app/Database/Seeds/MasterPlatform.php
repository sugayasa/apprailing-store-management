<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class MasterPlatform extends Seeder
{
    public function run()
    {
        $data = [
            [
                'NAMAPLATFORM' => 'Store',
                'STATUS'       => true,
            ],
            [
                'NAMAPLATFORM' => 'Customer',
                'STATUS'       => true,
            ],
        ];

        $this->db->table('m_platform')->insertBatch($data);
    }
}

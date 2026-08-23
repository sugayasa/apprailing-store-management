<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class PengaturanSistemAPIOngkir extends Seeder
{
    public function run()
    {
        $data = [
            [
                'NAMA'      =>  'API Ongkir - Provider',
                'DESKRIPSI' =>  'Penyedia API untuk pengecekan ongkos kirim',
                'DATA'      =>  null,
                'URUTAN'    =>  1,
            ],
            [
                'NAMA'      =>  'API Ongkir - Key',
                'DESKRIPSI' =>  'Key API yang disediakan provider untuk pengambilan pengecekan data ongkos kirim',
                'DATA'      =>  null,
                'URUTAN'    =>  2,
            ],
        ];

        $this->db->table('a_pengaturansistem')->insertBatch($data);
    }
}

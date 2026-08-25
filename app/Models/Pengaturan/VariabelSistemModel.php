<?php

namespace App\Models\Pengaturan;

use CodeIgniter\Model;

class VariabelSistemModel extends Model
{
    protected $table            = 'a_pengaturansistem';
    protected $primaryKey       = 'IDPENGATURANSISTEM';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['IDPENGATURANSISTEM', 'NAMA', 'DESKRIPSI', 'DATA', 'URUTAN'];

    protected bool $allowEmptyInserts = false;
    protected bool $updateOnlyChanged = true;

    protected array $casts = [];
    protected array $castHandlers = [];

    // Dates
    protected $useTimestamps = false;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [];
    protected $validationMessages   = [];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];

    public function getDataPengaturanSistem($searchKeyword = null)
    {
        $this->select('IDPENGATURANSISTEM, NAMA, DESKRIPSI, DATA');
        $this->from('a_pengaturansistem', true);
        if(!is_null($searchKeyword) && $searchKeyword !== ''){
            $this->groupStart();
            $this->like('NAMA', $searchKeyword);
            $this->orLike('DESKRIPSI', $searchKeyword);
            $this->orLike('DATA', $searchKeyword);
            $this->groupEnd();
        }
        $this->orderBy('URUTAN, IDPENGATURANSISTEM');

        return $this->get()->getResultObject();
    }

    public function getDataPengaturanSistemAPIOngkirProvider()
    {
        $this->select('IDAPIPROVIDER, NAMAPROVIDER');
        $this->from('a_apiprovider', true);
        $this->where('TIPEPROVIDER', 'Ongkos Kirim');
        $this->orderBy('NAMAPROVIDER');

        return $this->get()->getResultObject();
    }
    
    public function getDataWilayahOngkir($keywordProvinsi = null, $keywordKotaKabupaten = null, $keyword = null)
    {	
        $db     =   db_connect('dbcustomer');
        $builder=   $db->table('m_wilayahkecamatan AS A', true);

        $builder->select(
            "C.NAMAPROVINSI, C.KODEAPIPROVINSI, B.NAMAKOTAKABUPATEN, B.KODEAPIKOTAKABUPATEN,
            A.NAMAKECAMATAN, A.KODEAPIKECAMATAN"
        );
        $builder->join('m_wilayahkotakabupaten AS B', 'A.IDWILAYAHKOTAKABUPATEN = B.IDWILAYAHKOTAKABUPATEN', 'LEFT');
        $builder->join('m_wilayahprovinsi AS C', 'B.IDWILAYAHPROVINSI = C.IDWILAYAHPROVINSI', 'LEFT');

        if ($keywordProvinsi) {
            $builder->groupStart();
            $builder->like('C.NAMAPROVINSI', $keywordProvinsi);
            $builder->orLike('C.KODEAPIPROVINSI', $keywordProvinsi);
            $builder->groupEnd();
        }

        if ($keywordKotaKabupaten) {
            $builder->groupStart();
            $builder->like('B.NAMAKOTAKABUPATEN', $keywordKotaKabupaten);
            $builder->orLike('B.KODEAPIKOTAKABUPATEN', $keywordKotaKabupaten);
            $builder->groupEnd();
        }

        if ($keyword) {
            $builder->groupStart();
            $builder->like('C.NAMAPROVINSI', $keyword);
            $builder->orLike('C.KODEAPIPROVINSI', $keyword);
            $builder->orLike('B.NAMAKOTAKABUPATEN', $keyword);
            $builder->orLike('B.KODEAPIKOTAKABUPATEN', $keyword);
            $builder->orLike('A.NAMAKECAMATAN', $keyword);
            $builder->orLike('A.KODEAPIKECAMATAN', $keyword);
            $builder->groupEnd();
        }

        $builder->orderBy('C.NAMAPROVINSI ASC, B.NAMAKOTAKABUPATEN ASC, A.NAMAKECAMATAN ASC');
               
        return $builder;
    }

    public function getIdWilayahProvinsiByName($namaProvinsi)
    {	
        $db     =   db_connect('dbcustomer');
        $builder=   $db->table('m_wilayahprovinsi');

        $builder->select('IDWILAYAHPROVINSI');
        $builder->where('NAMAPROVINSI', $namaProvinsi);
        $builder->limit(1);

        $result =   $builder->get()->getRowArray();

        if (empty($result)) return null;
        return $result['IDWILAYAHPROVINSI'];
    }

    public function getIdWilayahKotaKabupatenByName($idWilayahProvinsi, $namaKotaKabupaten)
    {	
        $db     =   db_connect('dbcustomer');
        $builder=   $db->table('m_wilayahkotakabupaten');

        $builder->select('IDWILAYAHKOTAKABUPATEN');
        $builder->where('IDWILAYAHPROVINSI', $idWilayahProvinsi);
        $builder->where('NAMAKOTAKABUPATEN', $namaKotaKabupaten);
        $builder->limit(1);

        $result =   $builder->get()->getRowArray();

        if (empty($result)) return null;
        return $result['IDWILAYAHKOTAKABUPATEN'];
    }
}

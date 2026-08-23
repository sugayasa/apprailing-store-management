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
}

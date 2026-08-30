<?php

namespace App\Models\Customer\DataDasar;

use CodeIgniter\Model;

class DaftarMarketingModel extends Model
{
    protected $DBGroup          = 'dbcustomer';
    protected $table            = 'm_marketing';
    protected $primaryKey       = 'IDMARKETING';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['IDMARKETING', 'IDREGIONAL', 'IDMARKETINGREGIONAL', 'NAMAMARKETING', 'JENISKELAMIN', 'REVIEWTOTAL', 'RATINGRERATA', 'IMAGE'];

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

    public function getDataMarketing()
    {
        $this->select(
            'A.IDMARKETING, B.NAMAREGIONAL, A.NAMAMARKETING, A.JENISKELAMIN, A.REVIEWTOTAL, A.RATINGRERATA, A.IMAGE'
        );
        $this->from('m_marketing AS A', true);
        $this->join('m_regional AS B', 'B.IDREGIONAL = A.IDREGIONAL', 'left');
        $this->orderBy('B.NAMAREGIONAL, A.NAMAMARKETING');

        $dataReturn   =   $this->get()->getResultObject();
        if(is_null($dataReturn) || !is_array($dataReturn) || empty($dataReturn)) return null;
        return $dataReturn;
    }
}

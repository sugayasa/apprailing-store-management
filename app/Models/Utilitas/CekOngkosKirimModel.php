<?php

namespace App\Models\Utilitas;

use CodeIgniter\Model;

class CekOngkosKirimModel extends Model
{
    protected $DBGroup          = 'dbcustomer';
    protected $table            = 'm_wilayahkecamatan';
    protected $primaryKey       = 'IDWILAYAHKECAMATAN';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [];

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

    public function getKodeAPIKecamatan($idKecamatan)
    {
        $this->select('KODEAPIKECAMATAN');
        $this->where('IDWILAYAHKECAMATAN', $idKecamatan);
        $this->limit(1);

        $result =   $this->get()->getRowArray();

        if (empty($result)) return null;
        return $result['KODEAPIKECAMATAN'];
    }
}
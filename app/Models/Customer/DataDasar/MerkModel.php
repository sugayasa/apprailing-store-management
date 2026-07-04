<?php

namespace App\Models\Customer\DataDasar;

use CodeIgniter\Model;

class MerkModel extends Model
{
    protected $DBGroup          = 'dbcustomer';
    protected $table            = 'm_merk';
    protected $primaryKey       = 'IDMERK';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['IDMERK', 'NAMAMERK', 'LOGO', 'STATUS'];

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
    
    public function getDataMerk()
    {	
        $this->select("IDMERK, NAMAMERK, LOGO, PDFTHUMBNAIL, PDFFILE, STATUSKATALOG, STATUS");
        $this->orderBy('NAMAMERK ASC');
               
        return $this;
	}
}

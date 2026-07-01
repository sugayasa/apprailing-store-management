<?php

namespace App\Models\Customer\DataDasar;

use CodeIgniter\Model;

class LevelLoyaltiModel extends Model
{
    protected $DBGroup          = 'dbcustomer';
    protected $table            = 'm_customerloyalti';
    protected $primaryKey       = 'IDCUSTOMERLOYALTI';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['IDCUSTOMERLOYALTI', 'LOYALTITIER', 'DESKRIPSI', 'MINIMALNOMINALPEMBELIAN', 'MINIMALPOIN', 'ICONFILE', 'CARDFILE', 'STATUS'];

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
    
    public function getDataLevelLoyalti()
    {	
        $this->select("IDCUSTOMERLOYALTI, LOYALTITIER, DESKRIPSI, MINIMALNOMINALPEMBELIAN, MINIMALPOIN, ICONFILE, CARDFILE, STATUS");
        $this->orderBy('MINIMALNOMINALPEMBELIAN ASC');
               
        return $this;
	}
}

<?php

namespace App\Models\Customer\DataDasar;

use CodeIgniter\Model;

class SosmedMarketplaceModel extends Model
{
    protected $DBGroup          = 'dbcustomer';
    protected $table            = 'm_tipesosmedmarketplace';
    protected $primaryKey       = 'IDTIPESOSMEDMARKETPLACE';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['IDTIPESOSMEDMARKETPLACE', 'NAMATIPE', 'FILEICON', 'URUTAN', 'STATUS'];

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
    
    public function getDataSosmedMarketplace()
    {	
        $this->select("IDTIPESOSMEDMARKETPLACE, NAMATIPE, FILEICON, STATUS, '' AS LISTAKUN");
        $this->orderBy('URUTAN ASC');
               
        return $this;
	}

    public function getDataSosmedMarketplaceByIdTipe($idTipeSosmedMarketplace)
    {	
        $builder = $this->db->table('t_sosmedmarketplace');
        $builder->select("IDSOSMEDMARKETPLACE, NAMAAKUN, URL, URUTAN");
        $builder->where('IDTIPESOSMEDMARKETPLACE', $idTipeSosmedMarketplace);
        $builder->orderBy('URUTAN ASC');
               
        $result =   $builder->get()->getResultObject();
        if(is_null($result)) return [];
        return $result;
    }
}

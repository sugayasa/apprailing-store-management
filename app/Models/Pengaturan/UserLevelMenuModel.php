<?php

namespace App\Models\Pengaturan;

use CodeIgniter\Model;

class UserLevelMenuModel extends Model
{
    protected $table            = 'm_useradminlevel';
    protected $primaryKey       = 'IDUSERADMINLEVEL';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['IDUSERADMINLEVEL', 'LEVELNAME', 'DESCRIPTION', 'ISSUPERADMIN'];

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

    public function getDataLevelUser()
    {
        $this->select("IDUSERADMINLEVEL, LEVELNAME, IF(DESCRIPTION IS NULL OR DESCRIPTION = '', '-', DESCRIPTION) AS DESCRIPTION, ISSUPERADMIN");
        $this->orderBy('ISSUPERADMIN', 'DESC');
        $this->orderBy('LEVELNAME', 'ASC');

        $result =   $this->get()->getResultObject();
        if(is_null($result)) return [];
        return $result;
    }

    public function getDetailMenuLevelUser($idUserLevel)
    {	
        $subQuery   =   $this->db->table('m_menuleveladmin B')
                        ->select('IDMENULEVELADMIN, IDMENUADMIN, ALLOWPERMISSION1, ALLOWPERMISSION2, ALLOWPERMISSION3')
                        ->where('IDUSERADMINLEVEL', $idUserLevel)
                        ->getCompiledSelect();
                        
         $builder   =   $this->db->table('m_menuadmin A')
                        ->select(
                            "A.IDMENUADMIN, IFNULL(C.IDMENULEVELADMIN, 0) AS IDMENULEVELADMIN, IFNULL(B.NAMAPLATFORM, 'Semua Platform') AS NAMAPLATFORM,
                            A.GROUPNAME, A.MENUNAME, A.DESCRIPTION, IF(C.IDMENULEVELADMIN IS NULL, 0, 1) AS ISMENUOPEN, A.PERMISSION1, A.PERMISSION2,
                            A.PERMISSION3, IFNULL(C.ALLOWPERMISSION1, 0) AS ALLOWPERMISSION1, IFNULL(C.ALLOWPERMISSION2, 0) AS ALLOWPERMISSION2,
                            IFNULL(C.ALLOWPERMISSION3, 0) AS ALLOWPERMISSION3"
                        )
                        ->join("m_platform B", 'A.IDPLATFORM = B.IDPLATFORM', 'LEFT')
                        ->join("($subQuery) C", 'A.IDMENUADMIN = C.IDMENUADMIN', 'LEFT')
                        ->orderBy("B.NAMAPLATFORM, A.ORDERGROUP, A.ORDERMENU, A.MENUNAME");
        $query      =   $builder->get();
        $result     =   $query->getResultObject();

        if(is_null($result)) return false;
        return $result;
	}
}

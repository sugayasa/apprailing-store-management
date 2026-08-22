<?php

namespace App\Models\Pengaturan;

use CodeIgniter\Model;

class DaftarPenggunaModel extends Model
{
    protected $table            = 'm_useradmin';
    protected $primaryKey       = 'IDUSERADMIN';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['IDUSERADMIN', 'IDUSERADMINLEVEL', 'NAME', 'EMAIL', 'USERNAME', 'PASSWORD', 'HARDWAREID', 'REDIRECTTOKEN', 'DATETIMELOGIN', 'DATETIMEACTIVITY', 'DATETIMEEXPIRED', 'STATUS', 'ISPERMANENTUSER'];

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
    
    public function getDataPengguna($keyword = null)
    {	
        $this->select(
            "A.IDUSERADMIN, A.IDUSERADMINLEVEL, B.LEVELNAME, A.NAME, A.USERNAME, A.EMAIL,
            IFNULL(DATE_FORMAT(DATETIMELOGIN, '%d %b %Y %H:%i'), '-') AS DATETIMELOGIN,
            IFNULL(DATE_FORMAT(DATETIMEACTIVITY, '%d %b %Y %H:%i'), '-') AS DATETIMEACTIVITY,
            STATUS"
        );
        $this->from('m_useradmin AS A', true);
        $this->join('m_useradminlevel AS B', 'A.IDUSERADMINLEVEL = B.IDUSERADMINLEVEL', 'LEFT');
        if ($keyword) {
            $this->groupStart();
            $this->like('A.NAME', $keyword);
            $this->orLike('A.EMAIL', $keyword);
            $this->orLike('A.USERNAME', $keyword);
            $this->orLike('B.LEVELNAME', $keyword);
            $this->groupEnd();
        }
        $this->orderBy('A.NAME ASC');
               
        return $this;
	}
}

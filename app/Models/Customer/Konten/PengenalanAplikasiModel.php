<?php

namespace App\Models\Customer\Konten;

use CodeIgniter\Model;

class PengenalanAplikasiModel extends Model
{
    protected $DBGroup          = 'dbcustomer';
    protected $table            = 't_slideboarding';
    protected $primaryKey       = 'IDSLIDEBOARDING';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['IDSLIDEBOARDING', 'KONTEN', 'IMAGE', 'URUTAN', 'INPUTUSER', 'INPUTTANGGALWAKTU', 'STATUS'];

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
    
    public function getDataSlideOnboarding()
    {	
        $this->select(
            "IDSLIDEBOARDING, KONTEN, IMAGE, URUTAN, INPUTUSER,
            DATE_FORMAT(INPUTTANGGALWAKTU, '%d %b %Y %H:%i') AS INPUTTANGGALWAKTU, STATUS"
        );
        $this->orderBy('STATUS DESC');
        $this->orderBy('URUTAN ASC');
               
        return $this;
	}
}

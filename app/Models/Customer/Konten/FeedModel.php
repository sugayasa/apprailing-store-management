<?php

namespace App\Models\Customer\Konten;

use CodeIgniter\Model;

class FeedModel extends Model
{
    protected $DBGroup          = 'dbcustomer';
    protected $table            = 't_feed';
    protected $primaryKey       = 'IDFEED';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['IDFEED', 'URLFEED', 'JUDUL', 'DESKRIPSI', 'TOTALSUKA', 'TOTALSIMPAN', 'INPUTUSER', 'INPUTTANGGALWAKTU'];

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
    
    public function getDataFeed($keyword = null)
    {	
        $this->select(
            "IDFEED, URLFEED, JUDUL, DESKRIPSI, TOTALSUKA, TOTALSIMPAN, INPUTUSER,
            DATE_FORMAT(INPUTTANGGALWAKTU, '%d-%m-%Y %H:%i') as INPUTTANGGALWAKTUSTR"
        );
        if ($keyword) {
            $this->groupStart();
            $this->like('JUDUL', $keyword);
            $this->orLike('URLFEED', $keyword);
            $this->orLike('DESKRIPSI', $keyword);
            $this->orLike('INPUTUSER', $keyword);
            $this->groupEnd();
        }
        $this->orderBy('INPUTTANGGALWAKTU DESC');
               
        return $this;
	}
}

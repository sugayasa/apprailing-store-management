<?php

namespace App\Models\Customer\Konten;

use CodeIgniter\Model;

class GaleriProyekModel extends Model
{
    protected $DBGroup          = 'dbcustomer';
    protected $table            = 't_galeriproyek';
    protected $primaryKey       = 'IDGALERIPROYEK';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['IDGALERIPROYEK', 'IDMERKUTAMA', 'NAMAKLIEN', 'ALAMATPROYEK', 'DESKRIPSI', 'URUTAN', 'IMAGE', 'INPUTUSER', 'INPUTTANGGALWAKTU', 'STATUS'];

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
    
    public function getDataGeleriProyek($idMerk = "")
    {	
        $this->select(
            "A.IDGALERIPROYEK, A.IDMERKUTAMA, B.NAMAMERK, A.NAMAKLIEN, A.ALAMATPROYEK, A.DESKRIPSI, A.URUTAN,
            A.IMAGE, A.INPUTUSER, DATE_FORMAT(A.INPUTTANGGALWAKTU, '%d %b %Y %H:%i') AS INPUTTANGGALWAKTU, A.STATUS"
        );
        $this->from('t_galeriproyek AS A', TRUE);
        $this->join('m_merk AS B', 'A.IDMERKUTAMA = B.IDMERK', 'LEFT');
        if($idMerk != "") $this->where('A.IDMERKUTAMA', $idMerk);
        $this->orderBy('A.STATUS DESC, A.INPUTTANGGALWAKTU DESC');
        $this->orderBy('A.URUTAN ASC');
               
        return $this;
	}
}

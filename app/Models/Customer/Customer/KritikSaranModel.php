<?php

namespace App\Models\Customer\Customer;

use CodeIgniter\Model;

class KritikSaranModel extends Model
{
    protected $DBGroup          = 'dbcustomer';
    protected $table            = 't_kritiksaran';
    protected $primaryKey       = 'IDKRITIKSARAN';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['IDKRITIKSARAN', 'IDCUSTOMER', 'SUBYEK', 'PESAN', 'INPUTTANGGALWAKTU'];

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
    
    public function getDataKritikSaran($tanggalAwal = null, $tanggalAkhir = null, $keyword = null)
    {	
        $this->select(
            "B.NAMA, B.EMAIL, B.NOMORHP, A.SUBYEK, A.PESAN, DATE_FORMAT(A.INPUTTANGGALWAKTU, '%d %M %Y %H:%i') AS INPUTTANGGALWAKTU"
        );
        $this->from('t_kritiksaran AS A', TRUE);
        $this->join('m_customer AS B', 'A.IDCUSTOMER = B.IDCUSTOMER', 'LEFT');
        if ($keyword) {
            $this->groupStart();
            $this->like('A.SUBYEK', $keyword);
            $this->orLike('A.PESAN', $keyword);
            $this->orLike('B.NAMA', $keyword);
            $this->orLike('B.EMAIL', $keyword);
            $this->orLike('B.NOMORHP', $keyword);
            $this->groupEnd();
        }
        if ($tanggalAwal && $tanggalAkhir) {
            $this->where('DATE(A.INPUTTANGGALWAKTU) >=', $tanggalAwal);
            $this->where('DATE(A.INPUTTANGGALWAKTU) <=', $tanggalAkhir);
        }
        $this->orderBy('A.INPUTTANGGALWAKTU DESC');
               
        return $this;
	}
    
    public function getStatistikKritikSaran()
    {	
        $this->select(
            "COUNT(IDKRITIKSARAN) AS TOTALKRITIKSARAN,
            COUNT(DISTINCT IDCUSTOMER) AS TOTALCUSTOMER,
            COUNT(
                CASE WHEN INPUTTANGGALWAKTU >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
                THEN IDKRITIKSARAN
                END
            ) AS TOTALKRITIKSARAN30HARI,
            COUNT(
                DISTINCT CASE WHEN INPUTTANGGALWAKTU >= DATE_SUB(CURDATE(), INTERVAL 30 DAY)
                THEN IDCUSTOMER
                END
            ) AS TOTALCUSTOMER30HARI"
        );
        $this->limit(1);

        $result =   $this->first();

        if(is_null($result)) return [
            "TOTALKRITIKSARAN"      =>  0,
            "TOTALCUSTOMER"         =>  0,
            "TOTALKRITIKSARAN30HARI"=>  0,
            "TOTALCUSTOMER30HARI"   =>  0,
        ];
        return $result;
	}
}

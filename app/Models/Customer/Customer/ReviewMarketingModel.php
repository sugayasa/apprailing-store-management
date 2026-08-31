<?php

namespace App\Models\Customer\Customer;

use CodeIgniter\Model;

class ReviewMarketingModel extends Model
{
    protected $DBGroup          = 'dbcustomer';
    protected $table            = 't_reviewmarketing';
    protected $primaryKey       = 'IDREVIEWMARKETING';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['IDMARKETING', 'IDCUSTOMER', 'RATING', 'KOMENTAR', 'TANGGAL', 'TANGGALWAKTU'];

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

    public function getDataGrafikReview($tanggalAwal, $tanggalAkhir)
    {
        $this->select(
            "COUNT(IDREVIEWMARKETING) AS TOTALREVIEW, COUNT(DISTINCT IDMARKETING) AS TOTALMARKETING,
            DATE_FORMAT(TANGGAL, '%d %b') AS TANGGALBULAN"
        );
        $this->where('TANGGAL >= ', $tanggalAwal);
        $this->where('TANGGAL <= ', $tanggalAkhir);
        $this->groupBy('TANGGAL');

        $result = $this->get()->getResultObject();
        if (is_null($result)) return [];
        return $result;
    }

    public function getDataRingkasanReview($tanggalAwal, $tanggalAkhir)
    {
        $this->select(
            "COUNT(IDREVIEWMARKETING) AS TOTALREVIEW,
            ROUND(AVG(RATING), 2) AS RATINGRERATA,
            COUNT(DISTINCT IDMARKETING) AS TOTALMARKETING"
        );
        $this->where('TANGGAL >= ', $tanggalAwal);
        $this->where('TANGGAL <= ', $tanggalAkhir);
        $this->limit(1);

        $result = $this->first();
        if (is_null($result)) return [
            'TOTALREVIEW'   =>  0,
            'RATINGRERATA'  =>  0,
            'TOTALMARKETING'=>  0
        ];
        return $result;
    }

    public function getDataPeringkatMarketing($tanggalAwal, $tanggalAkhir)
    {
        $this->select(
            "B.NAMAMARKETING, B.IMAGE, C.NAMAREGIONAL,
            ROUND(AVG(A.RATING), 2) AS RATINGRERATA,
            COUNT(A.IDREVIEWMARKETING) AS TOTALREVIEW"
        );
        $this->from('t_reviewmarketing AS A', true);
        $this->join('m_marketing AS B', 'A.IDMARKETING = B.IDMARKETING', 'left');
        $this->join('m_regional AS C', 'B.IDREGIONAL = C.IDREGIONAL', 'left');
        $this->where('A.TANGGAL >= ', $tanggalAwal);
        $this->where('A.TANGGAL <= ', $tanggalAkhir);
        $this->groupBy('A.IDMARKETING');
        $this->orderBy('RATINGRERATA', 'DESC');
        $this->orderBy('TOTALREVIEW', 'DESC');
        $this->limit(10);

        $result =   $this->get()->getResultObject();
        if (is_null($result)) return [];
        return $result;
    }

    public function getDataDaftarReview($tanggalAwal, $tanggalAkhir)
    {
        $this->select(
            "DATE_FORMAT(A.TANGGAL, '%d %M %Y') AS TANGGAL, B.NAMA AS NAMACUSTOMER, B.EMAIL AS EMAILCUSTOMER,
            B.NOMORHP AS NOMORHPCUSTOMER, C.NAMAMARKETING, D.NAMAREGIONAL, A.RATING, A.KOMENTAR"
        );
        $this->from('t_reviewmarketing AS A', true);
        $this->join('m_customer AS B', 'A.IDCUSTOMER = B.IDCUSTOMER', 'left');
        $this->join('m_marketing AS C', 'A.IDMARKETING = C.IDMARKETING', 'left');
        $this->join('m_regional AS D', 'C.IDREGIONAL = D.IDREGIONAL', 'left');
        $this->where('A.TANGGAL >= ', $tanggalAwal);
        $this->where('A.TANGGAL <= ', $tanggalAkhir);
        $this->orderBy('A.TANGGALWAKTU', 'DESC');

        return $this;
    }
}

<?php

namespace App\Models\Customer\Customer;

use CodeIgniter\Model;

class StatistikCustomerModel extends Model
{
    protected $DBGroup          = 'dbcustomer';
    protected $table            = 'stats_kunjungan';
    protected $primaryKey       = 'IDSTATSKUNJUNGAN';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['IDSTATSKUNJUNGAN', 'IDCUSTOMER', 'HARDWAREID', 'TANGGAL', 'WAKTUAWAL', 'WAKTUAKHIR', 'TOTALWAKTU', 'ISPROSESDAFTAR'];

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

    public function getDataKunjungan($tanggalAwal, $tanggalAkhir)
    {
        $this->select(
            "COUNT(IDSTATSKUNJUNGAN) AS TOTALKUNJUNGAN, COUNT(DISTINCT HARDWAREID) AS JUMLAHPERANGKAT,
            SUM(IF(IDCUSTOMER = 0, 1, 0)) AS JUMLAHTAMU, SUM(IF(ISPROSESDAFTAR = 1, 1, 0)) AS JUMLAHREGISTRASI,
            SUM(IF(IDCUSTOMER > 0, 1, 0)) AS JUMLAHCUSTOMERTERDAFTAR, DATE_FORMAT(TANGGAL, '%d %b') AS TANGGALBULAN"
        );
        $this->where('TANGGAL >= ', $tanggalAwal);
        $this->where('TANGGAL <= ', $tanggalAkhir);
        $this->groupBy('TANGGAL');

        $result =   $this->get()->getResultObject();
        if(is_null($result)) return [];
        return $result;
    }
}

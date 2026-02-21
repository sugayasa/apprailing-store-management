<?php

namespace App\Models;
use CodeIgniter\Model;

class MonitoringMutasiStokModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 't_monitoringmutasistok';
    protected $primaryKey       = 'IDMONITORINGMUTASISTOK';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['IDREGIONAL', 'IDBARANG', 'NAMABARANG', 'JENISMUTASI', 'STOKAWAL', 'STOKMUTASI', 'STOKDITAHAN', 'STOKAKHIR', 'TANGGALWAKTU'];

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
    
    public function getDataMonitoringMutasiStok($idRegional)
    {	
        $this->select("IDMONITORINGMUTASISTOK, IDBARANG, NAMABARANG, JENISMUTASI, STOKAWAL, STOKMUTASI, STOKDITAHAN, STOKTERSEDIA, DATE_FORMAT(TANGGALWAKTU, '%d %m %Y %H:%i') AS TANGGALWAKTUSTR, TANGGALWAKTU");
        $this->from('t_monitoringmutasistok', true);
        if(isset($idRegional) && $idRegional != '' && $idRegional != 0) $this->where('IDREGIONAL', $idRegional);
        $this->orderBy('TANGGALWAKTU', 'DESC');
        $this->limit(50);
        $result     =   $this->get()->getResultObject();

        if(is_null($result)) return [];
        return $result;
	}

    public function getDataStokBarang($namaDatabase, $idBarang, $tanggalWaktu)
    {
        $baseQuery = "SELECT SUM(IF(A.TANGGALWAKTU < " . $this->db->escape($tanggalWaktu) . ", IF(A.TIPETRANSAKSI = 1 OR A.TIPETRANSAKSI = 3, A.TOTALBARANG, A.TOTALBARANG * -1), 0)) AS SALDOAWAL,
                        SUM(IF(A.TIPETRANSAKSI = 1 OR A.TIPETRANSAKSI = 3, A.TOTALBARANG, A.TOTALBARANG * -1)) AS SALDOAKHIR, B.STOKBARANGREJECT, C.STOKBARANGDIPESAN
                    FROM " . $namaDatabase . ".t_slotbarang A
                    LEFT JOIN (
                        SELECT IDBARANG, SUM(JUMLAHBARANG) AS STOKBARANGREJECT
                        FROM " . $namaDatabase . ".t_slotbarangreject 
                        WHERE IDBARANG = " . $this->db->escape($idBarang) . "
                        GROUP BY IDBARANG
                    ) AS B ON A.IDBARANG = B.IDBARANG
                    LEFT JOIN (
                        SELECT CA.IDBARANG, SUM(CA.JUMLAHPCS) AS STOKBARANGDIPESAN
                        FROM " . $namaDatabase . ".t_salesorderdetail CA
                        LEFT JOIN " . $namaDatabase . ".t_suratjalan CB ON CA.IDREKAPSALESORDER = CB.IDSALESORDERREKAP
                        WHERE CA.IDBARANG = " . $this->db->escape($idBarang) . " AND CA.STATUS = 0 AND CB.IDSALESORDERREKAP IS NULL
                        GROUP BY CA.IDBARANG
                    ) AS C ON A.IDBARANG = C.IDBARANG
                    WHERE A.IDBARANG = " . $this->db->escape($idBarang) . " AND A.TANGGALWAKTU <= " . $this->db->escape($tanggalWaktu) . " AND A.TIPETRANSAKSI IN (3,4)
                    GROUP BY A.IDBARANG
                    LIMIT 1";

        $query = $this->db->query($baseQuery);
        $row = $query->getRowArray();

        if (isset($row)) return $row;
        return false;
    }
}
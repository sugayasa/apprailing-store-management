<?php

namespace App\Models;
use CodeIgniter\Model;

class CronModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'a_systemsettings';
    protected $primaryKey       = 'IDSYSTEMSETTINGS';
    protected $useAutoIncrement = true;
    protected $insertID         = 0;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = [];

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

    public function getDataDetailStokPerRegional($namaDatabase, $arrDatabaseRegional, $idKotaMutasi)
    {
        // Subquery B: t_slotbarang
        $subQueryB = $this->db->table($namaDatabase.'.t_slotbarang');
        $subQueryB->select('IDBARANG, IFNULL(SUM(IF(TIPETRANSAKSI = 1 OR TIPETRANSAKSI = 3, TOTALBARANG, TOTALBARANG * -1)),0) AS STOKFISIK');
        $subQueryB->whereIn('TIPETRANSAKSI', [3, 4]);
        $subQueryB->groupBy('IDBARANG');
        $compiledSubQueryB = $subQueryB->getCompiledSelect();

        // Subquery C: t_salesorderdetail with t_suratjalan
        $subQueryC = $this->db->table($namaDatabase.'.t_salesorderdetail CA');
        $subQueryC->select('CA.IDBARANG, SUM(CA.JUMLAHPCS) AS STOKSOTERTAHAN');
        $subQueryC->join($namaDatabase.'.t_suratjalan CB', 'CA.IDREKAPSALESORDER = CB.IDSALESORDERREKAP', 'LEFT');
        $subQueryC->where('CA.STATUS', 0);
        $subQueryC->where('CB.IDSALESORDERREKAP IS NULL');
        $subQueryC->groupBy('CA.IDBARANG');
        $compiledSubQueryC = $subQueryC->getCompiledSelect();

        // Subquery D: t_suratjalanitem with t_suratjalan
        $subQueryD = $this->db->table($namaDatabase.'.t_suratjalanitem DA');
        $subQueryD->select('DA.IDBARANG, SUM(DA.JUMLAH) AS STOKBELUMKIRIM');
        $subQueryD->join($namaDatabase.'.t_suratjalan DB', 'DA.IDSURATJALAN = DB.IDSURATJALAN', 'LEFT');
        $subQueryD->where('DB.STATUS', 0);
        $subQueryD->groupBy('DA.IDBARANG');
        $compiledSubQueryD = $subQueryD->getCompiledSelect();

        // Subquery E: t_slotbarangreject
        $subQueryE = $this->db->table($namaDatabase.'.t_slotbarangreject');
        $subQueryE->select('IDBARANG, IFNULL(SUM(JUMLAHBARANG), 0) AS STOKBARANGREJECT');
        $subQueryE->groupBy('IDBARANG');
        $compiledSubQueryE = $subQueryE->getCompiledSelect();

        // Subquery F: UNION of regional databases
        foreach($arrDatabaseRegional as $namaDatabaseRegional) {
            $subQueryF = $this->db->table($namaDatabaseRegional.'.t_permintaanmutasidetail FA');
            $subQueryF->select('FA.IDBARANG, IFNULL(SUM(IF(FB.STATUS = 0, FA.JUMLAHDIMINTA, FA.JUMLAHAPPROVE)), 0) AS STOKBARANGMUTASIREGIONAL');
            $subQueryF->join($namaDatabaseRegional.'.t_permintaanmutasirekap FB', 'FA.IDPERMINTAANMUTASIREKAP = FB.IDPERMINTAANMUTASIREKAP', 'LEFT');
            $subQueryF->where('FB.IDKOTAMUTASI', $idKotaMutasi);
            $subQueryF->whereIn('FB.STATUS', [0, 1]);
            $compiledSubQueryF[]  =   $subQueryF->getCompiledSelect();
        }

        $compiledSubQueryF  =   implode(" UNION ALL ", $compiledSubQueryF);

        // Main query
        $builder = $this->db->table(APP_MAIN_DATABASE_NAME.'.m_barang A');
        $builder->select("A.IDBARANG, IFNULL(B.STOKFISIK, 0) AS STOKFISIK, IFNULL(C.STOKSOTERTAHAN, 0) AS STOKSOTERTAHAN, 
                IFNULL(D.STOKBELUMKIRIM, 0) AS STOKBELUMKIRIM, IFNULL(E.STOKBARANGREJECT, 0) AS STOKBARANGREJECT, 
                IFNULL(SUM(F.STOKBARANGMUTASIREGIONAL), 0) AS STOKBARANGMUTASIREGIONAL");
        $builder->join("({$compiledSubQueryB}) AS B", 'A.IDBARANG = B.IDBARANG', 'LEFT');
        $builder->join("({$compiledSubQueryC}) AS C", 'A.IDBARANG = C.IDBARANG', 'LEFT');
        $builder->join("({$compiledSubQueryD}) AS D", 'A.IDBARANG = D.IDBARANG', 'LEFT');
        $builder->join("({$compiledSubQueryE}) AS E", 'A.IDBARANG = E.IDBARANG', 'LEFT');
        $builder->join("({$compiledSubQueryF}) AS F", 'A.IDBARANG = F.IDBARANG', 'LEFT');
        $builder->where('A.STATUS', 1);
        $builder->where('A.IDBARANG', 210);
        $builder->groupBy('A.IDBARANG');
        $builder->orderBy('LENGTH(A.KODEBARANG), A.KODEBARANG');

        $result = $builder->get()->getResultObject();

        if(is_null($result)) return [];
        return $result;
    }
}
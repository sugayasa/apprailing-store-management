<?php

namespace App\Models\Customer\Customer;

use CodeIgniter\Model;

class DaftarCustomerModel extends Model
{
    protected $DBGroup          = 'dbcustomer';
    protected $table            = 'm_customer';
    protected $primaryKey       = 'IDCUSTOMER';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['IDCUSTOMER', 'IDCUSTOMERLOYALTI', 'NAMA', 'TANGGALLAHIR', 'TANGGALDAFTAR', 'EMAIL', 'NOMORHP', 'KODEUNIK', 'AVATAR', 'ISDEVELOPER', 'STATUS'];

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
    
    public function getDataDaftarCustomer($keyword = null)
    {	
        $this->select(
            "A.IDCUSTOMER, A.AVATAR, A.NAMA, B.LOYALTITIER, DATE_FORMAT(A.TANGGALDAFTAR, '%d %M %Y') AS TANGGALDAFTAR,
            DATE_FORMAT(A.TANGGALLAHIR, '%d %M %Y') AS TANGGALLAHIR, IFNULL(A.EMAIL, '-') AS EMAIL, IFNULL(A.NOMORHP, '-') AS NOMORHP,
            A.KODEUNIK, A.STATUS, A.ISDEVELOPER"
        );
        $this->from('m_customer AS A', TRUE);
        $this->join('m_customerloyalti AS B', 'A.IDCUSTOMERLOYALTI = B.IDCUSTOMERLOYALTI', 'LEFT');
        if ($keyword) {
            $this->groupStart();
            $this->like('NAMA', $keyword);
            $this->orLike('EMAIL', $keyword);
            $this->orLike('NOMORHP', $keyword);
            $this->orLike('KODEUNIK', $keyword);
            $this->groupEnd();
        }
        $this->orderBy('TANGGALDAFTAR DESC');
        $this->orderBy('NAMA');
               
        return $this;
	}
    
    public function getDataDetailAlamat($idCustomer)
    {
        $this->select(
            "NAMAALAMAT, NAMAPENERIMA, NOMORHPPENERIMA, ALAMAT, KODEPOS, KELURAHAN, KECAMATAN, KOTA, PROPINSI, ISALAMATUTAMA, STATUS"
        );
        $this->from('m_customeralamat', TRUE);
        $this->where('IDCUSTOMER', $idCustomer);
        $this->orderBy('ISALAMATUTAMA DESC');
        $this->orderBy('NAMAALAMAT');
               
        return $this;
	}
    
    public function getDataDetailTransaksi($idCustomer)
    {
        $this->select(
            "A.IDTRANSAKSIREKAP, B.NAMAREGIONAL, A.NOMORTRANSAKSI, C.NAMAKANALPEMBAYARAN, E.STATUSTRANSAKSI, D.NAMAEKSPEDISI, 
            IFNULL(A.NOMORRESIEKSPEDISI, '-') AS NOMORRESIEKSPEDISI, A.ALAMATNAMA, A.ALAMATKIRIM, A.PENERIMANAMA,
            A.PENERIMANOMORTELEPON, A.CATATAN, A.TOTALBARANG, A.TOTALNOMINALBARANG, A.TOTALNOMINALONGKIR, A.TOTALNOMINALDISKON,
            A.TOTALNOMINALBAYAR, DATE_FORMAT(A.INPUTTANGGALWAKTU, '%d %M %Y %H:%i:%s') AS INPUTTANGGALWAKTUSTR"
        );
        $this->from('t_transaksirekap AS A', TRUE);
        $this->join('m_regional AS B', 'A.IDREGIONAL = B.IDREGIONAL', 'LEFT');
        $this->join('m_kanalpembayaran AS C', 'A.IDKANALPEMBAYARAN = C.IDKANALPEMBAYARAN', 'LEFT');
        $this->join('m_ekspedisi AS D', 'A.IDEKSPEDISI = D.IDEKSPEDISI', 'LEFT');
        $this->join('m_statustransaksi AS E', 'A.IDSTATUSTRANSAKSI = E.IDSTATUSTRANSAKSI', 'LEFT');

        $this->where('A.IDCUSTOMER', $idCustomer);
        $this->orderBy('A.INPUTTANGGALWAKTU DESC');
               
        return $this;
	}
    
    public function getDataDetailFeed($idCustomer)
    {
        $this->resetQuery();

        $subQuerySuka       =   $this->db->table('t_feedsuka')
            ->select('IDCUSTOMER, IDFEED, 1 AS ISSUKA, 0 AS ISBOOKMARK')
            ->where('IDCUSTOMER', $idCustomer)
            ->getCompiledSelect();

        $subQueryBookmark   =   $this->db->table('t_feedbookmark')
            ->select('IDCUSTOMER, IDFEED, 0 AS ISSUKA, 1 AS ISBOOKMARK')
            ->where('IDCUSTOMER', $idCustomer)
            ->getCompiledSelect();

        $unionQuery =   "{$subQuerySuka} UNION ALL {$subQueryBookmark}";

        $this->select("A.JUDUL, A.DESKRIPSI, A.URLFEED, SUM(B.ISSUKA) AS ISSUKA, SUM(B.ISBOOKMARK) AS ISBOOKMARK");
        $this->from('t_feed AS A', TRUE);
        $this->join("({$unionQuery}) AS B", 'A.IDFEED = B.IDFEED', 'LEFT');
        $this->groupBy(['A.JUDUL', 'A.DESKRIPSI', 'A.URLFEED']);
        $this->orderBy('A.INPUTTANGGALWAKTU DESC');
        $this->having('SUM(B.ISSUKA) + SUM(B.ISBOOKMARK) > 0');

        return $this;
	}
}

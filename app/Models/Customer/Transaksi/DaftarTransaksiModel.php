<?php

namespace App\Models\Customer\Transaksi;

use CodeIgniter\Model;

class DaftarTransaksiModel extends Model
{
    protected $DBGroup          = 'dbcustomer';
    protected $table            = 't_transaksirekap';
    protected $primaryKey       = 'IDTRANSAKSIREKAP';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['IDTRANSAKSIREKAP', 'IDREGIONAL', 'IDCUSTOMER', 'IDCUSTOMERALAMAT', 'IDKANALPEMBAYARAN', 'IDEKSPEDISI', 'IDSTATUSTRANSAKSI', 'NOMORTRANSAKSI', 'NOMORRESIEKSPEDISI', 'ALAMATNAMA', 'ALAMATKIRIM', 'PENERIMANAMA', 'PENERIMANOMORTELEPON', 'CATATAN', 'TOTALBARANG', 'TOTALNOMINALBARANG', 'TOTALNOMINALONGKIR', 'TOTALNOMINALDISKON', 'TOTALNOMINALBAYAR', 'ISPEMBAYARANLUNAS', 'ISPENGIRIMANDIPROSES', 'ISPESANANSELESAI', 'ISREFUNDDANA', 'INPUTTANGGALWAKTU', 'INPUTUSER', 'UPDATETANGGALWAKTU', 'UPDATEUSER'];

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

    public function getDataTransaksi($idRegional, $tanggalAwal, $tanggalAkhir, $searchKeyword)
    {
        $this->select(
            "A.IDTRANSAKSIREKAP, B.NAMA, B.EMAIL, B.NOMORHP, DATE_FORMAT(A.INPUTTANGGALWAKTU, '%d %M %Y %H:%i:%s') AS INPUTTANGGALWAKTUSTR,
            C.NAMAREGIONAL, A.NOMORTRANSAKSI, E.NAMAKANALPEMBAYARAN, D.STATUSTRANSAKSI, D.COLORCLASSBS, A.TOTALBARANG,
            F.NAMAEKSPEDISI, IFNULL(A.NOMORRESIEKSPEDISI, '-') AS NOMORRESIEKSPEDISI, A.ALAMATNAMA, A.PENERIMANAMA,
            A.PENERIMANOMORTELEPON, A.ALAMATKIRIM, A.CATATAN, A.TOTALNOMINALBARANG, A.TOTALNOMINALONGKIR, A.TOTALNOMINALDISKON,
            A.TOTALNOMINALBAYAR"
        );
        $this->from('t_transaksirekap AS A', true);
        $this->join('m_customer AS B', 'A.IDCUSTOMER = B.IDCUSTOMER', 'left');
        $this->join('m_regional AS C', 'A.IDREGIONAL = C.IDREGIONAL', 'left');
        $this->join('m_statustransaksi AS D', 'A.IDSTATUSTRANSAKSI = D.IDSTATUSTRANSAKSI', 'left');
        $this->join('m_kanalpembayaran AS E', 'A.IDKANALPEMBAYARAN = E.IDKANALPEMBAYARAN', 'left');
        $this->join('m_ekspedisi AS F', 'A.IDEKSPEDISI = F.IDEKSPEDISI', 'left');
        $this->where('A.INPUTTANGGALWAKTU >= ', $tanggalAwal);
        $this->where('A.INPUTTANGGALWAKTU <= ', $tanggalAkhir);

        if($idRegional != 0) $this->where('A.IDREGIONAL', $idRegional);
        if($searchKeyword != "") {
            $this->groupStart();
            $this->like('A.NOMORTRANSAKSI', $searchKeyword);
            $this->orLike('B.NAMA', $searchKeyword);
            $this->orLike('B.EMAIL', $searchKeyword);
            $this->orLike('B.NOMORHP', $searchKeyword);
            $this->orLike('F.NAMAEKSPEDISI', $searchKeyword);
            $this->orLike('A.NOMORRESIEKSPEDISI', $searchKeyword);
            $this->groupEnd();
        }
        
        $this->orderBy("A.INPUTTANGGALWAKTU", "DESC");   
        return $this;
    }
}

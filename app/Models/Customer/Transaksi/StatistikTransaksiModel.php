<?php

namespace App\Models\Customer\Transaksi;

use CodeIgniter\Model;

class StatistikTransaksiModel extends Model
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

    public function getDataMerk()
    {
        $this->select("IDMERK, NAMAMERK, LOGO");
        $this->from('m_merk', true);
        $this->where('STATUS', 1);
        $this->groupBy('IDMERK');
        $this->orderBy('NAMAMERK', 'ASC');

        $result =   $this->get()->getResultObject();
        if(is_null($result)) return [];
        return $result;
    }

    public function getDataTransaksi($tanggalAwal, $tanggalAkhir)
    {
        $this->select(
            "B.IDMERK, DATE_FORMAT(C.INPUTTANGGALWAKTU, '%d') AS TANGGAL,
            COUNT(DISTINCT A.IDTRANSAKSIREKAP) AS TOTALTRANSAKSI"
        );
        $this->from('t_transaksibarang AS A', true);
        $this->join('t_produk AS B', 'A.IDPRODUK = B.IDPRODUK', 'left');
        $this->join('t_transaksirekap AS C', 'A.IDTRANSAKSIREKAP = C.IDTRANSAKSIREKAP', 'left');
        $this->where('C.INPUTTANGGALWAKTU >= ', $tanggalAwal);
        $this->where('C.INPUTTANGGALWAKTU <= ', $tanggalAkhir);
        $this->groupBy("DATE_FORMAT(C.INPUTTANGGALWAKTU, '%d'), B.IDMERK");

        $result =   $this->get()->getResultObject();
        if(is_null($result)) return [];
        return $result;
    }

    public function getDataRekapPerMerk($tanggalAwal, $tanggalAkhir)
    {
        $this->select(
            "B.IDMERK, IFNULL(COUNT(DISTINCT A.IDTRANSAKSIREKAP), 0) AS TOTALTRANSAKSI,
            IFNULL(SUM(A.NOMINALTOTAL), 0) AS TOTALTRANSAKSINOMINAL"
        );
        $this->from('t_transaksibarang AS A', true);
        $this->join('t_produk AS B', 'A.IDPRODUK = B.IDPRODUK', 'left');
        $this->join('t_transaksirekap AS C', 'A.IDTRANSAKSIREKAP = C.IDTRANSAKSIREKAP', 'left');
        $this->where('C.INPUTTANGGALWAKTU >= ', $tanggalAwal);
        $this->where('C.INPUTTANGGALWAKTU <= ', $tanggalAkhir);
        $this->groupBy("B.IDMERK");

        $result =   $this->get()->getResultObject();
        if(is_null($result)) return [];
        return $result;
    }

    public function getDataRekapPerRegional($tanggalAwal, $tanggalAkhir)
    {
        $this->select(
            "IDREGIONAL, IFNULL(COUNT(DISTINCT IDTRANSAKSIREKAP), 0) AS TOTALTRANSAKSI,
            IFNULL(SUM(TOTALNOMINALBARANG - TOTALNOMINALDISKON), 0) AS TOTALNOMINAL"
        );
        $this->from('t_transaksirekap', true);
        $this->where('INPUTTANGGALWAKTU >= ', $tanggalAwal);
        $this->where('INPUTTANGGALWAKTU <= ', $tanggalAkhir);
        $this->groupBy("IDREGIONAL");

        $result =   $this->get()->getResultObject();
        if(is_null($result)) return [];
        return $result;
    }
    
    public function getDataProdukBestSeller($tanggalAwal, $tanggalAkhir)
    {	
        $this->select(
            "B.ARRIMAGE AS IMAGE, C.NAMAMERK, D.NAMAKATEGORI, B.NAMAPRODUK, SUM(A.NOMINALTOTAL) AS NOMINALTOTAL, SUM(A.JUMLAH) AS JUMLAHBARANG"
        );
        $this->from('t_transaksibarang AS A', true);
        $this->join('t_produk AS B', 'A.IDPRODUK = B.IDPRODUK', 'left');
        $this->join('m_merk AS C', 'B.IDMERK = C.IDMERK', 'left');
        $this->join('m_kategori AS D', 'B.IDKATEGORI = D.IDKATEGORI', 'left');
        $this->join('t_transaksirekap AS E', 'A.IDTRANSAKSIREKAP = E.IDTRANSAKSIREKAP', 'left');
        $this->where('E.INPUTTANGGALWAKTU >= ', $tanggalAwal);
        $this->where('E.INPUTTANGGALWAKTU <= ', $tanggalAkhir);
        $this->groupBy("B.IDPRODUK");
        $this->orderBy("NOMINALTOTAL", "DESC");
        $this->limit(10);

        $result =   $this->get()->getResultObject();
        if(is_null($result)) return [];
        return $result;
	}

    public function getDataRiwayatTransaksi($tanggalAwal, $tanggalAkhir)
    {
        $this->select(
            "B.NAMA, B.EMAIL, B.NOMORHP, D.STATUSTRANSAKSI, D.COLORCLASSBS, C.NAMAREGIONAL, A.NOMORTRANSAKSI,
            DATE_FORMAT(A.INPUTTANGGALWAKTU, '%d %b %Y %H:%i') AS INPUTTANGGALWAKTU, A.TOTALNOMINALBARANG,
            A.TOTALNOMINALONGKIR, A.TOTALNOMINALDISKON, A.TOTALNOMINALBAYAR"
        );
        $this->from('t_transaksirekap AS A', true);
        $this->join('m_customer AS B', 'A.IDCUSTOMER = B.IDCUSTOMER', 'left');
        $this->join('m_regional AS C', 'A.IDREGIONAL = C.IDREGIONAL', 'left');
        $this->join('m_statustransaksi AS D', 'A.IDSTATUSTRANSAKSI = D.IDSTATUSTRANSAKSI', 'left');
        $this->where('A.INPUTTANGGALWAKTU >= ', $tanggalAwal);
        $this->where('A.INPUTTANGGALWAKTU <= ', $tanggalAkhir);
        $this->orderBy("A.INPUTTANGGALWAKTU", "DESC");
        $this->limit(20);

        $result =   $this->get()->getResultObject();
        if(is_null($result)) return [];
        return $result;
    }
}

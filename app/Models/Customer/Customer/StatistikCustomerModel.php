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
            COUNT(DISTINCT CASE WHEN IDCUSTOMER = 0 THEN HARDWAREID END) AS JUMLAHTAMU,
            SUM(IF(ISPROSESDAFTAR = 1, 1, 0)) AS JUMLAHREGISTRASI, SUM(IF(IDCUSTOMER > 0, 1, 0)) AS JUMLAHCUSTOMERTERDAFTAR,
            DATE_FORMAT(TANGGAL, '%d %b') AS TANGGALBULAN"
        );
        $this->where('TANGGAL >= ', $tanggalAwal);
        $this->where('TANGGAL <= ', $tanggalAkhir);
        $this->groupBy('TANGGAL');

        $result =   $this->get()->getResultObject();
        if(is_null($result)) return [];
        return $result;
    }
    
    public function getDataKunjunganRekap($tanggalAwal, $tanggalAkhir)
    {
        $this->select(
            "COUNT(IDSTATSKUNJUNGAN) AS TOTALKUNJUNGAN, COUNT(DISTINCT HARDWAREID) AS JUMLAHPERANGKAT,
            COUNT(DISTINCT CASE WHEN IDCUSTOMER = 0 THEN HARDWAREID END) AS JUMLAHTAMU,
            SUM(IF(ISPROSESDAFTAR = 1, 1, 0)) AS JUMLAHREGISTRASI, SUM(IF(IDCUSTOMER > 0, 1, 0)) AS JUMLAHCUSTOMERTERDAFTAR"
        );
        $this->where('TANGGAL >= ', $tanggalAwal);
        $this->where('TANGGAL <= ', $tanggalAkhir);
        $this->limit(1);

        $result =   $this->first();
        if(is_null($result)) return [
            'TOTALKUNJUNGAN'            =>  0,
            'JUMLAHPERANGKAT'           =>  0,
            'JUMLAHTAMU'                =>  0,
            'JUMLAHREGISTRASI'          =>  0,
            'JUMLAHCUSTOMERTERDAFTAR'   =>  0
        ];
        return $result;
    }

    public function getDataStatistikBerita($tanggalAwal, $tanggalAkhir)
    {
        $this->select(
            "B.IMAGE, B.JUDUL, B.KONTEN, COUNT(A.IDSTATSKONTEN) AS JUMLAHDILIHAT"
        );
        $this->from('stats_konten AS A', true);
        $this->join('t_slidebanner AS B', 'A.IDPRIMARYKONTEN = B.IDSLIDEBANNER', 'left');
        $this->where('A.IDTIPEKONTEN', 900);
        $this->where('DATE(A.TANGGALWAKTU) >= ', $tanggalAwal);
        $this->where('DATE(A.TANGGALWAKTU) <= ', $tanggalAkhir);
        $this->groupBy('A.IDPRIMARYKONTEN');
        $this->orderBy('JUMLAHDILIHAT', 'DESC');

        $result =   $this->get()->getResultObject();
        if(is_null($result)) return [];
        return $result;
    }

    public function getDataStatistikGaleriKlien($tanggalAwal, $tanggalAkhir)
    {
        $this->select(
            "B.IMAGE, D.NAMAMERK, C.NAMAKLIEN, B.DESKRIPSI, COUNT(A.IDSTATSKONTEN) AS JUMLAHDILIHAT,
            COUNT(DISTINCT A.HARDWAREID) AS JUMLAHUSER"
        );
        $this->from('stats_konten AS A', true);
        $this->join('t_galeriklien AS B', 'A.IDPRIMARYKONTEN = B.IDGALERIKLIEN', 'left');
        $this->join('m_klien AS C', 'B.IDKLIEN = C.IDKLIEN', 'left');
        $this->join('m_merk AS D', 'B.IDMERKUTAMA = D.IDMERK', 'left');
        $this->where('A.IDTIPEKONTEN', 901);
        $this->where('DATE(A.TANGGALWAKTU) >= ', $tanggalAwal);
        $this->where('DATE(A.TANGGALWAKTU) <= ', $tanggalAkhir);
        $this->groupBy('A.IDPRIMARYKONTEN');
        $this->orderBy('JUMLAHDILIHAT', 'DESC');

        $result =   $this->get()->getResultObject();
        if(is_null($result)) return [];
        return $result;
    }

    public function getDataStatistikGaleriProyek($tanggalAwal, $tanggalAkhir)
    {
        $this->select(
            "B.IMAGE, C.NAMAMERK, B.NAMAKLIEN, B.ALAMATPROYEK, COUNT(A.IDSTATSKONTEN) AS JUMLAHDILIHAT,
            COUNT(DISTINCT A.HARDWAREID) AS JUMLAHUSER"
        );
        $this->from('stats_konten AS A', true);
        $this->join('t_galeriproyek AS B', 'A.IDPRIMARYKONTEN = B.IDGALERIPROYEK', 'left');
        $this->join('m_merk AS C', 'B.IDMERKUTAMA = C.IDMERK', 'left');
        $this->where('A.IDTIPEKONTEN', 902);
        $this->where('DATE(A.TANGGALWAKTU) >= ', $tanggalAwal);
        $this->where('DATE(A.TANGGALWAKTU) <= ', $tanggalAkhir);
        $this->groupBy('A.IDPRIMARYKONTEN');
        $this->orderBy('JUMLAHDILIHAT', 'DESC');

        $result =   $this->get()->getResultObject();
        if(is_null($result)) return [];
        return $result;
    }

    public function getDataStatistikFeed($tanggalAwal, $tanggalAkhir)
    {
        $this->select(
            "B.JUDUL, B.DESKRIPSI, B.URLFEED, COUNT(A.IDSTATSKONTEN) AS JUMLAHDILIHAT,
            COUNT(DISTINCT A.HARDWAREID) AS JUMLAHUSER"
        );
        $this->from('stats_konten AS A', true);
        $this->join('t_feed AS B', 'A.IDPRIMARYKONTEN = B.IDFEED', 'left');
        $this->where('A.IDTIPEKONTEN', 903);
        $this->where('DATE(A.TANGGALWAKTU) >= ', $tanggalAwal);
        $this->where('DATE(A.TANGGALWAKTU) <= ', $tanggalAkhir);
        $this->groupBy('A.IDPRIMARYKONTEN');
        $this->orderBy('JUMLAHDILIHAT', 'DESC');

        $result =   $this->get()->getResultObject();
        if(is_null($result)) return [];
        return $result;
    }
}

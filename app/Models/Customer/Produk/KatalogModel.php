<?php

namespace App\Models\Customer\Produk;

use CodeIgniter\Model;

class KatalogModel extends Model
{
    protected $DBGroup          = 'dbcustomer';
    protected $table            = 't_produk';
    protected $primaryKey       = 'IDPRODUK';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['IDPRODUK', 'IDBARANG', 'IDMERK', 'IDKATEGORI', 'NAMAPRODUK', 'DESKRIPSI', 'ARRIMAGE', 'HARGAJUAL', 'TOTALTERJUAL', 'INPUTUSER', 'INPUTTANGGALWAKTU', 'STATUS'];

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
    
    public function getDataProduk($merk, $kategori, $keywordCari, $urutBerdasar, $jenisUrutan)
    {	
        $this->select(
            "A.IDPRODUK, A.ARRIMAGE, B.NAMAMERK, IFNULL(C.NAMAKATEGORI, '-') AS NAMAKATEGORI, A.NAMAPRODUK,
            A.DESKRIPSI, A.HARGAJUAL, A.TOTALTERJUAL"
        );
        $this->from('t_produk AS A', true);
        $this->join('m_merk AS B', 'A.IDMERK = B.IDMERK', 'LEFT');
        $this->join('m_kategori AS C', 'A.IDKATEGORI = C.IDKATEGORI', 'LEFT');

        if(isset($merk) && $merk != '' && $merk != 0) $this->where('A.IDMERK', $merk);
        if(isset($kategori) && $kategori != '' && $kategori != 0) $this->where('A.IDKATEGORI', $kategori);
        if(isset($keywordCari) && !is_null($keywordCari)){
            $this->groupStart();
            $this->like('B.NAMAMERK', $keywordCari, 'both')
            ->orLike('C.NAMAKATEGORI', $keywordCari, 'both')
            ->orLike('A.NAMAPRODUK', $keywordCari, 'both')
            ->orLike('A.DESKRIPSI', $keywordCari, 'both');
            $this->groupEnd();
        }

        switch ($urutBerdasar) {
            case '1':
                $this->orderBy('B.NAMAMERK '.$jenisUrutan.', C.NAMAKATEGORI '.$jenisUrutan.', A.NAMAPRODUK '.$jenisUrutan);
                break;
            case '2':
                $this->orderBy('A.HARGAJUAL '.$jenisUrutan);
                break;
            case '3':
                $this->orderBy('A.TOTALTERJUAL '.$jenisUrutan);
                break;
            default:
                $this->orderBy('B.NAMAMERK '.$jenisUrutan.', C.NAMAKATEGORI '.$jenisUrutan.', A.NAMAPRODUK '.$jenisUrutan);
                break;
        }

        return $this;
	}
}

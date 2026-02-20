<?php

namespace App\Models;
use CodeIgniter\Model;

class KatalogProdukModel extends Model
{
    protected $DBGroup          = 'default';
    protected $table            = 'm_barang';
    protected $primaryKey       = 'IDBARANG';
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
    
    public function getDataBarang($merk, $kategori, $keywordCari, $urutBerdasar, $jenisUrutan)
    {	
        $this->select("A.IDBARANG, A.IMAGE1, B.NAMAMERK, IFNULL(C.KATEGORIBARANG, '-') AS KATEGORIBARANG, CONCAT(A.NAMABARANG, ' ', A.KODEBARANG) AS NAMAKODEBARANG,
                    IFNULL(D.HARGA, 0) AS HARGA, '[]' AS STOKBARANG");
        $this->from(APP_MAIN_DATABASE_NAME.'.m_barang A', true);
        $this->join(APP_MAIN_DATABASE_NAME.'.m_merk AS B', 'A.IDMERK = B.IDMERK', 'LEFT');
        $this->join(APP_MAIN_DATABASE_NAME.'.m_barangkategori AS C', 'A.IDKATEGORI = C.IDKATEGORIBARANG', 'LEFT');
        $this->join(APP_MAIN_DATABASE_NAME.'.t_hargaretail AS D', 'A.IDBARANG = D.IDBARANG AND D.IDKELOMPOKHARGA = 2 AND D.SATUAN = 1', 'LEFT');

        if(isset($merk) && $merk != '' && $merk != 0) $this->where('A.IDMERK', $merk);
        if(isset($kategori) && $kategori != '' && $kategori != 0) $this->where('A.IDKATEGORI', $kategori);
        if(isset($keywordCari) && !is_null($keywordCari)){
            $this->groupStart();
            $this->like('B.NAMAMERK', $keywordCari, 'both')
            ->orLike('C.KATEGORIBARANG', $keywordCari, 'both')
            ->orLike('A.NAMABARANG', $keywordCari, 'both')
            ->orLike('A.KODEBARANG', $keywordCari, 'both');
            $this->groupEnd();
        }

        switch ($urutBerdasar) {
            case '1':
                $this->orderBy('B.NAMAMERK '.$jenisUrutan.', C.KATEGORIBARANG '.$jenisUrutan.', A.KODEBARANG '.$jenisUrutan);
                break;
            case '2':
                $this->orderBy('D.HARGA '.$jenisUrutan);
                break;
            default:
                $this->orderBy('B.NAMAMERK '.$jenisUrutan.', C.KATEGORIBARANG '.$jenisUrutan.', A.KODEBARANG '.$jenisUrutan);
                break;
        }

        return $this;
	}
}
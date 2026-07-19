<?php

namespace App\Controllers\Customer\Produk;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Models\MainOperation;
use App\Models\Customer\Produk\KatalogModel;
use App\Libraries\StorageFactory;
use App\Libraries\CacheDB;

class Katalog extends ResourceController
{
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    use ResponseTrait;
    protected $userData, $currentDateTime;
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger) {
        parent::initController($request, $response, $logger);

        try {
            $this->userData         =   $request->userData;
            $this->currentDateTime  =   $request->currentDateTime;
        } catch (\Throwable $th) {
        }
    }

    public function index()
    {
        return $this->failForbidden('[E-AUTH-000] Forbidden Access');
    }

    public function getData()
    {
        $rules      =   [
            'merk'          =>  ['label' => 'Merk', 'rules' => 'permit_empty|alpha_numeric'],
            'kategori'      =>  ['label' => 'Kategori', 'rules' => 'permit_empty|alpha_numeric'],
            'keywordCari'   =>  ['label' => 'Keyword Cari', 'rules' => 'permit_empty|string'],
            'urutBerdasar'  =>  ['label' => 'Urutan Berdasar', 'rules' => 'required|in_list[1,2,3]'],
            'jenisUrutan'   =>  ['label' => 'Jenis Urutan', 'rules' => 'required|in_list[ASC,DESC]'],
        ];

        $messages   =   [
            'merk'  =>  [
                'alpha_numeric' =>  'Merk tidak valid'
            ],
            'kategori'  =>  [
                'alpha_numeric' =>  'Kategori tidak valid'
            ],
            'urutBerdasar'  =>  [
                'in_list' =>  'Urutan Berdasar tidak valid'
            ],
            'jenisUrutan'   =>  [
                'in_list' =>  'Jenis Urutan tidak valid'
            ]
        ];

        if(!$this->validate($rules, $messages)) return $this->fail($this->validator->getErrors());

        $mainOperation  =   new MainOperation();
        $katalogModel   =   new KatalogModel();

        $merk           =   $this->request->getVar('merk');
        $merk           =   $merk != "" ? hashidDecode($merk) : 0;
        $kategori       =   $this->request->getVar('kategori');
        $kategori       =   $kategori != "" ? hashidDecode($kategori) : 0;
        $keywordCari    =   $this->request->getVar('keywordCari');
        $urutBerdasar   =   $this->request->getVar('urutBerdasar');
        $jenisUrutan    =   $this->request->getVar('jenisUrutan');
        $pageNumber     =   $this->request->getVar('pageNumber') ? (int)$this->request->getVar('pageNumber') : 1;
        $dataPerPage    =   $this->request->getVar('dataPerPage') ? (int)$this->request->getVar('dataPerPage') : 8;
        $baseData       =   $katalogModel->getDataProduk($merk, $kategori, $keywordCari, $urutBerdasar, $jenisUrutan);
        $totalNumberData=   $baseData->countAllResults(false);
        $pageProperty   =   $mainOperation->generatePageProperty($pageNumber, $dataPerPage, $totalNumberData);

        if($totalNumberData > 0){
            $listData   =   $baseData->asObject()->findAll($dataPerPage, ($pageNumber - 1) * $dataPerPage);
            $listData   =   encodeDatabaseObjectResultKey($listData, ['IDPRODUK']);
            return $this->setResponseFormat('json')->respond([
                "listData"          =>  $listData,
                "pageProperty"      =>  $pageProperty
            ]);
        } else {
            $dataReturn =   [
                "listData"          =>  [],
                "pageProperty"      =>  $pageProperty
            ];
            return throwResponseNotFound('Tidak ada data yang ditemukan', $dataReturn);
        }
    }

    public function getDetail()
    {
        $rules      =   [
            'idProduk'  =>  ['label' => 'ID Produk', 'rules' => 'required|alpha_numeric']
        ];

        $messages   =   [
            'idProduk' =>  [
                'required'      =>  'Data kiriman tidak lengkap, silakan periksa kembali',
                'alpha_numeric' =>  'Data kiriman tidak valid, silakan periksa kembali'
            ]
        ];

        if(!$this->validate($rules, $messages)) return $this->fail($this->validator->getErrors());

        $katalogModel   =   new KatalogModel();
        $idProduk       =   hashidDecode($this->request->getVar('idProduk'));
        $detailData     =   $katalogModel->find($idProduk);
        
        if(!$detailData) return throwResponseNotFound('Detail produk tidak ditemukan');

        $cacheDB                =   new CacheDB();
        $cacheKey               =   $cacheDB->getCacheKeyName('dataBarangSistemUtama');
        $dataBarangSistemUtama  =   $cacheDB->get($cacheKey);

        if ($dataBarangSistemUtama === null) return throwResponseNotFound('Detail produk tidak ditemukan');

        $detailBarangSistemUtama   =   array_filter($dataBarangSistemUtama, function ($item) use ($detailData) {
            return $item['IDBARANG'] == $detailData['IDBARANG'];
        });

        $detailData['IDMERK']       =   $detailData['IDMERK'] != "" && $detailData['IDMERK'] != 0 ? hashidEncode($detailData['IDMERK']) : "";
        $detailData['IDKATEGORI']   =   $detailData['IDKATEGORI'] != "" && $detailData['IDKATEGORI'] != 0 ? hashidEncode($detailData['IDKATEGORI']) : "";
        $detailData['IDBARANG']     =   $detailData['IDBARANG'] != "" && $detailData['IDBARANG'] != 0 ? hashidEncode($detailData['IDBARANG']) : "";
        $detailData['NAMABARANG']   =   $detailBarangSistemUtama ? array_values($detailBarangSistemUtama)[0]['NAMAKODEBARANG'] : "";

        unset($detailData['IDPRODUK']);
        return $this->setResponseFormat('json')->respond([
            "baseURLImageProduk"=> BASE_URL_ASSETS_CUSTOMER_PRODUK,
            "dataDetail"        => $detailData
        ]);
    }
	
	public function uploadFotoProduk(){
		helper(['fileValidation']);
        if (empty($_FILES['file']['tmp_name'])) return throwResponseNotAcceptable("Tidak ada file yang diunggah");

        $fileValidation =   validate_image($_FILES["file"], 2000000);
        if($fileValidation !== true) return $fileValidation;

		$info	    =	getimagesize($_FILES["file"]["tmp_name"]);
		$width	    =	$info[0];
		$height	    =	$info[1];

		if ($width < 600 || $height < 300) {
            return throwResponseNotAcceptable("Ukuran gambar minimal 600 x 300 pixel.");
        }

        if ($width > 3600 || $height > 2400) {
            return throwResponseNotAcceptable("Ukuran gambar maksimal 3600 x 2400 pixel.");
        }
		
		$storage	=	StorageFactory::make();
		$dir		=	PATH_STORAGE_CUSTOMER_PRODUK;
		$extension	=	pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);
		$filename	=	"FotoProduk"."_".date('YmdHis').".".$extension;
		$move		=	$storage->upload($_FILES["file"]["tmp_name"], $dir.$filename);
		
		if($move){
            return $this->setResponseFormat('json')
			->respond([
				"status"    =>  200,
				"urlImage"  =>  BASE_URL_ASSETS_CUSTOMER_PRODUK.$filename,
				"fileName"  =>  $filename,
				"message"   =>  "Berkas berhasil diunggah"
			]);
		} else {
			return throwResponseInternalServerError("Gagal mengunggah berkas. Silakan coba lagi nanti");
		}
	}

    public function getDataProdukPadanan()
    {
        try {
            $cacheDB    =   new CacheDB();
            $cacheKey   =   $cacheDB->getCacheKeyName('dataBarangSistemUtama');
            $dataBarang =   $cacheDB->get($cacheKey);

            if ($dataBarang === null) {
                return throwResponseNotFound(
                    "[E-DATA-404] Data barang sistem utama tidak ditemukan. Silakan lakukan sinkronisasi data terlebih dahulu."
                );
            }

            $dataBarang =   encodeDatabaseArrayResultKey($dataBarang, ['IDBARANG']);
            $dataBarang =   array_map(function ($item) {
                return [
                    'IDBARANG'      => $item['IDBARANG'] ?? null,
                    'NAMAMERK'      => $item['NAMAMERK'] ?? null,
                    'NAMAKODEBARANG'=> $item['NAMAKODEBARANG'] ?? null,
                ];
            }, $dataBarang);

            return throwResponseOK(
                "[S-DATA-000] Data barang sistem utama berhasil diambil",
                [
                    'dataBarang'    =>  $dataBarang
                ]
            );
        } catch (\Throwable $e) {
            return throwResponseError(
                500,
                '[E-DATA-999] Unexpected Error: ' . $e->getMessage()
            );
        }
    }

    public function saveData()
    {
        $idProduk   =   $this->request->getVar('idProduk');
        $idProduk   =   $idProduk != "" ? hashidDecode($idProduk) : 0;
        $validation =   $idProduk == 0 ? $this->parametersValidator() : $this->parametersValidator(true, $idProduk);
        
        if($validation !== true) return $this->fail($validation);
        
        $mainOperation          =   new MainOperation();
        $arrFotoProdukFileName  =   $this->request->getVar('arrFotoProdukFileName');
        $namaProduk             =   $this->request->getVar('namaProduk');
        $idMerk                 =   $this->request->getVar('optionMerk');
        $idMerk                 =   $idMerk != "" ? hashidDecode($idMerk) : 0;
        $idKategori             =   $this->request->getVar('optionKategori');
        $idKategori             =   $idKategori != "" ? hashidDecode($idKategori) : 0;
        $idProdukPadanan        =   $this->request->getVar('idProdukPadanan');
        $idProdukPadanan        =   $idProdukPadanan != "" ? hashidDecode($idProdukPadanan) : 0;
        $hargaJual              =   $this->request->getVar('hargaJual');
        $status                 =   $this->request->getVar('status');
        $deskripsi              =   $this->request->getVar('deskripsi');
        $arrInsertUpdate        =   [
            'IDBARANG'          =>  $idProdukPadanan,
            'IDMERK'            =>  $idMerk,
            'IDKATEGORI'        =>  $idKategori,
            'NAMAPRODUK'        =>  $namaProduk,
            'DESKRIPSI'         =>  $deskripsi,
            'ARRIMAGE'          =>  json_encode($arrFotoProdukFileName),
            'HARGAJUAL'         =>  $hargaJual,
            'INPUTUSER'         =>  $this->userData->name,
            'INPUTTANGGALWAKTU' =>  $this->currentDateTime,
            'STATUS'            =>  $status
        ];

        if($idProduk == 0){
            $procInsertData =   $mainOperation->insertDataTable(APP_MAIN_DATABASE_CUSTOMER . '.t_produk', $arrInsertUpdate);
            if(!$procInsertData['status']) return switchMySQLErrorCode($procInsertData['errCode']);
        } else {
            $procUpdateData =   $mainOperation->updateDataTable(APP_MAIN_DATABASE_CUSTOMER . '.t_produk', $arrInsertUpdate, ['IDPRODUK' => $idProduk]);
            if(!$procUpdateData['status']) return switchMySQLErrorCode($procUpdateData['errCode']);
        }
                    
        $responseSuccess =   $idProduk == 0 ? 'Data produk telah disimpan' : 'Data produk telah diperbarui';
        return throwResponseOK($responseSuccess);
    }

    private function parametersValidator($isUpdate = false, $idProduk = null)
    {
        $rules      =   [
            'arrFotoProdukFileName.*'   =>  ['label' => 'Foto Produk', 'rules' => 'required|alpha_numeric_punct'],
            'namaProduk'                =>  ['label' => 'Nama Produk', 'rules' => 'required|string|min_length[3]|max_length[150]'],
            'optionMerk'                =>  ['label' => 'Merk', 'rules' => 'required|alpha_numeric'],
            'optionKategori'            =>  ['label' => 'Kategori', 'rules' => 'required|alpha_numeric'],
            'idProdukPadanan'           =>  ['label' => 'Produk Padanan', 'rules' => 'required|alpha_numeric'],
            'hargaJual'                 =>  ['label' => 'Harga Jual', 'rules' => 'required|numeric|greater_than_equal_to[0]'],
            'deskripsi'                 =>  ['label' => 'Deskripsi', 'rules' => 'required|min_length[20]'],
            'status'                    =>  ['label' => 'Status', 'rules' => 'required|in_list[-1,1]']
        ];

        $messages   =   [
            'arrFotoProdukFileName.*'  =>  [
                'required'              =>  'Foto produk harus diunggah minimal 1 foto',
                'alpha_numeric_punct'   =>  'File foto produk yang diunggah tidak valid'
            ],
            'optionMerk'    =>  [
                'required'      =>  'Merk produk harus dipilih',
                'alpha_numeric' =>  'Merk produk yang dipilih tidak valid'
            ],
            'optionKategori'    =>  [
                'required'      =>  'Kategori produk harus dipilih',
                'alpha_numeric' =>  'Kategori produk yang dipilih tidak valid'
            ],
            'idProdukPadanan'   =>  [
                'required'      =>  'Produk padanan harus dipilih',
                'alpha_numeric' =>  'Produk padanan yang dipilih tidak valid'
            ],
            'status'        =>  [
                'required'  =>  'Status produk harus dipilih',
                'in_list'   =>  'Status produk yang dipilih tidak valid'
            ]
        ];

        if($isUpdate) {
            $rules['namaProduk']['rules']           .=  '|is_unique['.APP_MAIN_DATABASE_CUSTOMER_CI_VALIDATION . '.t_produk.NAMAPRODUK, IDPRODUK, '.$idProduk.']';
            $rules['idProduk']['rules']             =   'required|alpha_numeric';
            $messages['idProduk']['required']       =   'Data kiriman tidak lengkap, silakan periksa kembali';
            $messages['idProduk']['alpha_numeric']  =   'Data kiriman tidak lengkap, silakan periksa kembali';
        } else {
            $rules['namaProduk']['rules']           .=  '|is_unique['.APP_MAIN_DATABASE_CUSTOMER_CI_VALIDATION . '.t_produk.NAMAPRODUK]';
        }

        if(!$this->validate($rules, $messages)) return $this->validator->getErrors();
        return true;
    }
}

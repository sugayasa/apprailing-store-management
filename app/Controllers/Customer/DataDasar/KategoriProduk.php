<?php

namespace App\Controllers\Customer\DataDasar;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Models\MainOperation;
use App\Models\Customer\DataDasar\KategoriProdukModel;

class KategoriProduk extends ResourceController
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
        $mainOperation      =   new MainOperation();
        $kategoriProdukModel=   new KategoriProdukModel();

         $rules     =   [
            'searchKeyword' =>  ['label' => 'Nama Merk', 'rules' => 'permit_empty|alpha_numeric_punct']
        ];

        $messages   =   [];

        if(!$this->validate(array_merge($rules, APP_PAGE_PROPERTY_DEFAULT_RULES), array_merge($messages, APP_PAGE_PROPERTY_DEFAULT_MESSAGES))) return $this->fail($this->validator->getErrors());

        $pageNumber     =   $this->request->getVar('pageNumber') ? (int)$this->request->getVar('pageNumber') : 1;
        $dataPerPage    =   $this->request->getVar('dataPerPage') ? (int)$this->request->getVar('dataPerPage') : 20;
        $searchKeyword  =   $this->request->getVar('searchKeyword');
        $baseData       =   $kategoriProdukModel->getDataKategoriProduk($searchKeyword);
        $totalNumberData=   $baseData->countAllResults(false);
        $pageProperty   =   $mainOperation->generatePageProperty($pageNumber, $dataPerPage, $totalNumberData);

        if($totalNumberData > 0){
            $listData   =   $baseData->asObject()->findAll($dataPerPage, ($pageNumber - 1) * $dataPerPage);
            $listData   =   encodeDatabaseObjectResultKey($listData, ['IDKATEGORI']);

            return $this->setResponseFormat('json')->respond([
                "listData"      =>  $listData,
                "pageProperty"  =>  $pageProperty
            ]);
        } else {
            $dataReturn =   [
                "listData"      =>  [],
                "pageProperty"  =>  $pageProperty
            ];
            return throwResponseNotFound('Tidak ada data yang ditemukan', $dataReturn);
        }
    }

    public function saveData()
    {
        $idKategoriProduk   =   $this->request->getVar('idKategoriProduk');
        $idKategoriProduk   =   $idKategoriProduk != "" ? hashidDecode($idKategoriProduk) : 0;
        $validation         =   $idKategoriProduk == 0 ? $this->parametersValidator() : $this->parametersValidator(true, $idKategoriProduk);

        if($validation !== true) return $this->fail($validation);
        
        $mainOperation  =   new MainOperation();
        $namaKategori   =   $this->request->getVar('kategoriProduk');
        $deskripsi      =   $this->request->getVar('deskripsi');
        $status         =   $this->request->getVar('status');
        $arrInsertUpdate=   [
            'NAMAKATEGORI'  =>  $namaKategori,
            'DESKRIPSI'     =>  $deskripsi,
            'STATUS'        =>  $status
        ];

        if($idKategoriProduk == 0){
            $procInsertData =   $mainOperation->insertDataTable(APP_MAIN_DATABASE_CUSTOMER . '.m_kategori', $arrInsertUpdate);
            if(!$procInsertData['status']) return switchMySQLErrorCode($procInsertData['errCode']);
        } else {
            $procUpdateData =   $mainOperation->updateDataTable(APP_MAIN_DATABASE_CUSTOMER . '.m_kategori', $arrInsertUpdate, ['IDKATEGORI' => $idKategoriProduk]);
            if(!$procUpdateData['status']) return switchMySQLErrorCode($procUpdateData['errCode']);
        }
                    
        $responseSuccess =   $idKategoriProduk == 0 ? 'Data kategori produk telah disimpan' : 'Data kategori produk telah diperbarui';
        return throwResponseOK($responseSuccess);
    }

    private function parametersValidator($isUpdate = false, $idKategoriProduk = null)
    {
        $rules      =   [
            'kategoriProduk'=>  ['label' => 'Nama Kategori', 'rules' => 'required|alpha_numeric_punct|min_length[3]|max_length[100]'],
            'deskripsi'     =>  ['label' => 'Deskripsi', 'rules' => 'required|alpha_numeric_punct'],
            'status'        =>  ['label' => 'Status', 'rules' => 'required|in_list[-1,1]']
        ];

        $messages   =   [
            'deskripsi'  =>  [
                'required'  =>  'Deskripsi kategori harus diisi'
            ],
            'status'        =>  [
                'required'  =>  'Status kategori harus dipilih',
                'in_list'   =>  'Status kategori yang dipilih tidak valid'
            ]
        ];

        if($isUpdate) {
            $rules['kategoriProduk']['rules']               .=  '|is_unique['.APP_MAIN_DATABASE_CUSTOMER_CI_VALIDATION . '.m_kategori.NAMAKATEGORI, IDKATEGORI, '.$idKategoriProduk.']';
            $rules['idKategoriProduk']['rules']             =   'required|alpha_numeric';
            $messages['idKategoriProduk']['required']       =   'Data kiriman tidak lengkap, silakan periksa kembali';
            $messages['idKategoriProduk']['alpha_numeric']  =   'Data kiriman tidak lengkap, silakan periksa kembali';
        } else {
            $rules['kategoriProduk']['rules']               .=  '|is_unique['.APP_MAIN_DATABASE_CUSTOMER_CI_VALIDATION . '.m_kategori.NAMAKATEGORI]';
        }

        if(!$this->validate($rules, $messages)) return $this->validator->getErrors();
        return true;
    }
}

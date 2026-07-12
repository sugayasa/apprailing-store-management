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
}

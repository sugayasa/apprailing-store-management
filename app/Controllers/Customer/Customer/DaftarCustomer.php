<?php

namespace App\Controllers\Customer\Customer;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Models\MainOperation;
use App\Models\Customer\Customer\DaftarCustomerModel;

class DaftarCustomer extends ResourceController
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
        $mainOperation          =   new MainOperation();
        $daftarCustomerModel    =   new DaftarCustomerModel();

        $rules     =   [
            'searchKeyword' =>  ['label' => 'Kata Kunci', 'rules' => 'permit_empty|string']
        ];

        $messages   =   [];

        if(!$this->validate(array_merge($rules, APP_PAGE_PROPERTY_DEFAULT_RULES), array_merge($messages, APP_PAGE_PROPERTY_DEFAULT_MESSAGES))) return $this->fail($this->validator->getErrors());

        $pageNumber     =   $this->request->getVar('pageNumber') ? (int)$this->request->getVar('pageNumber') : 1;
        $dataPerPage    =   $this->request->getVar('dataPerPage') ? (int)$this->request->getVar('dataPerPage') : 10;
        $searchKeyword  =   $this->request->getVar('searchKeyword');
        $baseData       =   $daftarCustomerModel->getDataDaftarCustomer($searchKeyword);
        $totalNumberData=   $baseData->countAllResults(false);
        $pageProperty   =   $mainOperation->generatePageProperty($pageNumber, $dataPerPage, $totalNumberData);

        if($totalNumberData > 0){
            $listData   =   $baseData->asObject()->findAll($dataPerPage, ($pageNumber - 1) * $dataPerPage);
            $listData   =   encodeDatabaseObjectResultKey($listData, ['IDCUSTOMER']);

            return $this->setResponseFormat('json')->respond([
                "urlBaseAvatarImage"=>  BASE_URL_ASSETS_CUSTOMER_AVATAR,
                "listData"          =>  $listData,
                "pageProperty"      =>  $pageProperty
            ]);
        } else {
            $dataReturn =   [
                "urlBaseAvatarImage"=>  BASE_URL_ASSETS_CUSTOMER_AVATAR,
                "listData"          =>  [],
                "pageProperty"      =>  $pageProperty
            ];
            return throwResponseNotFound('Tidak ada data yang ditemukan', $dataReturn);
        }
    }

    public function getDataTableDetail()
    {
        $mainOperation      =   new MainOperation();
        $daftarCustomerModel=   new DaftarCustomerModel();

        $rules      =   [
            'dataType'  =>  ['label' => 'Tipe Data', 'rules' => 'required|in_list[alamat,transaksi,feed]'],
            'idCustomer'=>  ['label' => 'ID Customer', 'rules' => 'required|alpha_numeric']
        ];

        $messages   =   [
            'dataType'  =>  [
                'required'  =>  'Data kiriman tidak valid',
                'in_list'   =>  'Data kiriman tidak valid'
            ],
            'idCustomer'=>  [
                'required'      =>  'Data kiriman tidak valid',
                'alpha_numeric' =>  'Data kiriman tidak valid'
            ]
        ];

        if(!$this->validate(array_merge($rules, APP_PAGE_PROPERTY_DEFAULT_RULES), array_merge($messages, APP_PAGE_PROPERTY_DEFAULT_MESSAGES))) return $this->fail($this->validator->getErrors());

        $pageNumber     =   $this->request->getVar('pageNumber') ? (int)$this->request->getVar('pageNumber') : 1;
        $dataPerPage    =   $this->request->getVar('dataPerPage') ? (int)$this->request->getVar('dataPerPage') : 10;
        $dataType       =   $this->request->getVar('dataType');
        $idCustomer     =   $this->request->getVar('idCustomer');
        $idCustomer     =   $idCustomer != "" ? hashidDecode($idCustomer) : 0;

        switch ($dataType) {
            case 'alamat':
                $baseData   =   $daftarCustomerModel->getDataDetailAlamat($idCustomer);
                break;
            case 'transaksi':
                $baseData   =   $daftarCustomerModel->getDataDetailTransaksi($idCustomer);
                break;
            case 'feed':
                $baseData   =   $daftarCustomerModel->getDataDetailFeed($idCustomer);
                break;
            default:
                return throwResponseNotFound('Tidak ada data yang ditemukan', []);
        }

        $totalNumberData=   $baseData->countAllResults(false);
        $pageProperty   =   $mainOperation->generatePageProperty($pageNumber, $dataPerPage, $totalNumberData);

        if($totalNumberData > 0){
            $listData   =   $baseData->asObject()->findAll($dataPerPage, ($pageNumber - 1) * $dataPerPage);
            
            if($dataType === 'transaksi') $listData   =   encodeDatabaseObjectResultKey($listData, ['IDTRANSAKSIREKAP']);

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

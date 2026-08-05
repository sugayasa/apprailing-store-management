<?php

namespace App\Controllers\Customer\Customer;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Models\MainOperation;
use App\Models\Customer\Customer\KritikSaranModel;

class KritikSaran extends ResourceController
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
        $kritikSaranModel   =   new KritikSaranModel();

        $rules      =   [
            'tanggalAwal'   =>  ['label' => 'Tanggal Awal', 'rules' => 'required|valid_date'],
            'tanggalAkhir'  =>  ['label' => 'Tanggal Akhir', 'rules' => 'required|valid_date'],
            'searchKeyword' =>  ['label' => 'Kata Kunci', 'rules' => 'permit_empty|string']
        ];

        $messages   =   [];

        if(!$this->validate(array_merge($rules, APP_PAGE_PROPERTY_DEFAULT_RULES), array_merge($messages, APP_PAGE_PROPERTY_DEFAULT_MESSAGES))) return $this->fail($this->validator->getErrors());

        $tanggalAwal    =   $this->request->getVar('tanggalAwal');
        $tanggalAkhir   =   $this->request->getVar('tanggalAkhir');
        $pageNumber     =   $this->request->getVar('pageNumber') ? (int)$this->request->getVar('pageNumber') : 1;
        $dataPerPage    =   $this->request->getVar('dataPerPage') ? (int)$this->request->getVar('dataPerPage') : 10;
        $searchKeyword  =   $this->request->getVar('searchKeyword');
        $baseData       =   $kritikSaranModel->getDataKritikSaran($tanggalAwal, $tanggalAkhir, $searchKeyword);
        $totalNumberData=   $baseData->countAllResults(false);
        $pageProperty   =   $mainOperation->generatePageProperty($pageNumber, $dataPerPage, $totalNumberData);

        if($totalNumberData > 0){
            $listData   =   $baseData->asObject()->findAll($dataPerPage, ($pageNumber - 1) * $dataPerPage);

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

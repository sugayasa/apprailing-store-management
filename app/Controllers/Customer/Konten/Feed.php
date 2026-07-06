<?php

namespace App\Controllers\Customer\Konten;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Models\MainOperation;
use App\Models\Customer\Konten\FeedModel;

class Feed extends ResourceController
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
        $mainOperation  =   new MainOperation();
        $feedModel      =   new FeedModel();

        $rules      =   [
            'searchKeyword' =>  ['label' => 'Nama Merk', 'rules' => 'permit_empty|alpha_numeric_punct']
        ];

        $messages   =   [];

        if(!$this->validate(array_merge($rules, APP_PAGE_PROPERTY_DEFAULT_RULES), array_merge($messages, APP_PAGE_PROPERTY_DEFAULT_MESSAGES))) return $this->fail($this->validator->getErrors());

        $pageNumber     =   $this->request->getVar('pageNumber') ? (int)$this->request->getVar('pageNumber') : 1;
        $dataPerPage    =   $this->request->getVar('dataPerPage') ? (int)$this->request->getVar('dataPerPage') : 10;
        $searchKeyword  =   $this->request->getVar('searchKeyword');
        $baseData       =   $feedModel->getDataFeed($searchKeyword);
        $totalNumberData=   $baseData->countAllResults(false);
        $pageProperty   =   $mainOperation->generatePageProperty($pageNumber, $dataPerPage, $totalNumberData);

        if($totalNumberData > 0){
            $listData   =   $baseData->asObject()->findAll($dataPerPage, ($pageNumber - 1) * $dataPerPage);
            $listData   =   encodeDatabaseObjectResultKey($listData, ['IDFEED']);

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
        $idFeed     =   $this->request->getVar('idFeed');
        $idFeed     =   $idFeed != "" ? hashidDecode($idFeed) : 0;
        $validation =   $idFeed == 0 ? $this->parametersValidator() : $this->parametersValidator(true, $idFeed);
        
        if($validation !== true) return $this->fail($validation);
        
        $mainOperation  =   new MainOperation();
        $judul          =   $this->request->getVar('judul');
        $urlFeed        =   $this->request->getVar('urlFeed');
        $deskripsi      =   $this->request->getVar('deskripsi');
        $arrInsertUpdate=   [
            'JUDUL'             =>  $judul,
            'URLFEED'           =>  $urlFeed,
            'DESKRIPSI'         =>  $deskripsi,
            'INPUTUSER'         =>  $this->userData->name,
            'INPUTTANGGALWAKTU' =>  $this->currentDateTime
        ];

        if($idFeed == 0){
            $procInsertData =   $mainOperation->insertDataTable(APP_MAIN_DATABASE_CUSTOMER . '.t_feed', $arrInsertUpdate);
            if(!$procInsertData['status']) return switchMySQLErrorCode($procInsertData['errCode']);
        } else {
            $procUpdateData =   $mainOperation->updateDataTable(APP_MAIN_DATABASE_CUSTOMER . '.t_feed', $arrInsertUpdate, ['IDFEED' => $idFeed]);
            if(!$procUpdateData['status']) return switchMySQLErrorCode($procUpdateData['errCode']);
        }
                    
        $responseSuccess    =   $idFeed == 0 ? 'Data feed telah disimpan' : 'Data feed telah diperbarui';
        return throwResponseOK($responseSuccess);
    }

    private function parametersValidator($isUpdate = false, $idFeed = null)
    {
        $rules      =   [
            'judul'     =>  ['label' => 'Judul', 'rules' => 'required|string|min_length[3]|max_length[50]'],
            'urlFeed'   =>  ['label' => 'URL Feed', 'rules' => 'required|valid_url|max_length[150]'],
            'deskripsi' =>  ['label' => 'Deskripsi', 'rules' => 'required']
        ];

        $messages   =   [
            'urlFeed'   =>  [
                'valid_url' =>  'URL Feed harus berupa URL yang valid'
            ]
        ];

        if($isUpdate) {
            $rules['urlFeed']['rules']          .=  '|is_unique['.APP_MAIN_DATABASE_CUSTOMER_CI_VALIDATION . '.t_feed.URLFEED, IDFEED, '.$idFeed.']';
            $rules['idFeed']['rules']           =   'required|alpha_numeric';
            $messages['idFeed']['required']     =   'Data kiriman tidak lengkap, silakan periksa kembali';
            $messages['idFeed']['alpha_numeric']=   'Data kiriman tidak lengkap, silakan periksa kembali';
        } else {
            $rules['urlFeed']['rules']          .=  '|is_unique['.APP_MAIN_DATABASE_CUSTOMER_CI_VALIDATION . '.t_feed.URLFEED]';
        }

        if(!$this->validate($rules, $messages)) return $this->validator->getErrors();
        return true;
    }
}

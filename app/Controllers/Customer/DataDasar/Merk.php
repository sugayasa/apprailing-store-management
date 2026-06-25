<?php

namespace App\Controllers\Customer\DataDasar;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Models\MainOperation;
use App\Models\Customer\DataDasar\MerkModel;
use App\Libraries\StorageFactory;

class Merk extends ResourceController
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
        $merkModel      =   new MerkModel();

        $pageNumber     =   $this->request->getVar('pageNumber') ? (int)$this->request->getVar('pageNumber') : 1;
        $dataPerPage    =   $this->request->getVar('dataPerPage') ? (int)$this->request->getVar('dataPerPage') : 24;
        $baseData       =   $merkModel->getDataMerk();
        $totalNumberData=   $baseData->countAllResults(false);
        $pageProperty   =   $mainOperation->generatePageProperty($pageNumber, $dataPerPage, $totalNumberData);

        if($totalNumberData > 0){
            $listData   =   $baseData->asObject()->findAll($dataPerPage, ($pageNumber - 1) * $dataPerPage);
            $listData   =   encodeDatabaseObjectResultKey($listData, ['IDMERK']);

            return $this->setResponseFormat('json')->respond([
                "listData"          =>  $listData,
                "pageProperty"      =>  $pageProperty,
                "urlAssetLogoMerk"  =>  BASE_URL_ASSETS_LOGO_MERK
            ]);
        } else {
            $dataReturn =   [
                "listData"          =>  [],
                "pageProperty"      =>  $pageProperty,
                "urlAssetLogoMerk"  =>  BASE_URL_ASSETS_LOGO_MERK
            ];
            return throwResponseNotFound('Tidak ada data yang ditemukan', $dataReturn);
        }
    }
	
	public function uploadLogo(){
		helper(['fileValidation']);
		validate_image($_FILES["file"], 1000000);
		
		$storage	=	StorageFactory::make();
		$dir		=	PATH_STORAGE_FILE_LOGO_MERK;
		$extension	=	pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);
		$filename	=	"LogoMerk"."_".date('YmdHis').".".$extension;
		$move		=	$storage->upload($_FILES["file"]["tmp_name"], $dir.$filename);
		
		if($move){
            return $this->setResponseFormat('json')
			->respond([
				"status"    =>  200,
				"urlLogo"   =>  BASE_URL_ASSETS_LOGO_MERK.$filename,
				"fileName"  =>  $filename,
				"message"   =>  "Berkas berhasil diunggah"
			]);
		} else {
			return throwResponseInternalServerError("Gagal mengunggah berkas. Silakan coba lagi nanti");
		}
	}

    public function saveData()
    {
        
        $idMerk     =   $this->request->getVar('idMerk');
        $idMerk     =   $idMerk != "" ? hashidDecode($idMerk) : 0;
        $validation =   $idMerk == 0 ? $this->parametersValidator() : $this->parametersValidator(true, $idMerk);
        
        if($validation !== true) return $this->fail($validation);
        
        $mainOperation  =   new MainOperation();
        $namaMerk       =   $this->request->getVar('namaMerk');
        $logoFileName   =   $this->request->getVar('logoFileName');
        $status         =   $this->request->getVar('status');
        $arrInsertUpdate=   [
            'NAMAMERK'  =>  $namaMerk,
            'LOGO'      =>  $logoFileName,
            'STATUS'    =>  $status
        ];

        if($idMerk == 0){
            $procInsertData =   $mainOperation->insertDataTable(APP_MAIN_DATABASE_CUSTOMER . '.m_merk', $arrInsertUpdate);
            if(!$procInsertData['status']) return switchMySQLErrorCode($procInsertData['errCode']);
        } else {
            $procUpdateData =   $mainOperation->updateDataTable(APP_MAIN_DATABASE_CUSTOMER . '.m_merk', $arrInsertUpdate, ['IDMERK' => $idMerk]);
            if(!$procUpdateData['status']) return switchMySQLErrorCode($procUpdateData['errCode']);
        }
                    
        $responseSuccess =   $idMerk == 0 ? 'Data merk telah disimpan' : 'Data merk telah diperbarui';
        return throwResponseOK($responseSuccess);
    }

    private function parametersValidator($isUpdate = false, $idMerk = null)
    {
        $rules      =   [
            'namaMerk'      =>  ['label' => 'Nama Merk', 'rules' => 'required|alpha_numeric_space|min_length[3]|max_length[50]'],
            'logoFileName'  =>  ['label' => 'Logo', 'rules' => 'required|alpha_numeric_punct'],
            'status'        =>  ['label' => 'Status', 'rules' => 'required|in_list[-1,1]']
        ];

        $messages   =   [
            'logoFileName'  =>  [
                'required'  =>  'Logo merk harus diunggah'
            ],
            'status'        =>  [
                'required'  =>  'Status merk harus dipilih',
                'in_list'   =>  'Status merk yang dipilih tidak valid'
            ]
        ];

        if($isUpdate) {
            $rules['namaMerk']['rules']           .=  '|is_unique['.APP_MAIN_DATABASE_CUSTOMER_CI_VALIDATION . '.m_merk.NAMAMERK, IDMERK, '.$idMerk.']';
            $rules['idMerk']['rules']             =   'required|alpha_numeric';
            $messages['idMerk']['required']       =   'Data kiriman tidak lengkap, silakan periksa kembali';
            $messages['idMerk']['alpha_numeric']  =   'Data kiriman tidak lengkap, silakan periksa kembali';
        } else {
            $rules['namaMerk']['rules']           .=  '|is_unique['.APP_MAIN_DATABASE_CUSTOMER_CI_VALIDATION . '.m_merk.NAMAMERK]';
        }

        if(!$this->validate($rules, $messages)) return $this->validator->getErrors();
        return true;
    }
}

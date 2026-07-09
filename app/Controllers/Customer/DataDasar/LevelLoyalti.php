<?php

namespace App\Controllers\Customer\DataDasar;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Models\MainOperation;
use App\Models\Customer\DataDasar\LevelLoyaltiModel;
use App\Libraries\StorageFactory;

class LevelLoyalti extends ResourceController
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
        $levelLoyaltiModel  =   new LevelLoyaltiModel();

        $pageNumber     =   $this->request->getVar('pageNumber') ? (int)$this->request->getVar('pageNumber') : 1;
        $dataPerPage    =   $this->request->getVar('dataPerPage') ? (int)$this->request->getVar('dataPerPage') : 24;
        $baseData       =   $levelLoyaltiModel->getDataLevelLoyalti();
        $totalNumberData=   $baseData->countAllResults(false);
        $pageProperty   =   $mainOperation->generatePageProperty($pageNumber, $dataPerPage, $totalNumberData);

        if($totalNumberData > 0){
            $listData   =   $baseData->asObject()->findAll($dataPerPage, ($pageNumber - 1) * $dataPerPage);
            $listData   =   encodeDatabaseObjectResultKey($listData, ['IDCUSTOMERLOYALTI']);

            return $this->setResponseFormat('json')->respond([
                "listData"                  =>  $listData,
                "pageProperty"              =>  $pageProperty,
                "urlAssetIconLevelLoyalti"  =>  BASE_URL_ASSETS_ICON_LEVEL_LOYALTI,
                "urlAssetCardLevelLoyalti"  =>  BASE_URL_ASSETS_CARD_LEVEL_LOYALTI
            ]);
        } else {
            $dataReturn =   [
                "listData"                  =>  [],
                "pageProperty"              =>  $pageProperty,
                "urlAssetIconLevelLoyalti"  =>  BASE_URL_ASSETS_ICON_LEVEL_LOYALTI,
                "urlAssetCardLevelLoyalti"  =>  BASE_URL_ASSETS_CARD_LEVEL_LOYALTI
            ];
            return throwResponseNotFound('Tidak ada data yang ditemukan', $dataReturn);
        }
    }
	
	public function uploadIcon(){
		helper(['fileValidation']);
        if (empty($_FILES['file']['tmp_name'])) return throwResponseNotAcceptable("Tidak ada file yang diunggah");
		validate_image($_FILES["file"], 1000000);

		$info	    =	getimagesize($_FILES["file"]["tmp_name"]);
		$width	    =	$info[0];
		$height	    =	$info[1];

		if ($width !== $height || $width < 80 || $height < 80) {
			return throwResponseNotAcceptable("Gambar harus berbentuk persegi (lebar = tinggi) dengan minimal ukuran 80px.");
		}

        if ($width > 200 || $height > 200) {
            return throwResponseNotAcceptable("Ukuran gambar maksimal 200px x 200px.");
        }
		
		$storage	=	StorageFactory::make();
		$dir		=	PATH_STORAGE_FILE_ICON_LEVEL_LOYALTI;
		$extension	=	pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);
		$filename	=	"iconLoyaltiCustomer"."_".date('YmdHis').".".$extension;
		$move		=	$storage->upload($_FILES["file"]["tmp_name"], $dir.$filename);
		
		if($move){
            return $this->setResponseFormat('json')
			->respond([
				"status"    =>  200,
				"urlIcon"   =>  BASE_URL_ASSETS_ICON_LEVEL_LOYALTI.$filename,
				"fileName"  =>  $filename,
				"message"   =>  "Berkas berhasil diunggah"
			]);
		} else {
			return throwResponseInternalServerError("Gagal mengunggah berkas. Silakan coba lagi nanti");
		}
	}
	
	public function uploadCard(){
		helper(['fileValidation']);
        if (empty($_FILES['file']['tmp_name'])) return throwResponseNotAcceptable("Tidak ada file yang diunggah");
        
        $fileValidation =   validate_image($_FILES["file"], 1000000);
        if($fileValidation !== true) return $fileValidation;

		$info	    =	getimagesize($_FILES["file"]["tmp_name"]);
		$width	    =	$info[0];
		$height	    =	$info[1];
		$ratio	    =	$width / $height;

		if ($width < 600 || $height < 400) {
			return throwResponseNotAcceptable("Ukuran gambar minimal 600 x 400 pixel.");
		}

		if ($width > 1200 || $height > 800) {
			return throwResponseNotAcceptable("Ukuran gambar maksimal 1200 x 800 pixel.");
		}


		if (abs($ratio - 1.5) > 0.01) {
			return throwResponseNotAcceptable("Rasio gambar harus 3:2 (lebar : tinggi).");
		}
		
		$storage	=	StorageFactory::make();
		$dir		=	PATH_STORAGE_FILE_CARD_LEVEL_LOYALTI;
		$extension	=	pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);
		$filename	=	"cardLoyaltiCustomer"."_".date('YmdHis').".".$extension;
		$move		=	$storage->upload($_FILES["file"]["tmp_name"], $dir.$filename);
		
		if($move){
            return $this->setResponseFormat('json')
			->respond([
				"status"    =>  200,
				"urlCard"   =>  BASE_URL_ASSETS_CARD_LEVEL_LOYALTI.$filename,
				"fileName"  =>  $filename,
				"message"   =>  "Berkas berhasil diunggah"
			]);
		} else {
			return throwResponseInternalServerError("Gagal mengunggah berkas. Silakan coba lagi nanti");
		}
	}

    public function saveData()
    {
        $idCustomerLoyalti  =   $this->request->getVar('idCustomerLoyalti');
        $idCustomerLoyalti  =   $idCustomerLoyalti != "" ? hashidDecode($idCustomerLoyalti) : 0;
        $validation         =   $idCustomerLoyalti == 0 ? $this->parametersValidator() : $this->parametersValidator(true, $idCustomerLoyalti);
        
        if($validation !== true) return $this->fail($validation);
        
        $mainOperation      =   new MainOperation();
        $levelLoyalti       =   $this->request->getVar('levelLoyalti');
        $deskripsi          =   $this->request->getVar('deskripsi');
        $minNominalPembelian=   $this->request->getVar('minNominalPembelian');
        $minPoin            =   $this->request->getVar('minPoin');
        $cardFileName       =   $this->request->getVar('cardFileName');
        $iconFileName       =   $this->request->getVar('iconFileName');
        $status             =   $this->request->getVar('status');
        $arrInsertUpdate    =   [
            'LOYALTITIER'               =>  $levelLoyalti,
            'DESKRIPSI'                 =>  $deskripsi,
            'MINIMALNOMINALPEMBELIAN'   =>  $minNominalPembelian,
            'MINIMALPOIN'               =>  $minPoin,
            'CARDFILE'                  =>  $cardFileName,
            'ICONFILE'                  =>  $iconFileName,
            'STATUS'                    =>  $status
        ];

        if($idCustomerLoyalti == 0){
            $procInsertData =   $mainOperation->insertDataTable(APP_MAIN_DATABASE_CUSTOMER . '.m_customerloyalti', $arrInsertUpdate);
            if(!$procInsertData['status']) return switchMySQLErrorCode($procInsertData['errCode']);
        } else {
            $procUpdateData =   $mainOperation->updateDataTable(APP_MAIN_DATABASE_CUSTOMER . '.m_customerloyalti', $arrInsertUpdate, ['IDCUSTOMERLOYALTI' => $idCustomerLoyalti]);
            if(!$procUpdateData['status']) return switchMySQLErrorCode($procUpdateData['errCode']);
        }
                    
        $responseSuccess =   $idCustomerLoyalti == 0 ? 'Data level loyalty telah disimpan' : 'Data level loyalty telah diperbarui';
        return throwResponseOK($responseSuccess);
    }

    private function parametersValidator($isUpdate = false, $idCustomerLoyalti = null)
    {
        $rules      =   [
            'levelLoyalti'          =>  ['label' => 'Level Loyalti', 'rules' => 'required|alpha_numeric_space|min_length[3]|max_length[30]'],
            'deskripsi'             =>  ['label' => 'Deskripsi', 'rules' => 'required|alpha_numeric_punct|min_length[1]|max_length[100]'],
            'minNominalPembelian'   =>  ['label' => 'Minimal Nominal Pembelian', 'rules' => 'required|numeric|greater_than_equal_to[0]'],
            'minPoin'               =>  ['label' => 'Minimal Poin', 'rules' => 'required|numeric|greater_than_equal_to[0]'],
            'cardFileName'          =>  ['label' => 'Logo', 'rules' => 'required|alpha_numeric_punct'],
            'iconFileName'          =>  ['label' => 'Icon', 'rules' => 'required|alpha_numeric_punct'],
            'status'                =>  ['label' => 'Status', 'rules' => 'required|in_list[-1,1]']
        ];

        $messages   =   [
            'cardFileName'  =>  [
                'required'  =>  'Gambar kartu loyalty harus diunggah'
            ],
            'iconFileName'  =>  [
                'required'  =>  'Icon loyalty harus diunggah'
            ],
            'status'        =>  [
                'required'  =>  'Status harus dipilih',
                'in_list'   =>  'Status yang dipilih tidak valid'
            ]
        ];

        if($isUpdate) {
            $rules['levelLoyalti']['rules']                 .=  '|is_unique['.APP_MAIN_DATABASE_CUSTOMER_CI_VALIDATION . '.m_customerloyalti.LOYALTITIER, IDCUSTOMERLOYALTI, '.$idCustomerLoyalti.']';
            $rules['idCustomerLoyalti']['rules']             =   'required|alpha_numeric';
            $messages['idCustomerLoyalti']['required']       =   'Data kiriman tidak lengkap, silakan periksa kembali';
            $messages['idCustomerLoyalti']['alpha_numeric']  =   'Data kiriman tidak lengkap, silakan periksa kembali';
        } else {
            $rules['levelLoyalti']['rules']                 .=  '|is_unique['.APP_MAIN_DATABASE_CUSTOMER_CI_VALIDATION . '.m_customerloyalti.LOYALTITIER]';
        }

        if(!$this->validate($rules, $messages)) return $this->validator->getErrors();
        return true;
    }
}

<?php

namespace App\Controllers\Customer\Konten;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Models\MainOperation;
use App\Models\Customer\Konten\PengenalanAplikasiModel;
use App\Libraries\StorageFactory;

class PengenalanAplikasi extends ResourceController
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
        $pengenalanAplikasiModel=   new PengenalanAplikasiModel();

        $pageNumber     =   $this->request->getVar('pageNumber') ? (int)$this->request->getVar('pageNumber') : 1;
        $dataPerPage    =   $this->request->getVar('dataPerPage') ? (int)$this->request->getVar('dataPerPage') : 24;
        $baseData       =   $pengenalanAplikasiModel->getDataSlideOnboarding();
        $totalNumberData=   $baseData->countAllResults(false);
        $pageProperty   =   $mainOperation->generatePageProperty($pageNumber, $dataPerPage, $totalNumberData);

        if($totalNumberData > 0){
            $listData   =   $baseData->asObject()->findAll($dataPerPage, ($pageNumber - 1) * $dataPerPage);
            $listData   =   encodeDatabaseObjectResultKey($listData, ['IDSLIDEBOARDING']);

            return $this->setResponseFormat('json')->respond([
                "listData"                  =>  $listData,
                "pageProperty"              =>  $pageProperty,
                "urlAssetSlideOnboarding"   =>  BASE_URL_ASSETS_SLIDE_ONBOARDING
            ]);
        } else {
            $dataReturn =   [
                "listData"                  =>  [],
                "pageProperty"              =>  $pageProperty,
                "urlAssetSlideOnboarding"   =>  BASE_URL_ASSETS_SLIDE_ONBOARDING
            ];
            return throwResponseNotFound('Tidak ada data yang ditemukan', $dataReturn);
        }
    }
	
	public function uploadImage(){
		helper(['fileValidation']);
        if (empty($_FILES['file']['tmp_name'])) return throwResponseNotAcceptable("Tidak ada file yang diunggah");

        $fileValidation =   validate_image($_FILES["file"], 1000000);
        if($fileValidation !== true) return $fileValidation;

		$info	    =	getimagesize($_FILES["file"]["tmp_name"]);
		$width	    =	$info[0];
		$height	    =	$info[1];

		if ($width < 250 || $height < 250) {
			return throwResponseNotAcceptable("Ukuran gambar minimal 250 x 250 pixel.");
		}

        if ($width > 1000 || $height > 1000) {
			return throwResponseNotAcceptable("Ukuran gambar maksimal 1000 x 1000 pixel.");
		}
		
		$storage	=	StorageFactory::make();
		$dir		=	PATH_STORAGE_PHOTO_SLIDE_ONBOARDING;
		$extension	=	pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);
		$filename	=	"Onboarding"."_".date('YmdHis').".".$extension;
		$move		=	$storage->upload($_FILES["file"]["tmp_name"], $dir.$filename);
		
		if($move){
            return $this->setResponseFormat('json')
			->respond([
				"status"    =>  200,
				"urlLogo"   =>  BASE_URL_ASSETS_SLIDE_ONBOARDING.$filename,
				"fileName"  =>  $filename,
				"message"   =>  "Berkas berhasil diunggah"
			]);
		} else {
			return throwResponseInternalServerError("Gagal mengunggah berkas. Silakan coba lagi nanti");
		}
	}

    public function saveUrutanSlide()
    {
        $rules     =   [
            'arrUrutanSlide.*'  =>  ['label' => 'Urutan', 'rules' => 'required|alpha_numeric']
        ];

        $messages   =   [
            'arrUrutanSlide.*'  =>  [
                'required'      =>  'Urutan tidak valid',
                'alpha_numeric' =>  'Urutan tidak valid'
            ]
        ];

        if(!$this->validate($rules, $messages)) return $this->fail($this->validator->getErrors());
        
        $arrUrutanSlide =   $this->request->getVar('arrUrutanSlide');
        
        if(!is_array($arrUrutanSlide) || count($arrUrutanSlide) <= 0) return throwResponseNotAcceptable("Data kiriman tidak valid, harap ulangi lagi nanti");
        
        $mainOperation  =   new MainOperation();
        $urutanSlide    =   1;
        foreach($arrUrutanSlide as $idSlideBoarding){
            $idSlideBoarding    =   hashidDecode($idSlideBoarding);
            if($idSlideBoarding && is_numeric($idSlideBoarding)){
                $arrUpdateUrutan    =   ['URUTAN' => $urutanSlide];
                $mainOperation->updateDataTable(APP_MAIN_DATABASE_CUSTOMER . '.t_slideboarding', $arrUpdateUrutan, ['IDSLIDEBOARDING' => $idSlideBoarding]);
                $urutanSlide++;
            }
        }

        return throwResponseOK("Urutan slide tersimpan");
    }

    public function saveData()
    {
        $idSlideBoarding=   $this->request->getVar('idSlideBoarding');
        $idSlideBoarding=   $idSlideBoarding != "" ? hashidDecode($idSlideBoarding) : 0;
        $validation     =   $idSlideBoarding == 0 ? $this->parametersValidator() : $this->parametersValidator(true, $idSlideBoarding);
        
        if($validation !== true) return $this->fail($validation);
        
        $mainOperation  =   new MainOperation();
        $kontenDeskripsi=   $this->request->getVar('kontenDeskripsi');
        $imageFileName  =   $this->request->getVar('imageFileName');
        $status         =   $this->request->getVar('status');
        $arrInsertUpdate=   [
            'KONTEN'            =>  $kontenDeskripsi,
            'IMAGE'             =>  $imageFileName,
            'INPUTUSER'         =>  $this->userData->name,
            'INPUTTANGGALWAKTU' =>  $this->currentDateTime,
            'STATUS'            =>  $status
        ];

        if($idSlideBoarding == 0){
            $pengenalanAplikasiModel    =   new PengenalanAplikasiModel();
            $urutanTerakhir             =   (int)$pengenalanAplikasiModel->selectMax('URUTAN')->get()->getRow()->URUTAN ?? 0;
            $arrInsertUpdate['URUTAN']  =   $urutanTerakhir + 1;
            $procInsertData             =   $mainOperation->insertDataTable(APP_MAIN_DATABASE_CUSTOMER . '.t_slideboarding', $arrInsertUpdate);
            if(!$procInsertData['status']) return switchMySQLErrorCode($procInsertData['errCode']);
        } else {
            $procUpdateData =   $mainOperation->updateDataTable(APP_MAIN_DATABASE_CUSTOMER . '.t_slideboarding', $arrInsertUpdate, ['IDSLIDEBOARDING' => $idSlideBoarding]);
            if(!$procUpdateData['status']) return switchMySQLErrorCode($procUpdateData['errCode']);
        }
                    
        $responseSuccess =   $idSlideBoarding == 0 ? 'Data pengenalan aplikasi/slide onboarding telah disimpan' : 'Data pengenalan aplikasi/slide onboarding telah diperbarui';
        return throwResponseOK($responseSuccess);
    }

    private function parametersValidator($isUpdate = false, $idSlideBoarding = null)
    {
        $rules      =   [
            'kontenDeskripsi'   =>  ['label' => 'Konten/Deskripsi', 'rules' => 'required|alpha_numeric_punct|min_length[3]|max_length[150]'],
            'imageFileName'     =>  ['label' => 'Gambar', 'rules' => 'required|alpha_numeric_punct'],
            'status'            =>  ['label' => 'Status', 'rules' => 'required|in_list[-1,1]']
        ];

        $messages   =   [
            'imageFileName' =>  [
                'required'  =>  'Gambar slide harus diunggah'
            ],
            'status'        =>  [
                'required'  =>  'Status harus dipilih',
                'in_list'   =>  'Status yang dipilih tidak valid'
            ]
        ];

        if($isUpdate) {
            $rules['idSlideBoarding']['rules']              =   'required|alpha_numeric';
            $messages['idSlideBoarding']['required']        =   'Data kiriman tidak lengkap, silakan periksa kembali';
            $messages['idSlideBoarding']['alpha_numeric']   =   'Data kiriman tidak lengkap, silakan periksa kembali';
        }

        if(!$this->validate($rules, $messages)) return $this->validator->getErrors();
        return true;
    }
}

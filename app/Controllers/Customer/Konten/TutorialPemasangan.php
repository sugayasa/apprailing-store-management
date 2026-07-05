<?php

namespace App\Controllers\Customer\Konten;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Models\MainOperation;
use App\Models\Customer\Konten\TutorialPemasanganModel;
use App\Libraries\StorageFactory;

class TutorialPemasangan extends ResourceController
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
        $tutorialPemasanganModel=   new TutorialPemasanganModel();

         $rules     =   [
            'searchKeyword' =>  ['label' => 'Nama Merk', 'rules' => 'permit_empty|alpha_numeric_punct']
        ];

        $messages   =   [];

        if(!$this->validate(array_merge($rules, APP_PAGE_PROPERTY_DEFAULT_RULES), array_merge($messages, APP_PAGE_PROPERTY_DEFAULT_MESSAGES))) return $this->fail($this->validator->getErrors());

        $pageNumber     =   $this->request->getVar('pageNumber') ? (int)$this->request->getVar('pageNumber') : 1;
        $dataPerPage    =   $this->request->getVar('dataPerPage') ? (int)$this->request->getVar('dataPerPage') : 10;
        $searchKeyword  =   $this->request->getVar('searchKeyword');
        $baseData       =   $tutorialPemasanganModel->getDataTutorialPemasangan($searchKeyword);
        $totalNumberData=   $baseData->countAllResults(false);
        $pageProperty   =   $mainOperation->generatePageProperty($pageNumber, $dataPerPage, $totalNumberData);

        if($totalNumberData > 0){
            $listData   =   $baseData->asObject()->findAll($dataPerPage, ($pageNumber - 1) * $dataPerPage);
            $listData   =   encodeDatabaseObjectResultKey($listData, ['IDVIDEOCARAPEMASANGAN']);

            foreach ($listData as $keyData) {
                $kontenText =   strip_tags($keyData->KONTEN);
                $kontenText =   html_entity_decode($kontenText, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                $kontenText =   trim($kontenText);

                $posKontenText  =   mb_strpos($kontenText, '.');
                if ($posKontenText !== false) {
                    $kontenText = mb_substr($kontenText, 0, $posKontenText);
                }
                $kontenTextSlice=   mb_substr(trim($kontenText), 0, 250);

                $keyData->KONTEN        =   $kontenTextSlice;
                $keyData->IMAGETHUMBNAIL=   BASE_URL_ASSETS_VIDEO_CARA_PASANG . $keyData->IMAGETHUMBNAIL;
            }

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

    public function saveUrutanTutorial()
    {
        $rules      =   [
            'arrUrutanTutorial.*'   =>  ['label' => 'Urutan', 'rules' => 'required|alpha_numeric']
        ];

        $messages   =   [
            'arrUrutanTutorial.*'   =>  [
                'required'      =>  'Urutan tidak valid',
                'alpha_numeric' =>  'Urutan tidak valid'
            ]
        ];

        if(!$this->validate($rules, $messages)) return $this->fail($this->validator->getErrors());
        
        $arrUrutanTutorial  =   $this->request->getVar('arrUrutanTutorial');
        
        if(!is_array($arrUrutanTutorial) || count($arrUrutanTutorial) <= 0) return throwResponseNotAcceptable("Data kiriman tidak valid, harap ulangi lagi nanti");
        
        $mainOperation  =   new MainOperation();
        $urutanTutorial =   1;
        foreach($arrUrutanTutorial as $idVideoCaraPemasangan){
            $idVideoCaraPemasangan  =   hashidDecode($idVideoCaraPemasangan);
            if($idVideoCaraPemasangan && is_numeric($idVideoCaraPemasangan)){
                $arrUpdateUrutan    =   ['URUTAN' => $urutanTutorial];
                $mainOperation->updateDataTable(APP_MAIN_DATABASE_CUSTOMER . '.t_videocarapemasangan', $arrUpdateUrutan, ['IDVIDEOCARAPEMASANGAN' => $idVideoCaraPemasangan]);
                $urutanTutorial++;
            }
        }

        return throwResponseOK("Urutan tutorial pemasangan tersimpan");
    }

    public function getDetail()
    {
        $rules      =   [
            'idVideoCaraPemasangan' =>  ['label' => 'ID Video Cara Pemasangan', 'rules' => 'required|alpha_numeric']
        ];

        $messages   =   [
            'idVideoCaraPemasangan' =>  [
                'required'      =>  'Data kiriman tidak lengkap, silakan periksa kembali',
                'alpha_numeric' =>  'Data kiriman tidak valid, silakan periksa kembali'
            ]
        ];

        if(!$this->validate($rules, $messages)) return $this->fail($this->validator->getErrors());

        $tutorialPemasanganModel=   new TutorialPemasanganModel();
        $idVideoCaraPemasangan  =   hashidDecode($this->request->getVar('idVideoCaraPemasangan'));
        $detailData             =   $tutorialPemasanganModel->find($idVideoCaraPemasangan);
        
        if(!$detailData) return throwResponseNotFound('Data tidak ditemukan');

        unset($detailData['IDVIDEOCARAPEMASANGAN']);
        return $this->setResponseFormat('json')->respond([
            "dataDetail" => $detailData
        ]);
    }
	
	public function uploadThumbnailVideo(){
		helper(['fileValidation']);
		validate_image($_FILES["file"], 2000000);

		$info	    =	getimagesize($_FILES["file"]["tmp_name"]);
		$width	    =	$info[0];
		$height	    =	$info[1];
		$ratio	    =	$width / $height;

		if ($width < 600 || $height < 337.5) {
			return throwResponseNotAcceptable("Ukuran gambar minimal 600 x 337.5 pixel.");
		}

		if (abs($ratio - (16/9)) > 0.01) {
			return throwResponseNotAcceptable("Rasio gambar harus 16:9 (lebar : tinggi).");
		}
		
		$storage	=	StorageFactory::make();
		$dir		=	PATH_STORAGE_VIDEO_CARA_PASANG;
		$extension	=	pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);
		$filename	=	"TutorialBanner"."_".date('YmdHis').".".$extension;
		$move		=	$storage->upload($_FILES["file"]["tmp_name"], $dir.$filename);
		
		if($move){
            return $this->setResponseFormat('json')
			->respond([
				"status"    =>  200,
				"urlImage"  =>  BASE_URL_ASSETS_VIDEO_CARA_PASANG.$filename,
				"fileName"  =>  $filename,
				"message"   =>  "Berkas berhasil diunggah"
			]);
		} else {
			return throwResponseInternalServerError("Gagal mengunggah berkas. Silakan coba lagi nanti");
		}
	}

    public function saveData()
    {
        $idVideoCaraPemasangan  =   $this->request->getVar('idVideoCaraPemasangan');
        $idVideoCaraPemasangan  =   $idVideoCaraPemasangan != "" ? hashidDecode($idVideoCaraPemasangan) : 0;
        $validation             =   $idVideoCaraPemasangan == 0 ? $this->parametersValidator() : $this->parametersValidator(true, $idVideoCaraPemasangan);
        
        if($validation !== true) return $this->fail($validation);
        
        $mainOperation          =   new MainOperation();
        $thumbnailVideoFileName =   $this->request->getVar('thumbnailVideoFileName');
        $judul                  =   $this->request->getVar('judul');
        $urlVideo               =   $this->request->getVar('urlVideo');
        $konten                 =   $this->request->getVar('konten');
        $status                 =   $this->request->getVar('status');
        $arrInsertUpdate        =   [
            'JUDUL'             =>  $judul,
            'KONTEN'            =>  $konten,
            'IMAGETHUMBNAIL'    =>  $thumbnailVideoFileName,
            'URLVIDEO'          =>  $urlVideo,
            'INPUTUSER'         =>  $this->userData->name,
            'INPUTTANGGALWAKTU' =>  $this->currentDateTime,
            'STATUS'            =>  $status
        ];

        if($idVideoCaraPemasangan == 0){
            $tutorialPemasanganModel    =   new TutorialPemasanganModel();
            $lastUrutan                 =   $tutorialPemasanganModel->selectMax('URUTAN')->get()->getRow();
            $nextUrutan                 =   ($lastUrutan && $lastUrutan->URUTAN !== null) ? (int)$lastUrutan->URUTAN + 1 : 1;
            $arrInsertUpdate['URUTAN']  =   $nextUrutan;
            $procInsertData             =   $mainOperation->insertDataTable(APP_MAIN_DATABASE_CUSTOMER . '.t_videocarapemasangan', $arrInsertUpdate);
            if(!$procInsertData['status']) return switchMySQLErrorCode($procInsertData['errCode']);
        } else {
            $procUpdateData =   $mainOperation->updateDataTable(APP_MAIN_DATABASE_CUSTOMER . '.t_videocarapemasangan', $arrInsertUpdate, ['IDVIDEOCARAPEMASANGAN' => $idVideoCaraPemasangan]);
            if(!$procUpdateData['status']) return switchMySQLErrorCode($procUpdateData['errCode']);
        }
                    
        $responseSuccess =   $idVideoCaraPemasangan == 0 ? 'Data tutorial pemasangan telah disimpan' : 'Data tutorial pemasangan telah diperbarui';
        return throwResponseOK($responseSuccess);
    }

    private function parametersValidator($isUpdate = false, $idVideoCaraPemasangan = null)
    {
        $rules      =   [
            'thumbnailVideoFileName'=>  ['label' => 'Gambar Thumbnail', 'rules' => 'required|alpha_numeric_punct|max_length[50]'],
            'judul'                 =>  ['label' => 'Judul', 'rules' => 'required|alpha_numeric_space|min_length[3]|max_length[25]'],
            'konten'                =>  ['label' => 'Konten', 'rules' => 'required'],
            'urlVideo'              =>  ['label' => 'URL Video', 'rules' => 'required|valid_url|max_length[255]'],
            'status'                =>  ['label' => 'Status', 'rules' => 'required|in_list[-1,1]']
        ];

        $messages   =   [
            'thumbnailVideoFileName'=>  [
                'required'      =>  'Gambar thumbnail harus diunggah',
                'max_length'    =>  'Gambar thumbnail yang diunggah tidak valid'
            ],
            'status'        =>  [
                'required'  =>  'Status berita harus dipilih',
                'in_list'   =>  'Status berita yang dipilih tidak valid'
            ]
        ];

        if($isUpdate) {
            $rules['judul']['rules']                            .=  '|is_unique['.APP_MAIN_DATABASE_CUSTOMER_CI_VALIDATION . '.t_videocarapemasangan.JUDUL, IDVIDEOCARAPEMASANGAN, '.$idVideoCaraPemasangan.']';
            $rules['idVideoCaraPemasangan']['rules']            =   'required|alpha_numeric';
            $messages['idVideoCaraPemasangan']['required']      =   'Data kiriman tidak lengkap, silakan periksa kembali';
            $messages['idVideoCaraPemasangan']['alpha_numeric'] =   'Data kiriman tidak lengkap, silakan periksa kembali';
        } else {
            $rules['judul']['rules']    .=  '|is_unique['.APP_MAIN_DATABASE_CUSTOMER_CI_VALIDATION . '.t_videocarapemasangan.JUDUL]';
        }

        if(!$this->validate($rules, $messages)) return $this->validator->getErrors();
        return true;
    }
}

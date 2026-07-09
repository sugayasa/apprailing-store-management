<?php

namespace App\Controllers\Customer\Konten;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Models\MainOperation;
use App\Models\Customer\Konten\BeritaInformasiModel;
use App\Libraries\StorageFactory;

class BeritaInformasi extends ResourceController
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
        $beritaInformasiModel   =   new BeritaInformasiModel();

         $rules     =   [
            'searchKeyword' =>  ['label' => 'Nama Merk', 'rules' => 'permit_empty|alpha_numeric_punct']
        ];

        $messages   =   [];

        if(!$this->validate(array_merge($rules, APP_PAGE_PROPERTY_DEFAULT_RULES), array_merge($messages, APP_PAGE_PROPERTY_DEFAULT_MESSAGES))) return $this->fail($this->validator->getErrors());

        $pageNumber     =   $this->request->getVar('pageNumber') ? (int)$this->request->getVar('pageNumber') : 1;
        $dataPerPage    =   $this->request->getVar('dataPerPage') ? (int)$this->request->getVar('dataPerPage') : 10;
        $searchKeyword  =   $this->request->getVar('searchKeyword');
        $baseData       =   $beritaInformasiModel->getDataBeritaInformasi($searchKeyword);
        $totalNumberData=   $baseData->countAllResults(false);
        $pageProperty   =   $mainOperation->generatePageProperty($pageNumber, $dataPerPage, $totalNumberData);

        if($totalNumberData > 0){
            $listData   =   $baseData->asObject()->findAll($dataPerPage, ($pageNumber - 1) * $dataPerPage);
            $listData   =   encodeDatabaseObjectResultKey($listData, ['IDSLIDEBANNER']);

            foreach ($listData as $keyData) {
                $kontenText =   strip_tags($keyData->KONTEN);
                $kontenText =   html_entity_decode($kontenText, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                $kontenText =   trim($kontenText);

                $posKontenText  =   strpos($kontenText, '.');
                if ($posKontenText !== false) {
                    $kontenText = mb_substr($kontenText, 0, $posKontenText);
                }
                $kontenTextSlice=   mb_substr(trim($kontenText), 0, 250);

                $keyData->KONTEN=   $kontenTextSlice;
                $keyData->IMAGE =   BASE_URL_ASSETS_SLIDE_BANNER . $keyData->IMAGE;
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

    public function getDetail()
    {
        $rules      =   [
            'idSlideBanner'     =>  ['label' => 'ID Slide Banner', 'rules' => 'required|alpha_numeric']
        ];

        $messages   =   [
            'idSlideBanner' =>  [
                'required'      =>  'Data kiriman tidak lengkap, silakan periksa kembali',
                'alpha_numeric' =>  'Data kiriman tidak valid, silakan periksa kembali'
            ]
        ];

        if(!$this->validate($rules, $messages)) return $this->fail($this->validator->getErrors());

        $beritaInformasiModel   =   new BeritaInformasiModel();
        $idSlideBanner          =   hashidDecode($this->request->getVar('idSlideBanner'));
        $detailData             =   $beritaInformasiModel->find($idSlideBanner);
        
        if(!$detailData) return throwResponseNotFound('Data tidak ditemukan');

        unset($detailData['IDSLIDEBANNER']);
        return $this->setResponseFormat('json')->respond([
            "dataDetail" => $detailData
        ]);
    }
	
	public function uploadImage(){
		helper(['fileValidation']);
        if (empty($_FILES['file']['tmp_name'])) return throwResponseNotAcceptable("Tidak ada file yang diunggah");

        $fileValidation =   validate_image($_FILES["file"], 2000000);
        if($fileValidation !== true) return $fileValidation;

		$info	    =	getimagesize($_FILES["file"]["tmp_name"]);
		$width	    =	$info[0];
		$height	    =	$info[1];
		$ratio	    =	$width / $height;

		if ($width < 600 || $height < 337.5) {
			return throwResponseNotAcceptable("Ukuran gambar minimal 600 x 337.5 pixel.");
		}

        if ($width > 1200 || $height > 675) {
			return throwResponseNotAcceptable("Ukuran gambar maksimal 1200 x 675 pixel.");
		}

		if (abs($ratio - (16/9)) > 0.01) {
			return throwResponseNotAcceptable("Rasio gambar harus 16:9 (lebar : tinggi).");
		}
		
		$storage	=	StorageFactory::make();
		$dir		=	PATH_STORAGE_PHOTO_SLIDE_BANNER;
		$extension	=	pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);
		$filename	=	"SlideBanner"."_".date('YmdHis').".".$extension;
		$move		=	$storage->upload($_FILES["file"]["tmp_name"], $dir.$filename);
		
		if($move){
            return $this->setResponseFormat('json')
			->respond([
				"status"    =>  200,
				"urlImage"  =>  BASE_URL_ASSETS_SLIDE_BANNER.$filename,
				"fileName"  =>  $filename,
				"message"   =>  "Berkas berhasil diunggah"
			]);
		} else {
			return throwResponseInternalServerError("Gagal mengunggah berkas. Silakan coba lagi nanti");
		}
	}

    public function saveData()
    {
        $idSlideBanner  =   $this->request->getVar('idSlideBanner');
        $idSlideBanner  =   $idSlideBanner != "" ? hashidDecode($idSlideBanner) : 0;
        $validation     =   $idSlideBanner == 0 ? $this->parametersValidator() : $this->parametersValidator(true, $idSlideBanner);
        
        if($validation !== true) return $this->fail($validation);
        
        $mainOperation      =   new MainOperation();
        $slideBannerFileName=   $this->request->getVar('slideBannerFileName');
        $judul              =   $this->request->getVar('judul');
        $konten             =   $this->request->getVar('konten');
        $status             =   $this->request->getVar('status');
        $arrInsertUpdate    =   [
            'JUDUL'             =>  $judul,
            'KONTEN'            =>  $konten,
            'IMAGE'             =>  $slideBannerFileName,
            'INPUTUSER'         =>  $this->userData->name,
            'INPUTTANGGALWAKTU' =>  $this->currentDateTime,
            'STATUS'            =>  $status
        ];

        if($idSlideBanner == 0){
            $procInsertData =   $mainOperation->insertDataTable(APP_MAIN_DATABASE_CUSTOMER . '.t_slidebanner', $arrInsertUpdate);
            if(!$procInsertData['status']) return switchMySQLErrorCode($procInsertData['errCode']);
        } else {
            $procUpdateData =   $mainOperation->updateDataTable(APP_MAIN_DATABASE_CUSTOMER . '.t_slidebanner', $arrInsertUpdate, ['IDSLIDEBANNER' => $idSlideBanner]);
            if(!$procUpdateData['status']) return switchMySQLErrorCode($procUpdateData['errCode']);
        }
                    
        $responseSuccess =   $idSlideBanner == 0 ? 'Data berita/informasi telah disimpan' : 'Data berita/informasi telah diperbarui';
        return throwResponseOK($responseSuccess);
    }

    private function parametersValidator($isUpdate = false, $idSlideBanner = null)
    {
        $rules      =   [
            'slideBannerFileName'   =>  ['label' => 'Slide Banner', 'rules' => 'required|alpha_numeric_punct'],
            'judul'                 =>  ['label' => 'Judul', 'rules' => 'required|alpha_numeric_space|min_length[3]|max_length[75]'],
            'konten'                =>  ['label' => 'Konten', 'rules' => 'required'],
            'status'                =>  ['label' => 'Status', 'rules' => 'required|in_list[-1,1]']
        ];

        $messages   =   [
            'slideBannerFileName'  =>  [
                'required'  =>  'Slide banner harus diunggah'
            ],
            'status'        =>  [
                'required'  =>  'Status berita harus dipilih',
                'in_list'   =>  'Status berita yang dipilih tidak valid'
            ]
        ];

        if($isUpdate) {
            $rules['idSlideBanner']['rules']             =   'required|alpha_numeric';
            $messages['idSlideBanner']['required']       =   'Data kiriman tidak lengkap, silakan periksa kembali';
            $messages['idSlideBanner']['alpha_numeric']  =   'Data kiriman tidak lengkap, silakan periksa kembali';
        }

        if(!$this->validate($rules, $messages)) return $this->validator->getErrors();
        return true;
    }
}

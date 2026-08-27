<?php

namespace App\Controllers\Customer\Konten;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Models\MainOperation;
use App\Models\Customer\Konten\SlideKolaborasiModel;
use App\Libraries\StorageFactory;

class SlideKolaborasi extends ResourceController
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
        $slideKolaborasiModel   =   new SlideKolaborasiModel();

         $rules     =   [
            'searchKeyword' =>  ['label' => 'Kata Kunci', 'rules' => 'permit_empty|alpha_numeric_punct']
        ];

        $messages   =   [];

        if(!$this->validate(array_merge($rules, APP_PAGE_PROPERTY_DEFAULT_RULES), array_merge($messages, APP_PAGE_PROPERTY_DEFAULT_MESSAGES))) return $this->fail($this->validator->getErrors());

        $pageNumber     =   $this->request->getVar('pageNumber') ? (int)$this->request->getVar('pageNumber') : 1;
        $dataPerPage    =   $this->request->getVar('dataPerPage') ? (int)$this->request->getVar('dataPerPage') : 10;
        $searchKeyword  =   $this->request->getVar('searchKeyword');
        $baseData       =   $slideKolaborasiModel->getDataSlideKolaborasi($searchKeyword);
        $totalNumberData=   $baseData->countAllResults(false);
        $pageProperty   =   $mainOperation->generatePageProperty($pageNumber, $dataPerPage, $totalNumberData);

        if($totalNumberData > 0){
            $listData   =   $baseData->asObject()->findAll($dataPerPage, ($pageNumber - 1) * $dataPerPage);
            $listData   =   encodeDatabaseObjectResultKey($listData, ['IDSLIDEKOLABORASI']);

            foreach ($listData as $keyData) {
                $kontenText =   strip_tags($keyData->KONTEN);
                $kontenText =   html_entity_decode($kontenText, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                $kontenText =   trim($kontenText);

                $posKontenText  =   strpos($kontenText, '.');
                if ($posKontenText !== false) {
                    $kontenText = mb_substr($kontenText, 0, $posKontenText);
                }
                $kontenTextSlice=   mb_substr(trim($kontenText), 0, 250);

                $keyData->KONTEN                =   $kontenTextSlice;
                $keyData->IMAGEPRODUK           =   BASE_URL_ASSETS_SLIDE_KOLABORASI_PRODUK . $keyData->IMAGEPRODUK;
                $keyData->IMAGETHUMBNAILVIDEO   =   BASE_URL_ASSETS_SLIDE_KOLABORASI_THUMBNAIL . $keyData->IMAGETHUMBNAILVIDEO;
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
            'idSlideKolaborasi' =>  ['label' => 'ID Slide Kolaborasi', 'rules' => 'required|alpha_numeric']
        ];

        $messages   =   [
            'idSlideKolaborasi' =>  [
                'required'      =>  'Data kiriman tidak lengkap, silakan periksa kembali',
                'alpha_numeric' =>  'Data kiriman tidak valid, silakan periksa kembali'
            ]
        ];

        if(!$this->validate($rules, $messages)) return $this->fail($this->validator->getErrors());

        $slideKolaborasiModel   =   new SlideKolaborasiModel();
        $idSlideKolaborasi      =   hashidDecode($this->request->getVar('idSlideKolaborasi'));
        $detailData             =   $slideKolaborasiModel->find($idSlideKolaborasi);
        
        if(!$detailData) return throwResponseNotFound('Data tidak ditemukan');

        unset($detailData['IDSLIDEKOLABORASI']);
        return $this->setResponseFormat('json')->respond([
            "dataDetail" => $detailData
        ]);
    }
	
	public function uploadImageProduk(){
		helper(['fileValidation']);
        if (empty($_FILES['file']['tmp_name'])) return throwResponseNotAcceptable("Tidak ada file yang diunggah");

        $fileValidation =   validate_image($_FILES["file"], 2000000);
        if($fileValidation !== true) return $fileValidation;

		$info	    =	getimagesize($_FILES["file"]["tmp_name"]);
		$width	    =	$info[0];
		$height	    =	$info[1];
		$ratio	    =	$width / $height;

		if ($width < 300 || $height < 300) {
			return throwResponseNotAcceptable("Ukuran gambar minimal 300 x 300 pixel.");
		}

        if ($width > 1200 || $height > 1200) {
			return throwResponseNotAcceptable("Ukuran gambar maksimal 1200 x 1200 pixel.");
		}

		if ($ratio < 0.9 || $ratio > 1.1) {
			return throwResponseNotAcceptable("Rasio gambar harus 1:1 (lebar : tinggi).");
		}
		
		$storage	=	StorageFactory::make();
		$dir		=	PATH_STORAGE_PHOTO_SLIDE_KOLABORASI_PRODUK;
		$extension	=	pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);
		$filename	=	"SlideKolaborasiProduk"."_".date('YmdHis').".".$extension;
		$move		=	$storage->upload($_FILES["file"]["tmp_name"], $dir.$filename);
		
		if($move){
            return $this->setResponseFormat('json')
			->respond([
				"status"    =>  200,
				"urlImage"  =>  BASE_URL_ASSETS_SLIDE_KOLABORASI_PRODUK.$filename,
				"fileName"  =>  $filename,
				"message"   =>  "Berkas berhasil diunggah"
			]);
		} else {
			return throwResponseInternalServerError("Gagal mengunggah berkas. Silakan coba lagi nanti");
		}
	}

	public function uploadImageThumbnail(){
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
		$dir		=	PATH_STORAGE_PHOTO_SLIDE_KOLABORASI_THUMBNAIL;
		$extension	=	pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);
		$filename	=	"SlideKolaborasiThumbnail"."_".date('YmdHis').".".$extension;
		$move		=	$storage->upload($_FILES["file"]["tmp_name"], $dir.$filename);
		
		if($move){
            return $this->setResponseFormat('json')
			->respond([
				"status"    =>  200,
				"urlImage"  =>  BASE_URL_ASSETS_SLIDE_KOLABORASI_THUMBNAIL.$filename,
				"fileName"  =>  $filename,
				"message"   =>  "Berkas berhasil diunggah"
			]);
		} else {
			return throwResponseInternalServerError("Gagal mengunggah berkas. Silakan coba lagi nanti");
		}
	}

    public function saveData()
    {
        $idSlideKolaborasi  =   $this->request->getVar('idSlideKolaborasi');
        $idSlideKolaborasi  =   $idSlideKolaborasi != "" ? hashidDecode($idSlideKolaborasi) : 0;
        $validation         =   $idSlideKolaborasi == 0 ? $this->parametersValidator() : $this->parametersValidator(true, $idSlideKolaborasi);
        
        if($validation !== true) return $this->fail($validation);
        
        $mainOperation      =   new MainOperation();
        $produkFileName     =   $this->request->getVar('produkFileName');
        $thumbnailFileName  =   $this->request->getVar('thumbnailFileName');
        $judul              =   $this->request->getVar('judul');
        $urlVideo           =   $this->request->getVar('urlVideo');
        $konten             =   $this->request->getVar('konten');
        $status             =   $this->request->getVar('status');
        $arrInsertUpdate    =   [
            'JUDUL'                 =>  $judul,
            'KONTEN'                =>  $konten,
            'IMAGEPRODUK'           =>  $produkFileName,
            'IMAGETHUMBNAILVIDEO'   =>  $thumbnailFileName,
            'URLVIDEO'              =>  $urlVideo,
            'INPUTUSER'             =>  $this->userData->name,
            'INPUTTANGGALWAKTU'     =>  $this->currentDateTime,
            'STATUS'                =>  $status
        ];

        if($idSlideKolaborasi == 0){
            $procInsertData =   $mainOperation->insertDataTable(APP_MAIN_DATABASE_CUSTOMER . '.t_slidekolaborasi', $arrInsertUpdate);
            if(!$procInsertData['status']) return switchMySQLErrorCode($procInsertData['errCode']);
        } else {
            $procUpdateData =   $mainOperation->updateDataTable(APP_MAIN_DATABASE_CUSTOMER . '.t_slidekolaborasi', $arrInsertUpdate, ['IDSLIDEKOLABORASI' => $idSlideKolaborasi]);
            if(!$procUpdateData['status']) return switchMySQLErrorCode($procUpdateData['errCode']);
        }
                    
        $responseSuccess    =   $idSlideKolaborasi == 0 ? 'Data konten kolaborasi telah disimpan' : 'Data konten kolaborasi telah diperbarui';
        return throwResponseOK($responseSuccess);
    }

    private function parametersValidator($isUpdate = false, $idSlideKolaborasi = null)
    {
        $rules      =   [
            'produkFileName'    =>  ['label' => 'Image Produk', 'rules' => 'required|alpha_numeric_punct'],
            'thumbnailFileName' =>  ['label' => 'Image Thumbnail Video', 'rules' => 'required|alpha_numeric_punct'],
            'judul'             =>  ['label' => 'Judul', 'rules' => 'required|string|min_length[3]|max_length[75]'],
            'urlVideo'          =>  ['label' => 'URL Video', 'rules' => 'required|valid_url'],
            'konten'            =>  ['label' => 'Konten', 'rules' => 'required'],
            'status'            =>  ['label' => 'Status', 'rules' => 'required|in_list[-1,1]']
        ];

        $messages   =   [
            'produkFileName'=>  [
                'required'  =>  'Image produk harus diunggah'
            ],
            'thumbnailFileName' =>  [
                'required'      =>  'Image thumbnail video harus diunggah'
            ],
            'urlVideo'  =>  [
                'required'  =>  'URL Video harus diisi',
                'valid_url' =>  'URL Video tidak valid'
            ],
            'status'        =>  [
                'required'  =>  'Status konten kolaborasi harus dipilih',
                'in_list'   =>  'Status konten kolaborasi yang dipilih tidak valid'
            ]
        ];

        if($isUpdate) {
            $rules['idSlideKolaborasi']['rules']             =  'required|alpha_numeric';
            $messages['idSlideKolaborasi']['required']       =  'Data kiriman tidak lengkap, silakan periksa kembali';
            $messages['idSlideKolaborasi']['alpha_numeric']  =  'Data kiriman tidak lengkap, silakan periksa kembali';
            $rules['urlVideo']['rules']                     .=  '|is_unique['.APP_MAIN_DATABASE_CUSTOMER_CI_VALIDATION . '.t_slidekolaborasi.URLVIDEO, IDSLIDEKOLABORASI, '.$idSlideKolaborasi.']';
        } else {
            $rules['urlVideo']['rules']                     .=  '|is_unique['.APP_MAIN_DATABASE_CUSTOMER_CI_VALIDATION . '.t_slidekolaborasi.URLVIDEO]';
        }

        if(!$this->validate($rules, $messages)) return $this->validator->getErrors();
        return true;
    }
}

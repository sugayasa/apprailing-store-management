<?php

namespace App\Controllers\Customer\Konten;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Models\MainOperation;
use App\Models\Customer\Konten\ProfilPerusahaanModel;
use App\Libraries\StorageFactory;


class ProfilPerusahaan extends ResourceController
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
        $profilPerusahaanModel  =   new ProfilPerusahaanModel();

        $rules      =   [
            'searchKeyword' =>  ['label' => 'Nama Merk', 'rules' => 'permit_empty|alpha_numeric_punct']
        ];

        $messages   =   [];

        if(!$this->validate(array_merge($rules, APP_PAGE_PROPERTY_DEFAULT_RULES), array_merge($messages, APP_PAGE_PROPERTY_DEFAULT_MESSAGES))) return $this->fail($this->validator->getErrors());

        $pageNumber     =   $this->request->getVar('pageNumber') ? (int)$this->request->getVar('pageNumber') : 1;
        $dataPerPage    =   $this->request->getVar('dataPerPage') ? (int)$this->request->getVar('dataPerPage') : 10;
        $searchKeyword  =   $this->request->getVar('searchKeyword');
        $baseData       =   $profilPerusahaanModel->getDataProfilPerusahaan($searchKeyword);
        $totalNumberData=   $baseData->countAllResults(false);
        $pageProperty   =   $mainOperation->generatePageProperty($pageNumber, $dataPerPage, $totalNumberData);

        if($totalNumberData > 0){
            $listData   =   $baseData->asObject()->findAll($dataPerPage, ($pageNumber - 1) * $dataPerPage);
            $listData   =   encodeDatabaseObjectResultKey($listData, ['IDVIDEOCOMPANYPROFILE']);

            foreach ($listData as $keyData) {
                $kontenText =   strip_tags($keyData->KONTEN);
                $kontenText =   html_entity_decode($kontenText, ENT_QUOTES | ENT_HTML5, 'UTF-8');
                $kontenText =   trim($kontenText);

                $posKontenText  =   strpos($kontenText, '.');
                if ($posKontenText !== false) {
                    $kontenText = mb_substr($kontenText, 0, $posKontenText);
                }
                $kontenTextSlice=   mb_substr(trim($kontenText), 0, 250);

                $keyData->KONTEN        =   $kontenTextSlice;
                $keyData->IMAGETHUMBNAIL=   BASE_URL_ASSETS_VIDEO_COMPANY_PROFILE . $keyData->IMAGETHUMBNAIL;
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

    public function saveUrutanProfilPerusahaan()
    {
        $rules      =   [
            'arrUrutanProfil.*' =>  ['label' => 'Urutan', 'rules' => 'required|alpha_numeric']
        ];

        $messages   =   [
            'arrUrutanProfil.*' =>  [
                'required'      =>  'Urutan tidak valid',
                'alpha_numeric' =>  'Urutan tidak valid'
            ]
        ];

        if(!$this->validate($rules, $messages)) return $this->fail($this->validator->getErrors());
        
        $arrUrutanProfil    =   $this->request->getVar('arrUrutanProfil');
        
        if(!is_array($arrUrutanProfil) || count($arrUrutanProfil) <= 0) return throwResponseNotAcceptable("Data kiriman tidak valid, harap ulangi lagi nanti");
        
        $mainOperation          =   new MainOperation();
        $urutanProfilPerusahaan =   1;

        foreach($arrUrutanProfil as $idVideoProfilPerusahaan){
            $idVideoProfilPerusahaan    =   hashidDecode($idVideoProfilPerusahaan);
            if($idVideoProfilPerusahaan && is_numeric($idVideoProfilPerusahaan)){
                $arrUpdateUrutan        =   ['URUTAN' => $urutanProfilPerusahaan];
                $mainOperation->updateDataTable(APP_MAIN_DATABASE_CUSTOMER . '.t_videocompanyprofile', $arrUpdateUrutan, ['IDVIDEOCOMPANYPROFILE' => $idVideoProfilPerusahaan]);
                $urutanProfilPerusahaan++;
            }
        }

        return throwResponseOK("Urutan profil perusahaan tersimpan");
    }

    public function getDetail()
    {
        $rules      =   [
            'idVideoProfilPerusahaan'   =>  ['label' => 'ID Video Profil Perusahaan', 'rules' => 'required|alpha_numeric']
        ];

        $messages   =   [
            'idVideoProfilPerusahaan'   =>  [
                'required'      =>  'Data kiriman tidak lengkap, silakan periksa kembali',
                'alpha_numeric' =>  'Data kiriman tidak valid, silakan periksa kembali'
            ]
        ];

        if(!$this->validate($rules, $messages)) return $this->fail($this->validator->getErrors());

        $profilPerusahaanModel  =   new ProfilPerusahaanModel();
        $idVideoProfilPerusahaan=   hashidDecode($this->request->getVar('idVideoProfilPerusahaan'));
        $detailData             =   $profilPerusahaanModel->find($idVideoProfilPerusahaan);
        
        if(!$detailData) return throwResponseNotFound('Data tidak ditemukan');

        unset($detailData['IDVIDEOCOMPANYPROFILE']);
        return $this->setResponseFormat('json')->respond([
            "dataDetail" => $detailData
        ]);
    }
	
	public function uploadThumbnailVideo(){
		helper(['fileValidation']);
        if (empty($_FILES['file']['tmp_name'])) return throwResponseNotAcceptable("Tidak ada file yang diunggah");
		
        $fileValidation =   validate_image($_FILES["file"], 2000000);
        if($fileValidation !== true) return $fileValidation;

		$info   =	getimagesize($_FILES["file"]["tmp_name"]);
		$width  =	$info[0];
		$height =	$info[1];
		$ratio  =	$width / $height;

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
		$dir		=	PATH_STORAGE_VIDEO_COMPANY_PROFILE;
		$extension	=	pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);
		$filename	=	"ProfilPerusahaanThumbnail"."_".date('YmdHis').".".$extension;
		$move		=	$storage->upload($_FILES["file"]["tmp_name"], $dir.$filename);
		
		if($move){
            return $this->setResponseFormat('json')
			->respond([
				"status"    =>  200,
				"urlImage"  =>  BASE_URL_ASSETS_VIDEO_COMPANY_PROFILE.$filename,
				"fileName"  =>  $filename,
				"message"   =>  "Berkas berhasil diunggah"
			]);
		} else {
			return throwResponseInternalServerError("Gagal mengunggah berkas. Silakan coba lagi nanti");
		}
	}

    public function saveData()
    {
        $idVideoProfilPerusahaan=   $this->request->getVar('idVideoProfilPerusahaan');
        $idVideoProfilPerusahaan=   $idVideoProfilPerusahaan != "" ? hashidDecode($idVideoProfilPerusahaan) : 0;
        $validation             =   $idVideoProfilPerusahaan == 0 ? $this->parametersValidator() : $this->parametersValidator(true, $idVideoProfilPerusahaan);
        
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

        if($idVideoProfilPerusahaan == 0){
            $profilPerusahaanModel      =   new ProfilPerusahaanModel();
            $lastUrutan                 =   $profilPerusahaanModel->selectMax('URUTAN')->get()->getRow();
            $nextUrutan                 =   ($lastUrutan && $lastUrutan->URUTAN !== null) ? (int)$lastUrutan->URUTAN + 1 : 1;
            $arrInsertUpdate['URUTAN']  =   $nextUrutan;
            $procInsertData             =   $mainOperation->insertDataTable(APP_MAIN_DATABASE_CUSTOMER . '.t_videocompanyprofile', $arrInsertUpdate);
            if(!$procInsertData['status']) return switchMySQLErrorCode($procInsertData['errCode']);
        } else {
            $procUpdateData =   $mainOperation->updateDataTable(APP_MAIN_DATABASE_CUSTOMER . '.t_videocompanyprofile', $arrInsertUpdate, ['IDVIDEOCOMPANYPROFILE' => $idVideoProfilPerusahaan]);
            if(!$procUpdateData['status']) return switchMySQLErrorCode($procUpdateData['errCode']);
        }
                    
        $responseSuccess    =   $idVideoProfilPerusahaan == 0 ? 'Data profil perusahaan telah disimpan' : 'Data profil perusahaan telah diperbarui';
        return throwResponseOK($responseSuccess);
    }

    private function parametersValidator($isUpdate = false, $idVideoProfilPerusahaan = null)
    {
        $rules      =   [
            'thumbnailVideoFileName'=>  ['label' => 'Gambar Thumbnail', 'rules' => 'required|alpha_numeric_punct|max_length[50]'],
            'judul'                 =>  ['label' => 'Judul', 'rules' => 'required|string|min_length[3]|max_length[50]'],
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
                'required'  =>  'Status profil perusahaan harus dipilih',
                'in_list'   =>  'Status profil perusahaan yang dipilih tidak valid'
            ]
        ];

        if($isUpdate) {
            $rules['judul']['rules']                                .=  '|is_unique['.APP_MAIN_DATABASE_CUSTOMER_CI_VALIDATION . '.t_videocompanyprofile.JUDUL, IDVIDEOCOMPANYPROFILE, '.$idVideoProfilPerusahaan.']';
            $rules['idVideoProfilPerusahaan']['rules']              =   'required|alpha_numeric';
            $messages['idVideoProfilPerusahaan']['required']        =   'Data kiriman tidak lengkap, silakan periksa kembali';
            $messages['idVideoProfilPerusahaan']['alpha_numeric']   =   'Data kiriman tidak lengkap, silakan periksa kembali';
        } else {
            $rules['judul']['rules']    .=  '|is_unique['.APP_MAIN_DATABASE_CUSTOMER_CI_VALIDATION . '.t_videocompanyprofile.JUDUL]';
        }

        if(!$this->validate($rules, $messages)) return $this->validator->getErrors();
        return true;
    }
}

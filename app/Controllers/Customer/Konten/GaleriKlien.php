<?php

namespace App\Controllers\Customer\Konten;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Models\MainOperation;
use App\Models\Customer\Konten\GaleriKlienModel;
use App\Libraries\StorageFactory;

class GaleriKlien extends ResourceController
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
        $galeriKlienModel   =   new GaleriKlienModel();

        $pageNumber     =   $this->request->getVar('pageNumber') ? (int)$this->request->getVar('pageNumber') : 1;
        $dataPerPage    =   $this->request->getVar('dataPerPage') ? (int)$this->request->getVar('dataPerPage') : 3;
        $baseData       =   $galeriKlienModel->getDataKlien();
        $totalNumberData=   $baseData->countAllResults(false);
        $pageProperty   =   $mainOperation->generatePageProperty($pageNumber, $dataPerPage, $totalNumberData);

        if($totalNumberData > 0){
            $listData   =   $baseData->asObject()->findAll($dataPerPage, ($pageNumber - 1) * $dataPerPage);
            
            foreach($listData as $keyData){
                $idKlien            =   $keyData->IDKLIEN;
                $detailGaleriKlien  =   $galeriKlienModel->getDetailGaleriKlien($idKlien);

                if(!empty($detailGaleriKlien) && !is_null($detailGaleriKlien)){
                    $keyData->DETAILGALERI   =   encodeDatabaseObjectResultKey($detailGaleriKlien, ['IDGALERIKLIEN', 'IDKLIEN', 'IDMERKUTAMA']);
                } else {
                    $keyData->DETAILGALERI   =   [];
                }
            }

            $listData   =   encodeDatabaseObjectResultKey($listData, ['IDKLIEN']);
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
	
	public function uploadLogoKlien(){
		helper(['fileValidation']);
        if (empty($_FILES['file']['tmp_name'])) return throwResponseNotAcceptable("Tidak ada file yang diunggah");

        $fileValidation =   validate_image($_FILES["file"], 1000000);
        if($fileValidation !== true) return $fileValidation;

		$info	    =	getimagesize($_FILES["file"]["tmp_name"]);
		$width	    =	$info[0];
		$height	    =	$info[1];

		if ($width < 100 || $height < 100) {
			return throwResponseNotAcceptable("Ukuran gambar minimal 100 x 100 pixel.");
		}

        if ($width > 800 || $height > 800) {
			return throwResponseNotAcceptable("Ukuran gambar maksimal 800 x 800 pixel.");
		}
		
		$storage	=	StorageFactory::make();
		$dir		=	PATH_STORAGE_PHOTO_GALERI_KLIEN_LOGO;
		$extension	=	pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);
		$filename	=	"klienLogo"."_".date('YmdHis').".".$extension;
		$move		=	$storage->upload($_FILES["file"]["tmp_name"], $dir.$filename);
		
		if($move){
            return $this->setResponseFormat('json')
			->respond([
				"status"    =>  200,
				"urlLogo"   =>  BASE_URL_ASSETS_GALERI_KLIEN_LOGO.$filename,
				"fileName"  =>  $filename,
				"message"   =>  "Berkas berhasil diunggah"
			]);
		} else {
			return throwResponseInternalServerError("Gagal mengunggah berkas. Silakan coba lagi nanti");
		}
	}

    public function saveDataKlien()
    {
        $idKlien    =   $this->request->getVar('idKlien');
        $idKlien    =   $idKlien != "" ? hashidDecode($idKlien) : 0;
        $validation =   $idKlien == 0 ? $this->parametersValidatorKlien() : $this->parametersValidatorKlien(true, $idKlien);
        
        if($validation !== true) return $this->fail($validation);
        
        $mainOperation  =   new MainOperation();
        $namaKlien      =   $this->request->getVar('namaKlien');
        $status         =   $this->request->getVar('status');
        $logoFileName   =   $this->request->getVar('logoFileName');
        $arrInsertUpdate=   [
            'NAMAKLIEN' =>  $namaKlien,
            'STATUS'    =>  $status,
            'LOGO'      =>  $logoFileName
        ];

        if($idKlien == 0){
            $procInsertData =   $mainOperation->insertDataTable(APP_MAIN_DATABASE_CUSTOMER . '.m_klien', $arrInsertUpdate);
            if(!$procInsertData['status']) return switchMySQLErrorCode($procInsertData['errCode']);
        } else {
            $procUpdateData =   $mainOperation->updateDataTable(APP_MAIN_DATABASE_CUSTOMER . '.m_klien', $arrInsertUpdate, ['IDKLIEN' => $idKlien]);
            if(!$procUpdateData['status']) return switchMySQLErrorCode($procUpdateData['errCode']);
        }
                    
        $responseSuccess =   $idKlien == 0 ? 'Data klien telah disimpan' : 'Data klien telah diperbarui';
        return throwResponseOK($responseSuccess);
    }

    private function parametersValidatorKlien($isUpdate = false, $idKlien = null)
    {
        $rules      =   [
            'namaKlien'     =>  ['label' => 'Nama Klien', 'rules' => 'required|string|min_length[2]|max_length[64]'],
            'status'        =>  ['label' => 'Status', 'rules' => 'required|in_list[-1,1]'],
            'logoFileName'  =>  ['label' => 'Logo', 'rules' => 'required|alpha_numeric_punct'],
        ];

        $messages   =   [
            'logoFileName' =>  [
                'required'  =>  'Logo klien harus diunggah'
            ],
            'status'        =>  [
                'required'  =>  'Status klien harus dipilih',
                'in_list'   =>  'Status klien yang dipilih tidak valid'
            ]
        ];

        if($isUpdate) {
            $rules['namaKlien']['rules']    .=  '|is_unique['.APP_MAIN_DATABASE_CUSTOMER_CI_VALIDATION . '.m_klien.NAMAKLIEN, IDKLIEN, '.$idKlien.']';
            $rules['idKlien'] = ['label' => 'ID Klien', 'rules' => 'required|alpha_numeric'];
            $messages['idKlien'] = [
                'required'      =>   'Data kiriman tidak lengkap, silakan periksa kembali',
                'alpha_numeric' =>   'Data kiriman tidak lengkap, silakan periksa kembali'
            ];
        } else {
            $rules['namaKlien']['rules']    .=  '|is_unique['.APP_MAIN_DATABASE_CUSTOMER_CI_VALIDATION . '.m_klien.NAMAKLIEN]';
        }

        if(!$this->validate($rules, $messages)) return $this->validator->getErrors();
        return true;
    }
	
	public function uploadImageGaleriKlien(){
		helper(['fileValidation']);
        if (empty($_FILES['file']['tmp_name'])) return throwResponseNotAcceptable("Tidak ada file yang diunggah");

        $fileValidation =   validate_image($_FILES["file"], 1000000);
        if($fileValidation !== true) return $fileValidation;

		$info	    =	getimagesize($_FILES["file"]["tmp_name"]);
		$width	    =	$info[0];
		$height	    =	$info[1];
        $ratio      =   $width / $height;

		if ($width < 300 || $height < 300) {
			return throwResponseNotAcceptable("Ukuran gambar minimal 300 x 300 pixel.");
		}

        if ($width > 1800 || $height > 1252) {
			return throwResponseNotAcceptable("Ukuran gambar maksimal 1800 x 1252 pixel.");
		}

        if($ratio < 1.33 || $ratio > 1.78){
            return throwResponseNotAcceptable("Rasio gambar harus antara 3:2 atau 16:9 atau 4:3");
        }
		
		$storage	=	StorageFactory::make();
		$dir		=	PATH_STORAGE_PHOTO_GALERI_KLIEN_PROYEK;
		$extension	=	pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);
		$filename	=	"klienGaleriImage"."_".date('YmdHis').".".$extension;
		$move		=	$storage->upload($_FILES["file"]["tmp_name"], $dir.$filename);
		
		if($move){
            return $this->setResponseFormat('json')
			->respond([
				"status"    =>  200,
				"urlImage"  =>  BASE_URL_ASSETS_GALERI_KLIEN_PROYEK.$filename,
				"fileName"  =>  $filename,
				"message"   =>  "Berkas berhasil diunggah"
			]);
		} else {
			return throwResponseInternalServerError("Gagal mengunggah berkas. Silakan coba lagi nanti");
		}
	}

    public function saveDataKlienGaleri()
    {
        $idGaleriKlien  =   $this->request->getVar('idGaleriKlien');
        $idGaleriKlien  =   $idGaleriKlien != "" ? hashidDecode($idGaleriKlien) : 0;
        $validation     =   $idGaleriKlien == 0 ? $this->parametersValidatorGaleri() : $this->parametersValidatorGaleri(true);
        
        if($validation !== true) return $this->fail($validation);
        
        $mainOperation  =   new MainOperation();
        $idMerkUtama    =   $this->request->getVar('idMerkUtama') ? $this->request->getVar('idMerkUtama') : "";
        $idMerkUtama    =   isset($idMerkUtama) && $idMerkUtama != "" ? hashidDecode($idMerkUtama) : "";
        $idKlien        =   $this->request->getVar('idKlien') ? $this->request->getVar('idKlien') : "";
        $idKlien        =   isset($idKlien) && $idKlien != "" ? hashidDecode($idKlien) : "";
        $deskripsi      =   $this->request->getVar('deskripsi');
        $imageFileName  =   $this->request->getVar('imageFileName');
        $arrInsertUpdate=   [
            'IDMERKUTAMA'       =>  $idMerkUtama,
            'IDKLIEN'           =>  $idKlien,
            'DESKRIPSI'         =>  $deskripsi,
            'IMAGE'             =>  json_encode([$imageFileName]),
            'INPUTUSER'         =>  $this->userData->name,
            'INPUTTANGGALWAKTU' =>  $this->currentDateTime
        ];

        if($idGaleriKlien == 0){
            $procInsertData =   $mainOperation->insertDataTable(APP_MAIN_DATABASE_CUSTOMER . '.t_galeriklien', $arrInsertUpdate);
            if(!$procInsertData['status']) return switchMySQLErrorCode($procInsertData['errCode']);
        } else {
            $procUpdateData =   $mainOperation->updateDataTable(APP_MAIN_DATABASE_CUSTOMER . '.t_galeriklien', $arrInsertUpdate, ['IDGALERIKLIEN' => $idGaleriKlien]);
            if(!$procUpdateData['status']) return switchMySQLErrorCode($procUpdateData['errCode']);
        }
                    
        $responseSuccess =   $idGaleriKlien == 0 ? 'Data galeri telah disimpan' : 'Data galeri telah diperbarui';
        return throwResponseOK($responseSuccess);
    }

    private function parametersValidatorGaleri($isUpdate = false)
    {
        $rules      =   [
            'idMerkUtama'   =>  ['label' => 'Merk', 'rules' => 'required|alpha_numeric'],
            'idKlien'       =>  ['label' => 'Klien', 'rules' => 'required|alpha_numeric'],
            'deskripsi'     =>  ['label' => 'Deskripsi', 'rules' => 'required|string|min_length[1]|max_length[255]'],
            'imageFileName' =>  ['label' => 'Logo', 'rules' => 'required|alpha_numeric_punct'],
        ];

        $messages   =   [
            'idMerkUtama'   =>  [
                'required'      =>  'Harap pilih merk utama',
                'alpha_numeric' =>  'Merk yang dipilih tidak valid'
            ],
            'idKlien'       =>  [
                'required'      =>  'Data kiriman tidak valid, silakan coba lagi nanti',
                'alpha_numeric' =>  'Data kiriman tidak valid, silakan coba lagi nanti'
            ],
            'imageFileName' =>  [
                'required'  =>  'Logo merk harus diunggah'
            ]
        ];

        if($isUpdate) {
            $rules['idGaleriKlien']['rules']            =   'required|alpha_numeric';
            $messages['idGaleriKlien']['required']      =   'Data kiriman tidak lengkap, silakan periksa kembali';
            $messages['idGaleriKlien']['alpha_numeric'] =   'Data kiriman tidak lengkap, silakan periksa kembali';
        }

        if(!$this->validate($rules, $messages)) return $this->validator->getErrors();
        return true;
    }
}

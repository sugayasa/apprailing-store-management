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

        $dataReturn     =   [
            "listData"                      =>  [],
            "pageProperty"                  =>  $pageProperty,
            "urlAssetLogoMerk"              =>  BASE_URL_ASSETS_CUSTOMER_MERK,
            "urlAssetPdfKatalogThumbnail"   =>  BASE_URL_ASSETS_PDF_KATALOG_THUMBNAIL,
            "urlAssetPdfKatalogFile"        =>  BASE_URL_ASSETS_PDF_KATALOG_FILE
        ];

        if($totalNumberData > 0){
            $listData               =   $baseData->asObject()->findAll($dataPerPage, ($pageNumber - 1) * $dataPerPage);
            $listData               =   encodeDatabaseObjectResultKey($listData, ['IDMERK']);
            $dataReturn["listData"] =   $listData;

            return $this->setResponseFormat('json')->respond($dataReturn);
        } else {
            return throwResponseNotFound('Tidak ada data yang ditemukan', $dataReturn);
        }
    }
	
	public function uploadLogo(){
		helper(['fileValidation']);
		validate_image($_FILES["file"], 1000000);

		$info	    =	getimagesize($_FILES["file"]["tmp_name"]);
		$width	    =	$info[0];
		$height	    =	$info[1];

        if ($width > 500 || $height > 500) {
			return throwResponseNotAcceptable("Ukuran gambar maksimal 500 x 500 pixel.");
		}
		
		$storage	=	StorageFactory::make();
		$dir		=	PATH_STORAGE_CUSTOMER_MERK;
		$extension	=	pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);
		$filename	=	"LogoMerk"."_".date('YmdHis').".".$extension;
		$move		=	$storage->upload($_FILES["file"]["tmp_name"], $dir.$filename);
		
		if($move){
            return $this->setResponseFormat('json')
			->respond([
				"status"    =>  200,
				"urlLogo"   =>  BASE_URL_ASSETS_CUSTOMER_MERK.$filename,
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
	
	public function uploadPdfKatalogThumbnail(){
		helper(['fileValidation']);
		validate_image($_FILES["file"], 1000000);

		$info	    =	getimagesize($_FILES["file"]["tmp_name"]);
		$width	    =	$info[0];
		$height	    =	$info[1];
		$ratio	    =	$width / $height;

		if ($width < 250 || $height < 350) {
			return throwResponseNotAcceptable("Ukuran gambar minimal 250 x 350 pixel.");
		}

        if ($width > 1000 || $height > 1400) {
			return throwResponseNotAcceptable("Ukuran gambar maksimal 1000 x 1400 pixel.");
		}

		if (abs($ratio - (5/7)) > 0.01) {
			return throwResponseNotAcceptable("Rasio gambar harus 5:7 (lebar : tinggi).");
		}
		
		$storage	=	StorageFactory::make();
		$dir		=	PATH_STORAGE_FILE_PDF_KATALOG_THUMBNAIL;
		$extension	=	pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);
		$filename	=	"PdfKatalogThumbnail"."_".date('YmdHis').".".$extension;
		$move		=	$storage->upload($_FILES["file"]["tmp_name"], $dir.$filename);
		
		if($move){
            return $this->setResponseFormat('json')
			->respond([
				"status"                    =>  200,
				"urlPdfKatalogThumbnail"    =>  BASE_URL_ASSETS_PDF_KATALOG_THUMBNAIL.$filename,
				"pdfKatalogThumbnailName"   =>  $filename,
				"message"                   =>  "Berkas berhasil diunggah"
			]);
		} else {
			return throwResponseInternalServerError("Gagal mengunggah berkas. Silakan coba lagi nanti");
		}
	}
	
	public function uploadPdfKatalogFile(){
		helper(['fileValidation']);
		validate_pdf($_FILES["file"], 10000000);
		
		$storage	=	StorageFactory::make();
		$dir		=	PATH_STORAGE_FILE_PDF_KATALOG_FILE;
		$extension	=	pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);
		$filename	=	"PdfKatalogFile"."_".date('YmdHis').".".$extension;
		$move		=	$storage->upload($_FILES["file"]["tmp_name"], $dir.$filename);
		
		if($move){
            return $this->setResponseFormat('json')
			->respond([
				"status"            =>  200,
				"urlPdfKatalogFile" =>  BASE_URL_ASSETS_PDF_KATALOG_FILE.$filename,
				"pdfKatalogFileName"=>  $filename,
				"message"           =>  "Berkas berhasil diunggah"
			]);
		} else {
			return throwResponseInternalServerError("Gagal mengunggah berkas. Silakan coba lagi nanti");
		}
	}

    public function saveDataKatalog()
    {
        $rules      =   [
            'idMerk'            =>  ['label' => 'ID Merk', 'rules' => 'required|alpha_numeric'],
            'pdfThumbnailName'  =>  ['label' => 'Gambar Thumbnail', 'rules' => 'required|alpha_numeric_punct'],
            'pdfFileName'       =>  ['label' => 'File PDF', 'rules' => 'required|alpha_numeric_punct'],
            'status'            =>  ['label' => 'Status', 'rules' => 'required|in_list[0,1]']
        ];

        $messages   =   [
            'idMerk'        =>  [
                'required'      =>  'Data kiriman tidak lengkap, silakan periksa kembali',
                'alpha_numeric' =>  'Data kiriman tidak valid, silakan periksa kembali'
            ],
            'pdfThumbnailName'  =>  [
                'required'  =>  'Gambar thumbnail harus diunggah'
            ],
            'pdfFileName'   =>  [
                'required'  =>  'File PDF harus diunggah'
            ],
            'status'        =>  [
                'required'  =>  'Status katalog harus dipilih',
                'in_list'   =>  'Status katalog yang dipilih tidak valid'
            ]
        ];

        if(!$this->validate($rules, $messages)) return $this->fail($this->validator->getErrors());

        $mainOperation      =   new MainOperation();
        $idMerk             =   $this->request->getVar('idMerk');
        $idMerk             =   $idMerk != "" ? hashidDecode($idMerk) : 0;
        $pdfThumbnailName   =   $this->request->getVar('pdfThumbnailName');
        $pdfFileName        =   $this->request->getVar('pdfFileName');
        $status             =   $this->request->getVar('status');

        $arrUpdateKatalog   =   [
            'PDFTHUMBNAIL'  =>  $pdfThumbnailName,
            'PDFFILE'       =>  $pdfFileName,
            'STATUSKATALOG' =>  $status
        ];

        $procUpdateData     =   $mainOperation->updateDataTable(APP_MAIN_DATABASE_CUSTOMER . '.m_merk', $arrUpdateKatalog, ['IDMERK' => $idMerk]);
        if(!$procUpdateData['status']) return switchMySQLErrorCode($procUpdateData['errCode']);
                    
        return throwResponseOK("Data detail katalog merk telah diperbarui");
    }
}
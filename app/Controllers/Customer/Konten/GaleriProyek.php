<?php

namespace App\Controllers\Customer\Konten;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Models\MainOperation;
use App\Models\Customer\Konten\GaleriProyekModel;
use App\Libraries\StorageFactory;

class GaleriProyek extends ResourceController
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
        $rules     =   [
            'idMerk'    =>  ['label' => 'Merk', 'rules' => 'permit_empty|alpha_numeric']
        ];

        $messages   =   [
            'idMerk'    =>  [
                'alpha_numeric' =>  'Merk yang dipilih tidak valid'
            ]
        ];

        if(!$this->validate($rules, $messages)) return $this->fail($this->validator->getErrors());

        $mainOperation      =   new MainOperation();
        $galeriProyekModel  =   new GaleriProyekModel();

        $idMerk         =   $this->request->getVar('idMerk') ? $this->request->getVar('idMerk') : "";
        $idMerk         =   isset($idMerk) && $idMerk != "" ? hashidDecode($idMerk) : "";
        $pageNumber     =   $this->request->getVar('pageNumber') ? (int)$this->request->getVar('pageNumber') : 1;
        $dataPerPage    =   $this->request->getVar('dataPerPage') ? (int)$this->request->getVar('dataPerPage') : 8;
        $baseData       =   $galeriProyekModel->getDataGeleriProyek($idMerk);
        $totalNumberData=   $baseData->countAllResults(false);
        $pageProperty   =   $mainOperation->generatePageProperty($pageNumber, $dataPerPage, $totalNumberData);

        if($totalNumberData > 0){
            $listData   =   $baseData->asObject()->findAll($dataPerPage, ($pageNumber - 1) * $dataPerPage);
            $listData   =   encodeDatabaseObjectResultKey($listData, ['IDGALERIPROYEK', 'IDMERKUTAMA']);

            return $this->setResponseFormat('json')->respond([
                "listData"                  =>  $listData,
                "pageProperty"              =>  $pageProperty,
                "urlAssetImageGaleryProyek" =>  BASE_URL_ASSETS_GALERI_PROYEK
            ]);
        } else {
            $dataReturn =   [
                "listData"                  =>  [],
                "pageProperty"              =>  $pageProperty,
                "urlAssetImageGaleryProyek" =>  BASE_URL_ASSETS_GALERI_PROYEK
            ];
            return throwResponseNotFound('Tidak ada data yang ditemukan', $dataReturn);
        }
    }
	
	public function uploadImage(){
		helper(['fileValidation']);
		validate_image($_FILES["file"], 1000000);

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
		
		$storage	=	StorageFactory::make();
		$dir		=	PATH_STORAGE_PHOTO_GALERI_PROYEK;
		$extension	=	pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);
		$filename	=	"galeriProyek"."_".date('YmdHis').".".$extension;
		$move		=	$storage->upload($_FILES["file"]["tmp_name"], $dir.$filename);
		
		if($move){
            return $this->setResponseFormat('json')
			->respond([
				"status"    =>  200,
				"urlLogo"   =>  BASE_URL_ASSETS_GALERI_PROYEK.$filename,
				"fileName"  =>  $filename,
				"message"   =>  "Berkas berhasil diunggah"
			]);
		} else {
			return throwResponseInternalServerError("Gagal mengunggah berkas. Silakan coba lagi nanti");
		}
	}

    public function saveData()
    {
        $idGaleriProyek =   $this->request->getVar('idGaleriProyek');
        $idGaleriProyek =   $idGaleriProyek != "" ? hashidDecode($idGaleriProyek) : 0;
        $validation     =   $idGaleriProyek == 0 ? $this->parametersValidator() : $this->parametersValidator(true, $idGaleriProyek);
        
        if($validation !== true) return $this->fail($validation);
        
        $mainOperation  =   new MainOperation();
        $idMerkUtama    =   $this->request->getVar('idMerkUtama') ? $this->request->getVar('idMerkUtama') : "";
        $idMerkUtama    =   isset($idMerkUtama) && $idMerkUtama != "" ? hashidDecode($idMerkUtama) : "";
        $namaKlien      =   $this->request->getVar('namaKlien');
        $alamatProyek   =   $this->request->getVar('alamatProyek');
        $deskripsi      =   $this->request->getVar('deskripsi');
        $status         =   $this->request->getVar('status');
        $imageFileName  =   $this->request->getVar('imageFileName');
        $arrImage       =   [$imageFileName];
        $arrInsertUpdate=   [
            'IDMERKUTAMA'       =>  $idMerkUtama,
            'NAMAKLIEN'         =>  $namaKlien,
            'ALAMATPROYEK'      =>  $alamatProyek,
            'DESKRIPSI'         =>  $deskripsi,
            'IMAGE'             =>  json_encode($arrImage),
            'INPUTUSER'         =>  $this->userData->name,
            'INPUTTANGGALWAKTU' =>  $this->currentDateTime,
            'STATUS'            =>  $status
        ];

        if($idGaleriProyek == 0){
            $galeriProyekModel          =   new GaleriProyekModel();
            $urutanTerakhir             =   (int)$galeriProyekModel->selectMax('URUTAN')->get()->getRow()->URUTAN ?? 0;
            $arrInsertUpdate['URUTAN']  =   $urutanTerakhir + 1;
            $procInsertData             =   $mainOperation->insertDataTable(APP_MAIN_DATABASE_CUSTOMER . '.t_galeriproyek', $arrInsertUpdate);
            if(!$procInsertData['status']) return switchMySQLErrorCode($procInsertData['errCode']);
        } else {
            $procUpdateData =   $mainOperation->updateDataTable(APP_MAIN_DATABASE_CUSTOMER . '.t_galeriproyek', $arrInsertUpdate, ['IDGALERIPROYEK' => $idGaleriProyek]);
            if(!$procUpdateData['status']) return switchMySQLErrorCode($procUpdateData['errCode']);
        }
                    
        $responseSuccess =   $idGaleriProyek == 0 ? 'Data galeri proyek telah disimpan' : 'Data galeri proyek telah diperbarui';
        return throwResponseOK($responseSuccess);
    }

    private function parametersValidator($isUpdate = false, $idGaleriProyek = null)
    {
        $rules      =   [
            'idMerkUtama'   =>  ['label' => 'Merk', 'rules' => 'required|alpha_numeric'],
            'namaKlien'     =>  ['label' => 'Nama Klien', 'rules' => 'required|string|min_length[2]|max_length[75]'],
            'alamatProyek'  =>  ['label' => 'Alamat Proyek', 'rules' => 'required|string|min_length[2]|max_length[100]'],
            'deskripsi'     =>  ['label' => 'Deskripsi', 'rules' => 'required|string|min_length[1]|max_length[255]'],
            'status'        =>  ['label' => 'Status', 'rules' => 'required|in_list[-1,1]'],
            'imageFileName' =>  ['label' => 'Logo', 'rules' => 'required|alpha_numeric_punct'],
        ];

        $messages   =   [
            'idMerkUtama'   =>  [
                'required'      =>  'Harap pilih merk utama',
                'alpha_numeric' =>  'Merk yang dipilih tidak valid'
            ],
            'imageFileName' =>  [
                'required'  =>  'Logo merk harus diunggah'
            ],
            'status'        =>  [
                'required'  =>  'Status merk harus dipilih',
                'in_list'   =>  'Status merk yang dipilih tidak valid'
            ]
        ];

        if($isUpdate) {
            $rules['idGaleriProyek']['rules']           =   'required|alpha_numeric';
            $messages['idGaleriProyek']['required']     =   'Data kiriman tidak lengkap, silakan periksa kembali';
            $messages['idGaleriProyek']['alpha_numeric']=   'Data kiriman tidak lengkap, silakan periksa kembali';
        }

        if(!$this->validate($rules, $messages)) return $this->validator->getErrors();
        return true;
    }
}

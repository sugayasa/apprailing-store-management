<?php

namespace App\Controllers\Customer\DataDasar;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Models\MainOperation;
use App\Models\Customer\DataDasar\DaftarMarketingModel;
use App\Libraries\StorageFactory;

class DaftarMarketing extends ResourceController
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
        $daftarMarketingModel   =   new DaftarMarketingModel();
        $dataMarketing          =   $daftarMarketingModel->getDataMarketing();

        if(is_null($dataMarketing)) return $this->failNotFound('Tidak ada data yang ditemukan');

        $listData   =   encodeDatabaseObjectResultKey($dataMarketing, ['IDMARKETING']);
        return $this->setResponseFormat('json')->respond(['listData' => $listData]);
    }
	
	public function uploadImageMarketing(){
		helper(['fileValidation']);
        if (empty($_FILES['file']['tmp_name'])) return throwResponseNotAcceptable("Tidak ada file yang diunggah");
		validate_image($_FILES["file"], 1000000);

		$info	    =	getimagesize($_FILES["file"]["tmp_name"]);
		$width	    =	$info[0];
		$height	    =	$info[1];

		if ($width !== $height || $width < 200 || $height < 200) {
			return throwResponseNotAcceptable("Gambar harus berbentuk persegi (lebar = tinggi) dengan minimal ukuran 200px x 200px.");
		}

        if ($width > 500 || $height > 500) {
            return throwResponseNotAcceptable("Ukuran gambar maksimal 500px x 500px.");
        }
		
		$storage	=	StorageFactory::make();
		$dir		=	PATH_STORAGE_IMAGE_MARKETING;
		$extension	=	pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);
		$filename	=	"marketing"."_".date('YmdHis').".".$extension;
		$move		=	$storage->upload($_FILES["file"]["tmp_name"], $dir.$filename);
		
		if($move){
            return $this->setResponseFormat('json')
			->respond([
				"status"    =>  200,
				"urlImage"  =>  BASE_URL_ASSETS_IMAGE_MARKETING.$filename,
				"fileName"  =>  $filename,
				"message"   =>  "Berkas berhasil diunggah"
			]);
		} else {
			return throwResponseInternalServerError("Gagal mengunggah berkas. Silakan coba lagi nanti");
		}
	}

    public function saveData()
    {
        $rules      =   [
            'idMarketing'           =>  ['label' => 'Marketing', 'rules' => 'required|alpha_numeric'],
            'marketingImageFileName'=>  ['label' => 'Foto Marketing', 'rules' => 'required|string']
        ];

        $messages   =   [
            'idMarketing'   =>  [
                'required'      =>  'Data kiriman tidak valid',
                'alpha_numeric' =>  'Data kiriman tidak valid'
            ],
            'marketingImageFileName' =>  [
                'required'  =>  'Foto marketing belum diunggah',
                'string'    =>  'Foto marketing yang diunggah tidak valid.'
            ]
        ];

        if(!$this->validate($rules, $messages)) return $this->fail($this->validator->getErrors());

        $mainOperation  =   new MainOperation();
        $idMarketing    =   $this->request->getVar('idMarketing');
        $idMarketing    =   $idMarketing != "" ? hashidDecode($idMarketing) : 0;
        $imageFileName  =   $this->request->getVar('marketingImageFileName');
        $arrUpdate      =   [
            'IMAGE' =>  $imageFileName
        ];

        $procUpdateData =   $mainOperation->updateDataTable(APP_MAIN_DATABASE_CUSTOMER . '.m_marketing', $arrUpdate, ['IDMARKETING' => $idMarketing]);
        if(!$procUpdateData['status']) return switchMySQLErrorCode($procUpdateData['errCode']);

        return throwResponseOK("Data marketing telah diperbarui");
    }
}

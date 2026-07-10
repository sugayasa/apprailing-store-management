<?php

namespace App\Controllers\Customer\DataDasar;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Models\MainOperation;
use App\Models\Customer\DataDasar\SosmedMarketplaceModel;
use App\Libraries\StorageFactory;

class SosmedMarketplace extends ResourceController
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
        $sosmedMarketplaceModel =   new SosmedMarketplaceModel();

        $pageNumber     =   $this->request->getVar('pageNumber') ? (int)$this->request->getVar('pageNumber') : 1;
        $dataPerPage    =   $this->request->getVar('dataPerPage') ? (int)$this->request->getVar('dataPerPage') : 100;
        $baseData       =   $sosmedMarketplaceModel->getDataSosmedMarketplace();
        $totalNumberData=   $baseData->countAllResults(false);
        $pageProperty   =   $mainOperation->generatePageProperty($pageNumber, $dataPerPage, $totalNumberData);

        $dataReturn     =   [
            "listData"      =>  [],
            "pageProperty"  =>  $pageProperty,
            "urlAssetIcon"  =>  BASE_URL_ASSETS_CUSTOMER_SOSMED_MARKETPLACE
        ];

        if($totalNumberData > 0){
            $listData               =   $baseData->asObject()->findAll($dataPerPage, ($pageNumber - 1) * $dataPerPage);

            foreach ($listData as $keyData) {
                $idTipeSosmedMarketplace    =   $keyData->IDTIPESOSMEDMARKETPLACE;
                $listAkunSosmedMarketplace  =   $sosmedMarketplaceModel->getDataSosmedMarketplaceByIdTipe($idTipeSosmedMarketplace);
                $keyData->LISTAKUN          =   count($listAkunSosmedMarketplace) > 0 ? encodeDatabaseObjectResultKey($listAkunSosmedMarketplace, ['IDSOSMEDMARKETPLACE']) : [];
            }

            $listData               =   encodeDatabaseObjectResultKey($listData, ['IDTIPESOSMEDMARKETPLACE']);
            $dataReturn["listData"] =   $listData;

            return $this->setResponseFormat('json')->respond($dataReturn);
        } else {
            return throwResponseNotFound('Tidak ada data yang ditemukan', $dataReturn);
        }
    }
	
	public function uploadIcon(){
		helper(['fileValidation']);
        if (empty($_FILES['file']['tmp_name'])) return throwResponseNotAcceptable("Tidak ada file yang diunggah");
        $fileValidation =   validate_image($_FILES["file"], 1000000);
        if($fileValidation !== true) return $fileValidation;

		$info	    =	getimagesize($_FILES["file"]["tmp_name"]);
		$width	    =	$info[0];
		$height	    =	$info[1];
		$ratio	    =	$width / $height;

		if ($width < 100 || $height < 100) {
			return throwResponseNotAcceptable("Ukuran gambar minimal 100 x 100 pixel.");
		}

        if ($width > 500 || $height > 500) {
			return throwResponseNotAcceptable("Ukuran gambar maksimal 500 x 500 pixel.");
		}

		if (abs($ratio - 1) > 0.01) {
			return throwResponseNotAcceptable("Rasio gambar harus 1:1 (lebar : tinggi).");
		}
		
		$storage	=	StorageFactory::make();
		$dir		=	PATH_STORAGE_CUSTOMER_SOSMED_MARKETPLACE;
		$extension	=	pathinfo($_FILES["file"]["name"], PATHINFO_EXTENSION);
		$filename	=	"SosMP"."_".date('YmdHis').".".$extension;
		$move		=	$storage->upload($_FILES["file"]["tmp_name"], $dir.$filename);
		
		if($move){
            return $this->setResponseFormat('json')
			->respond([
				"status"    =>  200,
				"urlIcon"   =>  BASE_URL_ASSETS_CUSTOMER_SOSMED_MARKETPLACE.$filename,
				"fileName"  =>  $filename,
				"message"   =>  "Berkas berhasil diunggah"
			]);
		} else {
			return throwResponseInternalServerError("Gagal mengunggah berkas. Silakan coba lagi nanti");
		}
	}

    public function saveDataTipe()
    {
        $idTipeSosmedMarketplace=   $this->request->getVar('idTipeSosmedMarketplace');
        $idTipeSosmedMarketplace=   $idTipeSosmedMarketplace != "" ? hashidDecode($idTipeSosmedMarketplace) : 0;
        $validation             =   $idTipeSosmedMarketplace == 0 ? $this->parametersValidatorTipe() : $this->parametersValidatorTipe(true, $idTipeSosmedMarketplace);
        
        if($validation !== true) return $this->fail($validation);
        
        $mainOperation              =   new MainOperation();
        $namaTipeSosmedMarketplace  =   $this->request->getVar('namaTipeSosmedMarketplace');
        $iconFileName               =   $this->request->getVar('iconFileName');
        $status                     =   $this->request->getVar('status');
        $arrInsertUpdate=   [
            'NAMATIPE'  =>  $namaTipeSosmedMarketplace,
            'FILEICON'  =>  $iconFileName,
            'STATUS'    =>  $status
        ];

        if($idTipeSosmedMarketplace == 0){
            $sosmedMarketplaceModel =   new SosmedMarketplaceModel();
            $lastUrutan                 =   $sosmedMarketplaceModel->selectMax('URUTAN')->get()->getRow();
            $nextUrutan                 =   ($lastUrutan && $lastUrutan->URUTAN !== null) ? (int)$lastUrutan->URUTAN + 1 : 1;
            $arrInsertUpdate['URUTAN']  =   $nextUrutan;
            $procInsertData             =   $mainOperation->insertDataTable(APP_MAIN_DATABASE_CUSTOMER . '.m_tipesosmedmarketplace', $arrInsertUpdate);
            if(!$procInsertData['status']) return switchMySQLErrorCode($procInsertData['errCode']);
        } else {
            $procUpdateData =   $mainOperation->updateDataTable(APP_MAIN_DATABASE_CUSTOMER . '.m_tipesosmedmarketplace', $arrInsertUpdate, ['IDTIPESOSMEDMARKETPLACE' => $idTipeSosmedMarketplace]);
            if(!$procUpdateData['status']) return switchMySQLErrorCode($procUpdateData['errCode']);
        }
                    
        $responseSuccess =   $idTipeSosmedMarketplace == 0 ? 'Data tipe sosmed / marketplace telah disimpan' : 'Data tipe sosmed / marketplace telah diperbarui';
        return throwResponseOK($responseSuccess);
    }

    private function parametersValidatorTipe($isUpdate = false, $idTipeSosmedMarketplace = null)
    {
        $rules      =   [
            'namaTipeSosmedMarketplace' =>  ['label' => 'Nama Sosmed Marketplace', 'rules' => 'required|alpha_numeric_punct|min_length[3]|max_length[20]'],
            'iconFileName'              =>  ['label' => 'Icon', 'rules' => 'required|alpha_numeric_punct'],
            'status'                    =>  ['label' => 'Status', 'rules' => 'required|in_list[-1,1]']
        ];

        $messages   =   [
            'iconFileName'  =>  [
                'required'  =>  'Icon sosmed / marketplace harus diunggah'
            ],
            'status'        =>  [
                'required'  =>  'Status sosmed / marketplace harus dipilih',
                'in_list'   =>  'Status sosmed / marketplace yang dipilih tidak valid'
            ]
        ];

        if($isUpdate) {
            $rules['namaTipeSosmedMarketplace']['rules']           .=  '|is_unique['.APP_MAIN_DATABASE_CUSTOMER_CI_VALIDATION . '.m_tipesosmedmarketplace.NAMATIPE, IDTIPESOSMEDMARKETPLACE, '.$idTipeSosmedMarketplace.']';
            $rules['idTipeSosmedMarketplace']['rules']             =   'required|alpha_numeric';
            $messages['idTipeSosmedMarketplace']['required']       =   'Data kiriman tidak lengkap, silakan periksa kembali';
            $messages['idTipeSosmedMarketplace']['alpha_numeric']  =   'Data kiriman tidak lengkap, silakan periksa kembali';
        } else {
            $rules['namaTipeSosmedMarketplace']['rules']           .=  '|is_unique['.APP_MAIN_DATABASE_CUSTOMER_CI_VALIDATION . '.m_tipesosmedmarketplace.NAMATIPE]';
        }

        if(!$this->validate($rules, $messages)) return $this->validator->getErrors();
        return true;
    }

    public function saveUrutanTipeSosmedMarketplace()
    {
        $rules      =   [
            'arrUrutanTipe.*'   =>  ['label' => 'Urutan', 'rules' => 'required|alpha_numeric']
        ];

        $messages   =   [
            'arrUrutanTipe.*'   =>  [
                'required'      =>  'Urutan tidak valid',
                'alpha_numeric' =>  'Urutan tidak valid'
            ]
        ];

        if(!$this->validate($rules, $messages)) return $this->fail($this->validator->getErrors());
        
        $arrUrutanTipe  =   $this->request->getVar('arrUrutanTipe');
        
        if(!is_array($arrUrutanTipe) || count($arrUrutanTipe) <= 0) return throwResponseNotAcceptable("Data kiriman tidak valid, harap ulangi lagi nanti");
        
        $mainOperation  =   new MainOperation();
        $urutanTipe     =   1;

        foreach($arrUrutanTipe as $idTipeSosmedMarketplace){
            $idTipeSosmedMarketplace=   hashidDecode($idTipeSosmedMarketplace);

            if($idTipeSosmedMarketplace && is_numeric($idTipeSosmedMarketplace)){
                $arrUpdateUrutan    =   ['URUTAN' => $urutanTipe];
                $mainOperation->updateDataTable(APP_MAIN_DATABASE_CUSTOMER . '.m_tipesosmedmarketplace', $arrUpdateUrutan, ['IDTIPESOSMEDMARKETPLACE' => $idTipeSosmedMarketplace]);
                $urutanTipe++;
            }
        }

        return throwResponseOK("Urutan tipe sosmed / marketplace tersimpan");
    }

    public function saveDataAkun()
    {
        $idSosmedMarketplace=   $this->request->getVar('idSosmedMarketplace');
        $idSosmedMarketplace=   $idSosmedMarketplace != "" ? hashidDecode($idSosmedMarketplace) : 0;
        $validation         =   $idSosmedMarketplace == 0 ? $this->parametersValidatorAkun() : $this->parametersValidatorAkun(true);
        
        if($validation !== true) return $this->fail($validation);
        
        $mainOperation          =   new MainOperation();
        $idTipeSosmedMarketplace=   $this->request->getVar('idTipeSosmedMarketplace');
        $idTipeSosmedMarketplace=   $idTipeSosmedMarketplace != "" ? hashidDecode($idTipeSosmedMarketplace) : 0;
        $namaAkun               =   $this->request->getVar('namaAkun');
        $urlAkun                =   $this->request->getVar('urlAkun');
        $arrInsertUpdate        =   [
            'IDTIPESOSMEDMARKETPLACE'   =>  $idTipeSosmedMarketplace,
            'NAMAAKUN'                  =>  $namaAkun,
            'URL'                       =>  $urlAkun
        ];

        if($idSosmedMarketplace == 0){
            $procInsertData =   $mainOperation->insertDataTable(APP_MAIN_DATABASE_CUSTOMER . '.t_sosmedmarketplace', $arrInsertUpdate);
            if(!$procInsertData['status']) return switchMySQLErrorCode($procInsertData['errCode']);
        } else {
            $procUpdateData =   $mainOperation->updateDataTable(APP_MAIN_DATABASE_CUSTOMER . '.t_sosmedmarketplace', $arrInsertUpdate, ['IDSOSMEDMARKETPLACE' => $idSosmedMarketplace]);
            if(!$procUpdateData['status']) return switchMySQLErrorCode($procUpdateData['errCode']);
        }
                    
        $responseSuccess =   $idSosmedMarketplace == 0 ? 'Data akun sosmed / marketplace telah disimpan' : 'Data akun sosmed / marketplace telah diperbarui';
        return throwResponseOK($responseSuccess);
    }

    private function parametersValidatorAkun($isUpdate = false)
    {
        $rules      =   [
            'idTipeSosmedMarketplace'   =>  ['label' => 'Tipe Sosmed Marketplace', 'rules' => 'required|alpha_numeric'],
            'namaAkun'                  =>  ['label' => 'Nama Akun', 'rules' => 'required|alpha_numeric_punct|min_length[3]|max_length[50]'],
            'urlAkun'                   =>  ['label' => 'URL Akun', 'rules' => 'required|valid_url']
        ];

        $messages   =   [
            'idTipeSosmedMarketplace'   =>  [
                'required'              =>  'Data kiriman tidak valid, silakan coba lagi nanti',
            ]
        ];

        if($isUpdate) {
            $rules['idSosmedMarketplace']['rules']             =   'required|alpha_numeric';
            $messages['idSosmedMarketplace']['required']       =   'Data kiriman tidak lengkap, silakan periksa kembali';
            $messages['idSosmedMarketplace']['alpha_numeric']  =   'Data kiriman tidak lengkap, silakan periksa kembali';
        }

        if(!$this->validate($rules, $messages)) return $this->validator->getErrors();
        return true;
    }

    public function deleteDataAkun()
    {
        $rules      =   [
            'idSosmedMarketplace'   =>  ['label' => 'Id Akun', 'rules' => 'required|alpha_numeric']
        ];

        $messages   =   [
            'idSosmedMarketplace'   =>  [
                'required'      =>  'Data akun sosmed / marketplace tidak valid, coba lagi nanti',
                'alpha_numeric' =>  'Data akun sosmed / marketplace tidak valid, coba lagi nanti'
            ]
        ];

        if(!$this->validate($rules, $messages)) return $this->fail($this->validator->getErrors());
        
        $mainOperation      =   new MainOperation();
        $idSosmedMarketplace=   $this->request->getVar('idSosmedMarketplace');
        $idSosmedMarketplace=   hashidDecode($idSosmedMarketplace);

        if(!$idSosmedMarketplace || !is_numeric($idSosmedMarketplace)) return $this->fail("Data akun sosmed / marketplace tidak valid, coba lagi nanti");

        $procDeleteData     =   $mainOperation->deleteDataTable(APP_MAIN_DATABASE_CUSTOMER . '.t_sosmedmarketplace', ['IDSOSMEDMARKETPLACE' => $idSosmedMarketplace]);
        if(!$procDeleteData['status']) return switchMySQLErrorCode($procDeleteData['errCode']);

        return throwResponseOK("Data akun sosmed / marketplace telah dihapus");
    }
}

<?php

namespace App\Controllers\Pengaturan;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Models\MainOperation;
use App\Models\Pengaturan\DaftarPenggunaModel;

class DaftarPengguna extends ResourceController
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
        $daftarPenggunaModel=   new DaftarPenggunaModel();

         $rules     =   [
            'searchKeyword' =>  ['label' => 'Nama Merk', 'rules' => 'permit_empty|alpha_numeric_punct']
        ];

        $messages   =   [];

        if(!$this->validate(array_merge($rules, APP_PAGE_PROPERTY_DEFAULT_RULES), array_merge($messages, APP_PAGE_PROPERTY_DEFAULT_MESSAGES))) return $this->fail($this->validator->getErrors());

        $pageNumber     =   $this->request->getVar('pageNumber') ? (int)$this->request->getVar('pageNumber') : 1;
        $dataPerPage    =   $this->request->getVar('dataPerPage') ? (int)$this->request->getVar('dataPerPage') : 20;
        $searchKeyword  =   $this->request->getVar('searchKeyword');
        $baseData       =   $daftarPenggunaModel->getDataPengguna($searchKeyword);
        $totalNumberData=   $baseData->countAllResults(false);
        $pageProperty   =   $mainOperation->generatePageProperty($pageNumber, $dataPerPage, $totalNumberData);

        if($totalNumberData > 0){
            $listData   =   $baseData->asObject()->findAll($dataPerPage, ($pageNumber - 1) * $dataPerPage);
            $listData   =   encodeDatabaseObjectResultKey($listData, ['IDUSERADMIN', 'IDUSERADMINLEVEL']);

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

    public function saveData()
    {
        $rules      =   [
            'nama'  =>  ['label' => 'Nama', 'rules' => 'required|string'],
            'level' =>  ['label' => 'Level User', 'rules' => 'required|alpha_numeric'],
            'status'=>  ['label' => 'Status', 'rules' => 'required|in_list[1,-1]']
        ];

        $messages   =   [
            'level' =>  ['alpha_numeric' => 'Level yang dipilih tidak valid'],
            'status'=>  ['in_list' => 'Status yang dipilih tidak valid']
        ];
        if(!$this->validate($rules, $messages)) return $this->fail($this->validator->getErrors());

        $idPengguna =   $this->request->getVar('idPengguna');
        $idPengguna =   $idPengguna != "" ? hashidDecode($idPengguna) : 0;

        return $idPengguna == 0 ? $this->insertData() : $this->updateData($idPengguna);
    }

    private function insertData()
    {
        $rules      =   [
            'email'             =>  ['label' => 'Email', 'rules' => 'required|valid_email|is_unique[m_useradmin.EMAIL]'],
            'username'          =>  ['label' => 'Username', 'rules' => 'required|alpha_numeric|min_length[5]|is_unique[m_useradmin.USERNAME]'],
            'password'          =>  ['label' => 'Password Baru', 'rules' => 'required|alpha_numeric|min_length[6]'],
            'konfirmasiPassword'=>  ['label' => 'Konfirmasi/Pengulangan Password', 'rules' => 'required|alpha_numeric|min_length[6]']
        ];

        $messages   =   [
            'email'     =>  ['is_unique' => 'Email ini sudah digunakan, silahkan gunakan email lain'],
            'username'  =>  ['is_unique' => 'Username ini sudah digunakan, silahkan gunakan username lain']
        ];
        if(!$this->validate($rules, $messages)) return $this->fail($this->validator->getErrors());

        $idLevelUser        =   $this->request->getVar('level');
        $idLevelUser        =   hashidDecode($idLevelUser);
        $nama               =   $this->request->getVar('nama');
        $email              =   $this->request->getVar('email');
        $username           =   $this->request->getVar('username');
        $password           =   $this->request->getVar('password');
        $konfirmasiPassword =   $this->request->getVar('konfirmasiPassword');
        $status             =   $this->request->getVar('status');

        if($password != $konfirmasiPassword) return throwResponseNotAcceptable("Pengulangan password yang Anda masukkan tidak sama");

        $arrInsertData  =   [
            'IDUSERADMINLEVEL'  =>  $idLevelUser,
            'NAME'              =>  $nama,
            'EMAIL'             =>  $email,
            'USERNAME'          =>  $username,
            'PASSWORD'          =>  password_hash($password, PASSWORD_DEFAULT),
            'STATUS'            =>  $status
        ];

        $mainOperation  =   new MainOperation();
        $procInsertData =   $mainOperation->insertDataTable('m_useradmin', $arrInsertData);

        if(!$procInsertData['status']) return switchMySQLErrorCode($procInsertData['errCode']);
        return throwResponseOK(
            'Data pengguna baru telah ditambahkan'
        );
    }

    private function updateData($idPengguna)
    {
        $rules      =   [
            'email'     =>  ['label' => 'Email', 'rules' => 'required|valid_email|is_unique[m_useradmin.EMAIL, IDUSERADMIN, '.$idPengguna.']'],
            'username'  =>  ['label' => 'Username', 'rules' => 'required|alpha_numeric|min_length[5]|is_unique[m_useradmin.USERNAME, IDUSERADMIN, '.$idPengguna.']'],
            'idPengguna'=>  ['label' => 'ID Pengguna', 'rules' => 'required|alpha_numeric']
        ];

        $messages   =   [
            'idPengguna'   => [
                'required'      => 'Data kiriman tidak lengkap, silakan periksa kembali',
                'alpha_numeric' => 'Data kiriman tidak valid, silakan periksa kembali'
            ]
        ];
        if(!$this->validate($rules, $messages)) return $this->fail($this->validator->getErrors());

        $idLevelUser        =   $this->request->getVar('level');
        $idLevelUser        =   hashidDecode($idLevelUser);
        $nama               =   $this->request->getVar('nama');
        $email              =   $this->request->getVar('email');
        $username           =   $this->request->getVar('username');
        $password           =   $this->request->getVar('password');
        $konfirmasiPassword =   $this->request->getVar('konfirmasiPassword');
        $status             =   $this->request->getVar('status');

        $arrUpdateUserAdmin =   [
            'IDUSERADMINLEVEL'  =>  $idLevelUser,
            'NAME'              =>  $nama,
            'EMAIL'             =>  $email,
            'USERNAME'          =>  $username,
            'STATUS'            =>  $status
        ];

        if($password != "" || $konfirmasiPassword != ""){
			if($password == "") return throwResponseNotAcceptable("Silakan masukkan password baru untuk mengubah password");
            if($konfirmasiPassword == "") return throwResponseNotAcceptable("Silakan masukkan konfirmasi password baru");
            if($password != $konfirmasiPassword) return throwResponseNotAcceptable("Konfirmasi password baru tidak sesuai");

			$arrUpdateUserAdmin['PASSWORD'] =   password_hash($konfirmasiPassword, PASSWORD_DEFAULT);
        }

        $mainOperation  =   new MainOperation();
        $procUpdateData =   $mainOperation->updateDataTable('m_useradmin', $arrUpdateUserAdmin, ['IDUSERADMIN' => $idPengguna]);

        if(!$procUpdateData['status']) return switchMySQLErrorCode($procUpdateData['errCode']);
        return throwResponseOK(
            'Data pengguna telah diperbarui'
        );
    }
}

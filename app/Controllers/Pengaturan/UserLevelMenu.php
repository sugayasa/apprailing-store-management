<?php

namespace App\Controllers\Pengaturan;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Models\MainOperation;
use App\Models\Pengaturan\UserLevelMenuModel;

class UserLevelMenu extends ResourceController
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

    public function getDataLevelUser()
    {
        $userLevelMenuModel =   new UserLevelMenuModel();
        $dataUserLevel      =	$userLevelMenuModel->getDataLevelUser();

        if($dataUserLevel){
            $result =   encodeDatabaseObjectResultKey($dataUserLevel, 'IDUSERADMINLEVEL');
            return $this->setResponseFormat('json')
                        ->respond([
                            "listData"  =>  $result
                        ]);
        } else {
            return throwResponseNotFound('Tidak ada data yang ditemukan');
        }
    }

    public function getDetailMenuLevelUser()
    {
        $rules  =   [
            'idUserLevel' =>    ['label' => 'Id user level', 'rules' => 'required|alpha_numeric']
        ];

        $messages   =   [
            'idUserLevel'    => [
                'required'      =>  'Invalid data sent',
                'alpha_numeric' =>  'Invalid data sent'
            ]
        ];

        if(!$this->validate($rules, $messages)) return $this->fail($this->validator->getErrors());

        $userLevelMenuModel =   new UserLevelMenuModel();
        $idUserLevel        =   $this->request->getVar('idUserLevel');
        $idUserLevel        =   hashidDecode($idUserLevel);
        $dataMenuLevel      =	$userLevelMenuModel->getDetailMenuLevelUser($idUserLevel);

        if($dataMenuLevel){
            $dataMenuLevel  =   encodeDatabaseObjectResultKey($dataMenuLevel, ['IDMENUADMIN', 'IDMENULEVELADMIN']);
            return $this->setResponseFormat('json')
                        ->respond([
                            "listData"    =>  $dataMenuLevel
                        ]);
        } else {
            return throwResponseNotFound('Tidak ada detail menu level user yang ditemukan');
        }
    }

    public function saveLevelUser()
    {
        $idLevelUser    =   $this->request->getVar('idLevelUser');
        $idLevelUser    =   $idLevelUser != "" ? hashidDecode($idLevelUser) : 0;
        $rules          =   [
            'namaLevel' =>  ['label' => 'Nama Level User', 'rules' => 'required|string|min_length[3]|max_length[50]'],
            'deskripsi' =>  ['label' => 'Deskripsi', 'rules' => 'permit_empty|string|max_length[255]']
        ];

        $messages       =   [
            'namaLevel' =>  [],
            'deskripsi' =>  []
        ];

        if($idLevelUser != 0){
            $rules['namaLevel']['rules'].=  '|is_unique[m_useradminlevel.LEVELNAME, IDUSERADMINLEVEL, '.$idLevelUser.']';
        } else {
            $rules['namaLevel']['rules'].=  '|is_unique[m_useradminlevel.LEVELNAME]';
        }

        if(!$this->validate($rules, $messages)) return $this->fail($this->validator->getErrors());
        
        $mainOperation  =   new MainOperation();
        $namaLevel      =   $this->request->getVar('namaLevel');
        $deskripsi      =   $this->request->getVar('deskripsi');
        $arrInsertUpdate=   [
            'LEVELNAME'     =>  $namaLevel,
            'DESCRIPTION'   =>  $deskripsi,
            'ISSUPERADMIN'  =>  0
        ];

        if($idLevelUser == 0){
            $procInsertData =   $mainOperation->insertDataTable('m_useradminlevel', $arrInsertUpdate);
            if(!$procInsertData['status']) return switchMySQLErrorCode($procInsertData['errCode']);
        } else {
            $procUpdateData =   $mainOperation->updateDataTable('m_useradminlevel', $arrInsertUpdate, ['IDUSERADMINLEVEL' => $idLevelUser]);
            if(!$procUpdateData['status']) return switchMySQLErrorCode($procUpdateData['errCode']);
        }
                    
        $responseSuccess =   $idLevelUser == 0 ? 'Level user baru telah disimpan' : 'Level user telah diperbarui';
        return throwResponseOK($responseSuccess);
    }

    public function saveLevelMenu()
    {
        $rules      =   [
            'idUserLevel'   =>  ['label' => 'Id user level', 'rules' => 'required|alpha_numeric'],
            'userLevelMenu' =>  ['label' => 'data menu level pengguna', 'rules' => 'required|is_array'],
        ];

        $messages   =   [
            'idUserLevel'   => [
                'required'      => 'Data kiriman tidak valid',
                'alpha_numeric' => 'Data kiriman tidak valid'
            ],
            'userLevelMenu' => [
                'required'  => 'Data kiriman tidak valid',
                'is_array'  => 'Data kiriman tidak valid'
            ]
        ];

        if(!$this->validate($rules, $messages)) return $this->fail($this->validator->getErrors());

        $mainOperation  =   new MainOperation();
        $idUserLevel    =   $this->request->getVar('idUserLevel');
        $idUserLevel    =   hashidDecode($idUserLevel);
        $userLevelMenu  =   $this->request->getVar('userLevelMenu');

        foreach($userLevelMenu as $keyUserLevelMenu) {
            $idMenuAdmin        =   isset($keyUserLevelMenu->idMenuAdmin) && $keyUserLevelMenu->idMenuAdmin != '' ? hashidDecode($keyUserLevelMenu->idMenuAdmin) : 0;
            $idMenuLevelAdmin   =   isset($keyUserLevelMenu->idMenuLevelAdmin) && $keyUserLevelMenu->idMenuLevelAdmin != '' ? hashidDecode($keyUserLevelMenu->idMenuLevelAdmin) : 0;
            $isMenuOpen         =   isset($keyUserLevelMenu->isMenuOpen) && $keyUserLevelMenu->isMenuOpen != '' ? $keyUserLevelMenu->isMenuOpen : 0;

            if($isMenuOpen){
                $arrInsertUpdateMenuLevel   =   [
                    'IDUSERADMINLEVEL'  =>  $idUserLevel,
                    'IDMENUADMIN'       =>  $idMenuAdmin
                ];

                for($i=1; $i <= 3; $i++){
                    $arrInsertUpdateMenuLevel['ALLOWPERMISSION'.$i] =   isset($keyUserLevelMenu->{"allowPermission".$i}) && $keyUserLevelMenu->{"allowPermission".$i} != '' ? $keyUserLevelMenu->{"allowPermission".$i} : 0;
                }

                if($idMenuLevelAdmin != 0) $mainOperation->updateDataTable('m_menuleveladmin', $arrInsertUpdateMenuLevel, ['IDMENULEVELADMIN' => $idMenuLevelAdmin]);
                else $mainOperation->insertDataTable('m_menuleveladmin', $arrInsertUpdateMenuLevel);
            } else {
                if($idMenuLevelAdmin != 0) $mainOperation->deleteDataTable('m_menuleveladmin', ['IDMENULEVELADMIN' => $idMenuLevelAdmin]);
            }
        }

        return throwResponseOK(
            'Menu level pengguna telah diperbarui'
        );
    }
}
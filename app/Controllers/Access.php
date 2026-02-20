<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Models\AccessModel;
use CodeIgniter\I18n\Time;

class Access extends ResourceController
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

    public function check()
    {
        helper(['form', 'firebaseJWT', 'hashid']);

        $rules  =   [
            'hardwareID'    =>  ['label' => 'Hardware ID', 'rules' => 'required|alpha_numeric_punct|min_length[10]'],
        ];

        if(!$this->validate($rules)) return $this->fail($this->validator->getErrors());

        $hardwareID         =   strtoupper($this->request->getVar('hardwareID'));
        $username           =   $this->request->getVar('username');
        $userTimeZoneOffset =   $this->request->getVar('userTimeZoneOffset');
        $header             =   $this->request->getServer('HTTP_AUTHORIZATION');
        $explodeHeader      =   $header != "" ? explode(' ', $header) : [];
        $token              =   is_array($explodeHeader) && isset($explodeHeader[1]) && $explodeHeader[1] != "" ? $explodeHeader[1] : "";
        $timeCreate         =   Time::now(APP_TIMEZONE)->toDateTimeString();
        $statusCode         =   401;
        $responseMsg        =   'Harap masukkan username dan password untuk melanjutkan';
        $captchaCode        =   generateRandomCharacter(4, 3);

        $userAdminData  =   array(
            "name"  =>   "",
            "email" =>   ""
        );

        $tokenPayload   =   array(
            "idUserAdmin"       =>  0,
            "idUserAdminLevel"  =>  0,
            "username"          =>  "",
            "name"              =>  "",
            "email"             =>  "",
            "captchaCode"       =>  $captchaCode,
            "hardwareID"        =>  $hardwareID,
            "userTimeZoneOffset"=>  $userTimeZoneOffset,
            "timeCreate"        =>  $timeCreate
        );

        $defaultToken           =   encodeJWTToken($tokenPayload);

        if(isset($token) && $token != ""){
            try {
                $dataDecode     =   decodeJWTToken($token);
                $idUserAdmin    =   intval($dataDecode->idUserAdmin);
                $hardwareIDToken=   $dataDecode->hardwareID;
                $timeCreateToken=   $dataDecode->timeCreate;

                if($idUserAdmin != 0){
                    $accessModel    =   new AccessModel(); 
                    $userAdminDataDB=   $accessModel
                                        ->where("IDUSERADMIN", $idUserAdmin)
                                        ->first();

                    if(!$userAdminDataDB || is_null($userAdminDataDB)) return throwResponseUnauthorized('[E-AUTH-001.1.0] User Anda tidak terdaftar. Harap login untuk melanjutkan', ['token'=>$defaultToken]);

                    $hardwareIDDB   =   $userAdminDataDB['HARDWAREID'];
                    $usernameDB     =   $userAdminDataDB['USERNAME'];

                    if($hardwareID == $hardwareIDDB && $hardwareID == $hardwareIDToken){
                        $timeCreateToken    =   Time::parse($timeCreateToken, APP_TIMEZONE);
                        $minutesDifference  =   $timeCreateToken->difference(Time::now(APP_TIMEZONE))->getMinutes();

                        if($minutesDifference > MAX_INACTIVE_SESSION_MINUTES){
                            return throwResponseForbidden('Sesi berakhir, harap login terlebih dahulu');
                        }
            
                        $accessModel->update($idUserAdmin, ['DATETIMELOGIN' => $timeCreate]);

                        $userAdminData  =   [
                            "name"  =>   $userAdminDataDB['NAME'],
                            "email" =>   $userAdminDataDB['EMAIL']
                        ];

                        if(isset($username) && $username != null){
                            if($username != $usernameDB) {
                                $accessModel->where('HARDWAREID', $hardwareID)->set('HARDWAREID', 'null', false)->update();
                                return throwResponseConlflict('Pengguna tidak valid, kredensial pengguna tidak cocok');
                            }
                        }

                        $tokenPayload['idUserAdmin']        =   $idUserAdmin;
                        $tokenPayload['idUserAdminLevel']   =   $userAdminDataDB['IDUSERADMINLEVEL'];
                        $tokenPayload['username']           =   $userAdminDataDB['USERNAME'];
                        $tokenPayload['name']               =   $userAdminDataDB['NAME'];
                        $tokenPayload['initialName']        =   getInitialsName($userAdminDataDB['NAME']);
                        $tokenPayload['email']              =   $userAdminDataDB['EMAIL'];
                        $statusCode                         =   200;
                        $responseMsg                        =   'Login berhasil, lanjutkan';
                    } else {
                        return throwResponseUnauthorized('[E-AUTH-001.1.2] Hardware ID perangkat berubah, harap login untuk melanjutkan', ['token'=>$defaultToken]);
                    }
                }
            } catch (\Throwable $th) {
                return throwResponseUnauthorized('[E-AUTH-001.2.0] Token tidak valid', ['token'=>$defaultToken]);
            }
        }

        $newToken       =   encodeJWTToken($tokenPayload);
        $optionHelper   =   $this->getDataOption();
        return $this->setResponseFormat('json')
                    ->respond([
                        'token'         =>  $newToken,
                        'userAdminData' =>  $userAdminData,
                        'optionHelper'  =>  $optionHelper,
                        'messages'      =>  [
                            "accessMessage" =>  $responseMsg
                        ]
                    ])
                    ->setStatusCode($statusCode);

    }

    public function login()
    {
        helper(['form']);
        $rules  =   [
            'username'  =>  'required|min_length[5]',
            'password'  =>  'required|min_length[5]',
            'captcha'   =>  'required|alpha_numeric|exact_length[4]'
        ];

        if(!$this->validate($rules)) return $this->fail($this->validator->getErrors());

        $accessModel    =   new AccessModel();
        $username       =   $this->request->getVar('username');
        $password       =   $this->request->getVar('password');
        $captcha        =   $this->request->getVar('captcha');
        $captchaToken   =   $this->userData->captchaCode;

        if($captcha != $captchaToken) return $this->fail('Kode captcha yang Anda masukkan tidak cocok');

        $dataUserAdmin  =   $accessModel->where("USERNAME", $username)->where("STATUS", 1)->first();

        if(!$dataUserAdmin) return $this->failNotFound('Tidak ada username yang cocok, masukkan username lain');
 
        $passwordVerify =   password_verify($password, $dataUserAdmin['PASSWORD']);
        if(!$passwordVerify) return $this->fail('Password yang Anda masukkan salah');

        $idUserAdmin        =   $dataUserAdmin['IDUSERADMIN'];
        $idUserAdminLevel   =   $dataUserAdmin['IDUSERADMINLEVEL'];
        $name               =   $dataUserAdmin['NAME'];
        $email              =   $dataUserAdmin['EMAIL'];
        $currentDateTime    =   $this->currentDateTime;
        $hardwareID         =   $this->userData->hardwareID;
        
        $dataUpdateUserAdmin    =   [
            'HARDWAREID'    =>   $hardwareID,
            'DATETIMELOGIN' =>   $currentDateTime    
        ];

        $accessModel        =   new AccessModel();
        $accessModel->where('HARDWAREID', $hardwareID)->set('HARDWAREID', 'null', false)->update();
        $accessModel->update($idUserAdmin, $dataUpdateUserAdmin);

        $tokenUpdate        =   array(
            "idUserAdmin"       =>  $idUserAdmin,
            "idUserAdminLevel"  =>  $idUserAdminLevel,
            "username"          =>  $username,
            "initialName"       =>  getInitialsName($name),
            "name"              =>  $name,
            "email"             =>  $email
        );
        
        $optionHelper   =   $this->getDataOption();
        return $this->setResponseFormat('json')
                    ->respond([
                        'tokenUpdate'   =>  $tokenUpdate,
                        'optionHelper'  =>  $optionHelper,
                        'message'       =>  "Login berhasil"
                    ]);		
    }

    public function logout($token = false)
    {
        if(!$token || $token == "") return $this->failUnauthorized('[E-AUTH-001.1] Token Required');
        helper(['firebaseJWT']);

        try {
            $dataDecode         =   decodeJWTToken($token);
            $idUserAdmin        =   $dataDecode->idUserAdmin;
            $hardwareID         =   $dataDecode->hardwareID;
            $accessModel        =   new AccessModel();
            $userAdminDataDB    =   $accessModel
                                    ->where("IDUSERADMIN", $idUserAdmin)
                                    ->first();

            if(!$userAdminDataDB || is_null($userAdminDataDB)) return redirect()->to(BASE_URL.'logoutPage');

            $hardwareIDDB       =   $userAdminDataDB['HARDWAREID'];

            if($hardwareID == $hardwareIDDB){
                $accessModel->where('HARDWAREID', $hardwareID)->set('HARDWAREID', 'null', false)->update();
            }

            return redirect()->to(BASE_URL.'logoutPage');
        } catch (\Throwable $th) {
            return $this->failUnauthorized('[E-AUTH-001.2] Token tidak valid - '.$th->getMessage());
        }
    }

    public function captcha($token = '')
    {
        if(!$token || $token == "") $this->returnBlankCaptcha();
        helper(['firebaseJWT']);

        try {
            $dataDecode     =   decodeJWTToken($token);
            $captchaCode    =   $dataDecode->captchaCode;
            $codeLength     =   strlen($captchaCode);

            generateCaptchaImage($captchaCode, $codeLength);
        } catch (\Throwable $th) {
            $this->returnBlankCaptcha();
        }
    }

    private function returnBlankCaptcha()
    {
        $img    =   imagecreatetruecolor(120, 20);
        $bg     =   imagecolorallocate ( $img, 255, 255, 255 );
        imagefilledrectangle($img, 0, 0, 120, 20, $bg);
        
        ob_start();
        imagejpeg($img, "blank.jpg", 100);
        $contents = ob_get_contents();
        ob_end_clean();

        $dataUri = "data:image/jpeg;base64," . base64_encode($contents);
        echo $dataUri;
    }

    private function getDataOption()
    {
        $accessModel            =   new AccessModel();
        $dataUserAdminLevel     =   encodeDatabaseObjectResultKey($accessModel->getDataUserAdminLevel(), 'ID');
        $dataUserAdminLevelMenu =   encodeDatabaseObjectResultKey($accessModel->getDataUserAdminLevelMenu(), 'ID');
        $dataMerk               =   encodeDatabaseObjectResultKey($accessModel->getDataMerk(), 'ID');
        $dataBarangKategori     =   encodeDatabaseObjectResultKey($accessModel->getDataBarangKategori(), 'ID');

        return [
            "dataUserAdminLevel"    =>  $dataUserAdminLevel,
            "dataUserAdminLevelMenu"=>  $dataUserAdminLevelMenu,
            "dataMerk"              =>  $dataMerk,
            "dataBarangKategori"    =>  $dataBarangKategori,
            "optionHours"	        =>  OPTION_HOURS,
            "optionMinutes"         =>  OPTION_MINUTES,
            "optionMinuteInterval"	=>  OPTION_MINUTEINTERVAL,
            "optionMonth"	        =>  OPTION_MONTH,
            "optionYear"	        =>  OPTION_YEAR
        ];
    }

    public function getDataOptionByKey($keyName, $optionName = false, $keyword = false)
    {
        $accessModel    =   new AccessModel();
        $optionName     =   $optionName != false ? $optionName : 'randomOption';
        $dataOption     =   [];
        $arrEncodeKey   =   ['ID'];

        switch($keyName){
            default :
                break;
        }

        $dataOption     =   encodeDatabaseObjectResultKey($dataOption, $arrEncodeKey);
        return $this->setResponseFormat('json')
                ->respond([
                    "dataOption"    =>  $dataOption,
                    "optionName"    =>  $optionName
                ]);
    }

    public function detailProfileSetting()
    {
        $accessModel    =   new AccessModel();
        $idUserAdmin    =   $this->userData->idUserAdmin;
        $detailUserAdmin=   $accessModel->getUserAdminDetail($idUserAdmin);

        if(is_null($detailUserAdmin)) return throwResponseNotFound("Detail profil tidak ditemukan");
        unset($detailUserAdmin['IDUSERADMINLEVEL']);
        return $this->setResponseFormat('json')
                    ->respond([
                        "detailUserAdmin"   =>  $detailUserAdmin
                     ]);
    }

    public function saveDetailProfileSetting()
    {
        helper(['form']);
        $idUserAdmin  =   $this->userData->idUserAdmin;
        $rules          =   [
            'username'  => ['label' => 'Username', 'rules' => 'required|alpha_numeric|min_length[4]'],
            'name'      => ['label' => 'Nama', 'rules' => 'required|alpha_numeric_space|min_length[4]'],
        ];

        if(!$this->validate($rules)) return $this->fail($this->validator->getErrors());

        $accessModel        =   new AccessModel();
        $username           =   $this->request->getVar('username');
        $name               =   $this->request->getVar('name');
        $currentPassword    =   $this->request->getVar('currentPassword');
        $newPassword        =   $this->request->getVar('newPassword');
        $repeatPassword     =   $this->request->getVar('repeatPassword');
        $relogin            =   false;

        $arrUpdateUserAdmin =   [
            'NAME'      =>  $name,
            'USERNAME'  =>  $username
        ];

        if($currentPassword != "" || $newPassword != "" || $repeatPassword != ""){
			if($currentPassword == "") return throwResponseNotAcceptable("Harap masukkan kata sandi lama Anda (kata sandi saat ini)");
			if($newPassword == "") return throwResponseNotAcceptable("Harap masukkan kata sandi baru");
            if($repeatPassword == "") return throwResponseNotAcceptable("Harap masukkan pengulangan kata sandi baru");
			if($newPassword != $repeatPassword) return throwResponseNotAcceptable("Pengulangan kata sandi yang Anda masukkan tidak cocok");
			
            $dataUserAdmin  =   $accessModel->where("IDUSERADMIN", $idUserAdmin)->first();
            if(!$dataUserAdmin) return $this->failNotFound('Data profil Anda tidak ditemukan, silakan coba lagi nanti');
            $passwordVerify =   password_verify($currentPassword, $dataUserAdmin['PASSWORD']);
            if(!$passwordVerify) return $this->fail('Kata sandi lama yang Anda masukkan salah');
			
			$arrUpdateUserAdmin['PASSWORD'] =	password_hash($newPassword, PASSWORD_DEFAULT);
            $relogin                        =   true;
		}

        $accessModel->update($idUserAdmin, $arrUpdateUserAdmin);
        $tokenUpdate    =   [
            "username"  =>  $username,
            "name"      =>  $name
        ];

        return $this->setResponseFormat('json')
                    ->respond([
                        "message"       =>  "Data profil Anda telah diperbarui",
                        "relogin"       =>  $relogin,
                        "tokenUpdate"   =>  $tokenUpdate
                     ]);
    }
}
<?php
namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;
use App\Controllers\BaseController;
use App\Models\MainOperation;
use App\Models\AccessModel;

class Index extends BaseController
{
    use ResponseTrait;
    public function index()
    {
        return $this->failForbidden('[E-AUTH-000] Akses ditolak');
    }

    public function response404()
    {
        return $this->failNotFound('[E-AUTH-404] Tidak ditemukan');
    }

    public function main()
    {
        return view('main');
    }

    public function loginPage()
    {
        return view('login');
    }

    public function mainPage()
    {
        helper(['form']);

        $hardwareID     =   strtoupper($this->request->getVar('hardwareID'));
        $lastPageAlias  =   strtoupper($this->request->getVar('lastPageAlias'));
        $header         =   $this->request->getServer('HTTP_AUTHORIZATION');
        $explodeHeader  =   $header != "" ? explode(' ', $header) : [];
        $token          =   is_array($explodeHeader) && isset($explodeHeader[1]) && $explodeHeader[1] != "" ? $explodeHeader[1] : "";

        if(isset($token) && $token != ""){
            try {
                $dataDecode         =   decodeJWTToken($token);
                $idUserAdmin        =   intval($dataDecode->idUserAdmin);
                $idUserAdminLevel   =   intval($dataDecode->idUserAdminLevel);
                $hardwareIDToken    =   $dataDecode->hardwareID;

                if($idUserAdmin != 0){
                    if(isset($idUserAdminLevel) && $idUserAdminLevel != "" && $idUserAdminLevel != 0){
                        $accessModel    =   new AccessModel();
                        $userAdminDataDB=   $accessModel->getUserAdminDetail($idUserAdmin);

                        if(!$userAdminDataDB || is_null($userAdminDataDB)) return $this->failUnauthorized('[E-AUTH-001.1.0] Token tidak valid - Tidak terdaftar');

                        $hardwareIDDB       =   $userAdminDataDB['HARDWAREID'];
                        $idUserAdminLevel   =   $userAdminDataDB['IDUSERADMINLEVEL'];

                        if($hardwareID == $hardwareIDDB && $hardwareID == $hardwareIDToken){
                            $userAdminData  =   array(
                                "name"      =>   $userAdminDataDB['NAME'],
                                "email"     =>   $userAdminDataDB['EMAIL'],
                                "levelName" =>   $userAdminDataDB['LEVELNAME']
                            );

                            try {
                                $listPlatform   =   $accessModel->getDataPlatform();
                                $platformElem   =   $this->generatePlatformElement($listPlatform);
                                $listMenuDB     =   $accessModel->getUserAdminMenu($idUserAdminLevel);
                                $menuElement    =	$this->menuBuilder($listMenuDB, $lastPageAlias, $listPlatform[0]->IDPLATFORM);
                                $htmlRes        =   view(
                                    'mainPage',
                                    array(
                                        "userAdminData" => $userAdminData,
                                        "platformElem"  => $platformElem,
                                        "menuElement"   => $menuElement,
                                        "allowNotifList"=> []
                                    ),
                                    ['debug' => false]
                                );
                                return $this->setResponseFormat('json')
                                ->respond([
                                    'htmlRes'   =>  $htmlRes
                                ]);
                            } catch (\Throwable $th) {
                                log_message('error', '[E-AUTH-001.1.1] Kesalahan internal. Gagal merespons: ' . $th->getMessage());
                                return $this->failUnauthorized('[E-AUTH-001.1.1] Kesalahan internal. Gagal merespons');
                            }
                        } else {
                            return $this->failUnauthorized('[E-AUTH-001.1.2] Token tidak valid - Hardware ID');
                        }
                    } else {
                        return $this->failUnauthorized('[E-AUTH-001.1.3] Token tidak valid - Level');
                    }
                } else {
                    return $this->failUnauthorized('[E-AUTH-001.1.4] Token tidak valid - User ID');
                }
            } catch (\Throwable $th) {
                return $this->failUnauthorized('[E-AUTH-001.2.0] Token tidak valid');
            }
        } else {
            return $this->failUnauthorized('[E-AUTH-001.2.0] Token tidak valid');
        }
    }

    private function generatePlatformElement($listPlatform)
    {
        if(is_null($listPlatform) || !is_array($listPlatform) || empty($listPlatform)) return ["firstPlatform" => "", "platformElem" => ""];
        $platformElem   =   "";
        if(is_array($listPlatform) && !empty($listPlatform)){
            foreach($listPlatform as $platform){
                $platformElem   .=  '<a class="dropdown-item" href="#"
                                            data-select="platform-dropdown-selection"
                                            data-target="#platformOption"
                                            data-id="'.hashidEncode($platform->IDPLATFORM).'"
                                            data-value="'.$platform->NAMAPLATFORM.'"
                                    >
                                    '.$platform->NAMAPLATFORM.'
                                    </a>';
            }
        }

        return [
            "firstPlatform" => $listPlatform[0]->NAMAPLATFORM ?? "",
            "platformElem"  => $platformElem
        ];
    }

    public function menuBuilder($listMenuDB, $lastPageAlias, $firstPlatformId)
    {
        if ($listMenuDB == "" || !is_array($listMenuDB) || empty($listMenuDB)) {
            return "";
        }

        $groupItemsMap = [];
        foreach ($listMenuDB as $item) {
            if ($item->GROUPNAME !== $item->MENUNAME) {
                $groupItemsMap[$item->IDPLATFORM][$item->GROUPNAME][] = $item;
            }
        }

        $menuElement    = "";
        $activePlatform = null;
        $renderedGroups = [];

        foreach ($listMenuDB as $item) {
            $platform       = $item->IDPLATFORM;
            $platformEnc    = $platform == 0 ? 0 : hashidEncode($platform);
            $dNoneClass     = ($platform !== $firstPlatformId && $platform != 0) ? ' d-none' : '';
            $isStandalone   = ($item->GROUPNAME === $item->MENUNAME);
            $groupKey       = $platform . '|' . $item->GROUPNAME;

            if (!$isStandalone && isset($renderedGroups[$groupKey])) {
                continue;
            }

            if ($activePlatform !== null && $activePlatform == 0 && $activePlatform !== $platform) {
                $menuElement .= '<div class="vr mx-2 px-1 text-white"></div>';
            }
            $activePlatform = $platform;

            $active = ($lastPageAlias !== '' && $lastPageAlias === $item->MENUALIAS) ? ' active' : '';
            if ($active === '' && $menuElement === '') {
                $active = ' active';
            }

            if ($isStandalone) {
                $menuElement .= '<div class="menu-item top-level' . $active . $dNoneClass . '" data-id-platform="' . $platformEnc . '">';
                $menuElement .= '<a href="#" class="menu-app-item menu-link" title="' . $item->MENUNAME . '" data-alias="' . $item->MENUALIAS . '" data-url="' . $item->URL . '">';
                $menuElement .= '<span class="menu-icon"><i class="fa ' . $item->ICON . '"></i></span>';
                $menuElement .= '<span class="menu-text">' . $item->MENUNAME . '</span>';
                $menuElement .= '</a></div>';
            } else {
                $renderedGroups[$groupKey] = true;
                $groupItems = $groupItemsMap[$platform][$item->GROUPNAME] ?? [];
                $icon       = $groupItems[0]->ICON ?? $item->ICON;

                $menuElement .= '<div class="menu-item top-level has-sub' . $dNoneClass . '" data-id-platform="' . $platformEnc . '">';
                $menuElement .= '<a href="#" class="menu-link">';
                $menuElement .= '<span class="menu-icon"><i class="fa ' . $icon . '"></i></span>';
                $menuElement .= '<span class="menu-text">' . $item->GROUPNAME . '</span>';
                $menuElement .= '<span class="menu-caret"><b class="caret"></b></span>';
                $menuElement .= '</a>';
                $menuElement .= '<div class="menu-submenu">';

                foreach ($groupItems as $gi) {
                    $itemActive   = ($lastPageAlias !== '' && $lastPageAlias === $gi->MENUALIAS) ? ' active' : '';
                    $menuElement .= '<div class="menu-item' . $itemActive . '">';
                    $menuElement .= '<a href="#" class="menu-app-item menu-link" title="' . $gi->MENUNAME . '" data-alias="' . $gi->MENUALIAS . '" data-url="' . $gi->URL . '">';
                    $menuElement .= '<span class="menu-text">' . $gi->MENUNAME . '</span>';
                    $menuElement .= '</a></div>';
                }

                $menuElement .= '</div></div>';
            }
        }

        return $menuElement;
    }
}
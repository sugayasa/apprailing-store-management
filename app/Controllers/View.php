<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Models\AccessModel;

class View extends ResourceController
{
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    use ResponseTrait;
    protected $userData, $currentDateTime, $currentDateDT, $menuDetail;
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger) {
        parent::initController($request, $response, $logger);

        $accessModel    =   new AccessModel();
        $alias          =   $this->request->getVar('alias');
        $menuDetail     =   $accessModel->getMenuDetailByAlias($alias);

        try {
            $this->userData         =   $request->userData;
            $this->currentDateTime  =   $request->currentDateTime;
            $this->currentDateDT    =   $request->currentDateDT;
            $this->menuDetail       =   $menuDetail;
        } catch (\Throwable $th) {
        }
    }

    public function __call($name, $arguments)
    {
        $content    =   view(
            'errors/html/underConstruction',
            [
                'menuDetail'    =>  $this->menuDetail ?? null,
                'methodName'    =>  $name,
            ],
            ['debug' => false]
        );
        return $this->setResponseFormat('json')->respond([
            'content'   =>  $content
        ]);
    }

    public function index()
    {
        return $this->failForbidden('[E-AUTH-000] Forbidden Access');
    }
    
    public function dashboard()
    {
        $content    =   view(
            'Menu/dashboard',
            [],
            ['debug' => false]
        );
        return $this->setResponseFormat('json')->respond([
            'content'   =>  $content
        ]);
    }

    public function katalogProduk()
    {
        $content    =   view(
            'Menu/katalogProduk',
            [],
            ['debug' => false]
        );
        return $this->setResponseFormat('json')->respond([
            'content'   =>  $content
        ]);
    }

    public function daftarHarga()
    {
        $content    =   view(
            'Menu/daftarHarga',
            [],
            ['debug' => false]
        );
        return $this->setResponseFormat('json')->respond([
            'content'   =>  $content
        ]);
    }

    public function monitoringMutasiStok()
    {
        $accessModel    =   new AccessModel();
        $dataRegional   =   $accessModel->getDataRegional();
        $arrRegional    =   array_map(function($item) {
            return [
                'IDKOTA'    =>  hashidEncode($item->IDKOTA),
                'NAMAKOTA'  =>  $item->NAMAKOTA
            ];
        }, $dataRegional);
        $content        =   view(
            'Menu/monitoringMutasiStok',
            ['arrRegional' => $arrRegional],
            ['debug' => false]
        );
        return $this->setResponseFormat('json')->respond([
            'content'   =>  $content
        ]);
    }
    
    public function pengaturanLevelMenu()
    {
        $content    =   view(
            'Menu/pengaturan/levelMenu',
            [],
            ['debug' => false]
        );
        return $this->setResponseFormat('json')->respond([
            'content'   =>  $content
        ]);
    }
    
    public function pengaturanDaftarPengguna()
    {
        $content    =   view(
            'Menu/pengaturan/daftarPengguna',
            [],
            ['debug' => false]
        );
        return $this->setResponseFormat('json')->respond([
            'content'   =>  $content
        ]);
    }
    
    public function pengaturanVariabelSistem()
    {
        $content    =   view(
            'Menu/pengaturan/variabelSistem',
            [],
            ['debug' => false]
        );
        return $this->setResponseFormat('json')->respond([
            'content'   =>  $content
        ]);
    }
    
    public function customerDataDasarMerk()
    {
        $content    =   view(
            'Menu/Customer/DataDasar/merk',
            [
                'menuDetail'                =>  $this->menuDetail,
                'defaultImage'              =>  BASE_URL_ASSETS_CUSTOMER_MERK . 'default.jpg',
                'defaultPdfKatalogThumbnail'=>  BASE_URL_ASSETS_PDF_KATALOG_THUMBNAIL . 'default.png',
                'defaultPdfKatalogFile'     =>  BASE_URL_ASSETS_PDF_KATALOG_FILE . 'default.pdf'
            ],
            ['debug' => false]
        );
        return $this->setResponseFormat('json')->respond([
            'content'   =>  $content
        ]);
    }
    
    public function customerDataDasarKategoriProduk()
    {
        $content    =   view(
            'Menu/Customer/DataDasar/kategoriProduk',
            [
                'menuDetail'    =>  $this->menuDetail
            ],
            ['debug' => false]
        );
        return $this->setResponseFormat('json')->respond([
            'content'   =>  $content
        ]);
    }
    
    public function customerDataDasarLevelLoyalti()
    {
        $content    =   view(
            'Menu/Customer/DataDasar/levelLoyalti',
            [
                'menuDetail'        =>  $this->menuDetail,
                'defaultImageCard'  =>  BASE_URL_ASSETS_CARD_LEVEL_LOYALTI . 'default.jpg',
                'defaultImageIcon'  =>  BASE_URL_ASSETS_ICON_LEVEL_LOYALTI . 'default.png'
            ],
            ['debug' => false]
        );
        return $this->setResponseFormat('json')->respond([
            'content'   =>  $content
        ]);
    }
    
    public function customerKontenPengenalanAplikasi()
    {
        $content    =   view(
            'Menu/Customer/Konten/pengenalanAplikasi',
            [
                'menuDetail'    =>  $this->menuDetail,
                'defaultImage'  =>  BASE_URL_ASSETS_SLIDE_ONBOARDING . 'defaultBoarding.png'
            ],
            ['debug' => false]
        );
        return $this->setResponseFormat('json')->respond([
            'content'   =>  $content
        ]);
    }
    
    public function customerKontenGaleriProyek()
    {
        $accessModel=   new AccessModel();
        $dataMerk   =   $accessModel->getDataCustomerMerk();
        $content    =   view(
            'Menu/Customer/Konten/galeriProyek',
            [
                'menuDetail'    =>  $this->menuDetail,
                'dataMerk'      =>  encodeDatabaseObjectResultKey($dataMerk, 'ID'),
                'defaultImage'  =>  BASE_URL_ASSETS_GALERI_PROYEK . 'noimage.jpg'
            ],
            ['debug' => false]
        );
        return $this->setResponseFormat('json')->respond([
            'content'   =>  $content
        ]);
    }
    
    public function customerKontenTutorialPemasangan()
    {
        $content    =   view(
            'Menu/Customer/Konten/tutorialPemasangan',
            [
                'menuDetail'    =>  $this->menuDetail,
                'defaultImage'  =>  BASE_URL_ASSETS_VIDEO_CARA_PASANG . 'noimage.jpg'
            ],
            ['debug' => false]
        );
        return $this->setResponseFormat('json')->respond([
            'content'   =>  $content
        ]);
    }
    
    public function customerKontenProfilPerusahaan()
    {
        $content    =   view(
            'Menu/Customer/Konten/profilPerusahaan',
            [
                'menuDetail'    =>  $this->menuDetail,
                'defaultImage'  =>  BASE_URL_ASSETS_VIDEO_COMPANY_PROFILE . 'defaultThumbnail.jpg'
            ],
            ['debug' => false]
        );

        return $this->setResponseFormat('json')->respond([
            'content'   =>  $content
        ]);
    }
    
    public function customerKontenFeed()
    {
        $content    =   view(
            'Menu/Customer/Konten/feed',
            [
                'menuDetail'    =>  $this->menuDetail
            ],
            ['debug' => false]
        );

        return $this->setResponseFormat('json')->respond([
            'content'   =>  $content
        ]);
    }
    
    public function customerKontenBeritaInformasi()
    {
        $content    =   view(
            'Menu/Customer/Konten/beritaInformasi',
            [
                'menuDetail'    =>  $this->menuDetail,
                'defaultImage'  =>  BASE_URL_ASSETS_SLIDE_BANNER . 'noimage.jpg'
            ],
            ['debug' => false]
        );

        return $this->setResponseFormat('json')->respond([
            'content'   =>  $content
        ]);
    }
}
<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;

class Assets extends ResourceController
{
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    use ResponseTrait;
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger) {
        parent::initController($request, $response, $logger);
    }

    public function index()
    {
        return $this->failForbidden('[E-AUTH-000] Forbidden Access');
    }

    public function logoMerk($namaFile)
    {
        $fullFilePath   =   PATH_STORAGE_FILE_LOGO_MERK.$namaFile;
        $isDefault      =   strpos($namaFile, 'default') !== false;
        $defaultFilePath=   PATH_STORAGE_FILE_LOGO_MERK  .'default.jpg';

        return $this->setReturnAssets($namaFile, $fullFilePath, $isDefault, $defaultFilePath);
    }

    public function logoMarketplace($namaFile)
    {
        $fullFilePath   =   PATH_STORAGE_FILE_LOGO_MARKETPLACE.$namaFile;
        $isDefault      =   strpos($namaFile, 'default') !== false;
        $defaultFilePath=   PATH_STORAGE_FILE_LOGO_MARKETPLACE  .'default.jpg';

        return $this->setReturnAssets($namaFile, $fullFilePath, $isDefault, $defaultFilePath);
    }

    public function cardLevelLoyalti($namaFile)
    {
        $fullFilePath   =   PATH_STORAGE_FILE_CARD_LEVEL_LOYALTI.$namaFile;
        $isDefault      =   strpos($namaFile, 'default') !== false;
        $defaultFilePath=   PATH_STORAGE_FILE_CARD_LEVEL_LOYALTI  .'default.jpg';

        return $this->setReturnAssets($namaFile, $fullFilePath, $isDefault, $defaultFilePath);
    }

    public function iconLevelLoyalti($namaFile)
    {
        $fullFilePath   =   PATH_STORAGE_FILE_ICON_LEVEL_LOYALTI.$namaFile;
        $isDefault      =   strpos($namaFile, 'default') !== false;
        $defaultFilePath=   PATH_STORAGE_FILE_ICON_LEVEL_LOYALTI  .'default.png';

        return $this->setReturnAssets($namaFile, $fullFilePath, $isDefault, $defaultFilePath);
    }

    public function pdfKatalogThumbnail($namaFile)
    {
        $fullFilePath   =   PATH_STORAGE_FILE_PDF_KATALOG_THUMBNAIL.$namaFile;
        $isDefault      =   strpos($namaFile, 'default') !== false;
        $defaultFilePath=   PATH_STORAGE_FILE_PDF_KATALOG_THUMBNAIL  .'default.jpg';

        return $this->setReturnAssets($namaFile, $fullFilePath, $isDefault, $defaultFilePath);
    }

    public function pdfKatalogFile($namaFile)
    {
        $fullFilePath   =   PATH_STORAGE_FILE_PDF_KATALOG_FILE.$namaFile;
        $isDefault      =   strpos($namaFile, 'default') !== false;
        $defaultFilePath=   PATH_STORAGE_FILE_PDF_KATALOG_FILE  .'default.pdf';

        return $this->setReturnAssets($namaFile, $fullFilePath, $isDefault, $defaultFilePath);
    }

    public function imageSlideOnboarding($namaFile)
    {
        $fullFilePath   =   PATH_STORAGE_PHOTO_SLIDE_ONBOARDING.$namaFile;
        $isDefault      =   strpos($namaFile, 'defaultBoarding') !== false;
        $defaultFilePath=   PATH_STORAGE_PHOTO_SLIDE_ONBOARDING  .'defaultBoarding.png';

        return $this->setReturnAssets($namaFile, $fullFilePath, $isDefault, $defaultFilePath);
    }

    public function imageSlideBanner($nameFile)
    {
        $fullFilePath   =   PATH_STORAGE_PHOTO_SLIDE_BANNER.$nameFile;
        $isDefault      =   strpos($nameFile, 'default') !== false;
        $defaultFilePath=   PATH_STORAGE_PHOTO_SLIDE_BANNER.'default.jpg';

        return $this->setReturnAssets($nameFile, $fullFilePath, $isDefault, $defaultFilePath);
    }

    public function videoCaraPasang($nameFile)
    {
        $fullFilePath   =   PATH_STORAGE_VIDEO_CARA_PASANG.$nameFile;
        $isDefault      =   strpos($nameFile, 'default') !== false;
        $defaultFilePath=   PATH_STORAGE_VIDEO_CARA_PASANG  .'thumbnailDefault.png';

        return $this->setReturnAssets($nameFile, $fullFilePath, $isDefault, $defaultFilePath);
    }

    public function photoBarang($namaFile)
    {
        $fullFilePath   =   PATH_STORAGE_PHOTO_BARANG.$namaFile;
        $isDefault      =   strpos($namaFile, 'noimage') !== false;
        $defaultFilePath=   PATH_STORAGE_PHOTO_BARANG  .'noimage.jpg';

        return $this->setReturnAssets($namaFile, $fullFilePath, $isDefault, $defaultFilePath);
    }

    public function imageGaleriProyek($namaFile)
    {
        $fullFilePath   =   PATH_STORAGE_PHOTO_GALERI_PROYEK.$namaFile;
        $isDefault      =   strpos($namaFile, 'noimage') !== false;
        $defaultFilePath=   PATH_STORAGE_PHOTO_GALERI_PROYEK  .'noimage.jpg';

        return $this->setReturnAssets($namaFile, $fullFilePath, $isDefault, $defaultFilePath);
    }

    private function setReturnAssets($namaFile, $fullFilePath, $isDefault, $defaultFilePath)
    {
        if (!is_file($fullFilePath) || !file_exists($fullFilePath) || $isDefault !== false) $fullFilePath   =   $defaultFilePath;

        $mimeType       =   mime_content_type($fullFilePath);
        $fileContent    =   file_get_contents($fullFilePath);

        return $this->response
            ->setHeader('Content-Type', $mimeType)
            ->setHeader('Content-Disposition', 'inline; filename="' . $namaFile . '"')
            ->setBody($fileContent);
    }
}
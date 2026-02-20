<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use CodeIgniter\I18n\Time;
use App\Models\AccessModel;

class View extends ResourceController
{
    /**
     * Return an array of resource objects, themselves in array format
     *
     * @return mixed
     */
    use ResponseTrait;
    protected $userData, $currentDateTime, $currentDateDT;
    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger) {
        parent::initController($request, $response, $logger);

        try {
            $this->userData         =   $request->userData;
            $this->currentDateTime  =   $request->currentDateTime;
            $this->currentDateDT    =   $request->currentDateDT;
        } catch (\Throwable $th) {
        }
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
}
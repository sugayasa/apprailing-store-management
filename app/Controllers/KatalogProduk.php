<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Models\MainOperation;
use App\Models\KatalogProdukModel;

class KatalogProduk extends ResourceController
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

    public function getDataKatalogProduk()
    {
        $mainOperation      =   new MainOperation();
        $katalogProdukModel =   new KatalogProdukModel();

        $merk           =   $this->request->getVar('merk');
        $kategori       =   $this->request->getVar('kategori');
        $keywordCari    =   $this->request->getVar('keywordCari');
        $urutBerdasar   =   $this->request->getVar('urutBerdasar');
        $jenisUrutan    =   $this->request->getVar('jenisUrutan');
        $pageNumber     =   $this->request->getVar('pageNumber') ? (int)$this->request->getVar('pageNumber') : 1;
        $dataPerPage    =   $this->request->getVar('dataPerPage') ? (int)$this->request->getVar('dataPerPage') : 12;
        $baseData       =   $katalogProdukModel->getDataBarang($merk, $kategori, $keywordCari, $urutBerdasar, $jenisUrutan);
        $totalNumberData=   $baseData->countAllResults(false);
        $pageProperty   =   $mainOperation->generatePageProperty($pageNumber, $dataPerPage, $totalNumberData);

        if($totalNumberData > 0){
            $dataDetailRegional =   $mainOperation->getDataDetailRegional();
            $listData           =   $baseData->asObject()->findAll($dataPerPage, ($pageNumber - 1) * $dataPerPage);
            $arrDataStokRegional=   [];
            
            foreach($dataDetailRegional as $detailRegional){
                $arrDataStokRegional[]  =   [
                    'NAMADATABASE'  => $detailRegional->NAMADATABASE,
                    'NAMAREGIONAL'  => $detailRegional->NAMAKOTA,
                    'STOKBARANG'    => rand(0, 2000)
                ];
            }

            foreach($listData as $keyData) {
                $keyData->STOKBARANG    =   &$arrDataStokRegional;
            }

            $listData   =   encodeDatabaseObjectResultKey($listData, ['IDBARANG']);
            return $this->setResponseFormat('json')->respond([
                "listData"          =>  $listData,
                "pageProperty"      =>  $pageProperty,
                "urlAssetFotoBarang"=>  BASE_URL_ASSETS_PHOTO_BARANG
            ]);
        } else {
            $dataReturn =   [
                "listData"          =>  [],
                "pageProperty"      =>  $pageProperty,
                "urlAssetFotoBarang"=>  BASE_URL_ASSETS_PHOTO_BARANG
            ];
            return throwResponseNotFound('Tidak ada data yang ditemukan', $dataReturn);
        }

    }
}
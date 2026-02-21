<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Models\MainOperation;
use App\Models\AccessModel;
use App\Models\MonitoringMutasiStokModel;

class MonitoringMutasiStok extends ResourceController
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

    public function getDataMonitoringMutasiStok()
    {
        $mainOperation              =   new MainOperation();
        $accessModel                =   new AccessModel();
        $monitoringMutasiStokModel  =   new MonitoringMutasiStokModel();
        $dataRegional               =   $accessModel->getDataRegional();
        $dataResult                 =   [];

        foreach ($dataRegional as $keyRegional) {
            $idRegional     =   $keyRegional->IDKOTA;
            $namaDatabase   =   $keyRegional->NAMADATABASE;
            $dataPerRegional=   $monitoringMutasiStokModel->getDataMonitoringMutasiStok($idRegional);

            if(!empty($dataPerRegional)){
                foreach ($dataPerRegional as $keyDataPerRegional) {
                    $idMonitoringMutasiStok =   $keyDataPerRegional->IDMONITORINGMUTASISTOK;
                    $idBarang               =   $keyDataPerRegional->IDBARANG;
                    $stokAwal               =   $keyDataPerRegional->STOKAWAL;
                    $stokTersedia           =   $keyDataPerRegional->STOKTERSEDIA;
                    $tanggalWaktu           =   $keyDataPerRegional->TANGGALWAKTU;

                    if($stokAwal == 0 && $stokTersedia == 0){
                        $dataStokBarang	=	$monitoringMutasiStokModel->getDataStokBarang($namaDatabase, $idBarang, $tanggalWaktu);
				
                        if($dataStokBarang){
                            $stokBarangAwal			=	$dataStokBarang['SALDOAWAL'];
                            $stokBarangAkhir		=	$dataStokBarang['SALDOAKHIR'];
                            $stokBarangReject		=	$dataStokBarang['STOKBARANGREJECT'];
                            $stokBarangDipesan		=	$dataStokBarang['STOKBARANGDIPESAN'];
                            $stokBarangDitahan		=	$stokBarangReject + $stokBarangDipesan;
                            $stokBarangTersedia		=	$stokBarangAkhir - $stokBarangDitahan;
                            $stokBarangTersedia		=	$stokBarangTersedia < 0 ? 0 : $stokBarangTersedia;
                            $arrUpdateMutasiStok    =   [
                                'STOKAWAL'      =>  $stokBarangAwal,
                                'STOKDITAHAN'   =>  $stokBarangDitahan,
                                'STOKTERSEDIA'  =>  $stokBarangTersedia,
                            ];

                            $mainOperation->updateDataTable('t_monitoringmutasistok', $arrUpdateMutasiStok, ['IDMONITORINGMUTASISTOK' => $idMonitoringMutasiStok]);
                        }
                    }
                }
            }

            $dataResult[]   =   [
                "idRegional"        =>  hashidEncode($idRegional),
                "dataPerRegional"   =>  $dataPerRegional
            ];
        }

        return $this->setResponseFormat('json')
                    ->respond([
                        "dataResult"    =>  $dataResult
                    ]);

    }
}
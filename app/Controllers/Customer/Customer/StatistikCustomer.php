<?php

namespace App\Controllers\Customer\Customer;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Models\Customer\Customer\StatistikCustomerModel;

class StatistikCustomer extends ResourceController
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

    public function getDataStatistik()
    {
        $rules      =   [
            'tanggalAwal'   =>  ['label' => 'Tanggal Awal', 'rules' => 'required|valid_date'],
            'tanggalAkhir'  =>  ['label' => 'Tanggal Akhir', 'rules' => 'required|valid_date']
        ];

        $messages   =   [];

        if(!$this->validate($rules, $messages)) return $this->fail($this->validator->getErrors());

        $tanggalAwal            =   $this->request->getVar('tanggalAwal');
        $tanggalAkhir           =   $this->request->getVar('tanggalAkhir');
        $arrTanggalPeriode      =   [];
        $dataGrafikKunjungan    =   [];
        $tanggalPeriodeAwalTS   =   strtotime($tanggalAwal);
        $tanggalPeriodeAkhirTS  =   strtotime($tanggalAkhir);
        $jumlahHariPeriode      =   ($tanggalPeriodeAkhirTS - $tanggalPeriodeAwalTS) / (60 * 60 * 24) + 1;

        if($jumlahHariPeriode > 60) return throwResponseNotAcceptable('Rentang tanggal tidak boleh lebih dari 60 hari');
        if($jumlahHariPeriode < 1) return throwResponseNotAcceptable('Rentang tanggal yang dipilih tidak valid');
            
        while ($tanggalPeriodeAwalTS <= $tanggalPeriodeAkhirTS) {
            $arrTanggalPeriode[]    =   date('d M', $tanggalPeriodeAwalTS);
            $tanggalPeriodeAwalTS   =   strtotime('+1 day', $tanggalPeriodeAwalTS);
        }

        $statistikCustomerModel =   new StatistikCustomerModel();
        $arrJenisKunjungan      =   ['Total Kunjungan', 'Jumlah Perangkat', 'Jumlah Tamu', 'Jumlah Registrasi', 'Jumlah Customer Terdaftar'];
        $arrJenisKunjunganHex   =   ['#1f6bff', '#c1cbdf', '#ff9500', '#ffcc00', '#9acfa7'];
        $dataKunjungan          =   $statistikCustomerModel->getDataKunjungan($tanggalAwal, $tanggalAkhir);

        foreach($arrJenisKunjungan as $index => $jenisKunjungan){
            $dataGrafik =   array_fill(0, count($arrTanggalPeriode), 0);

            foreach($dataKunjungan as $kunjungan){
                $tanggalIndex   =   array_search($kunjungan->TANGGALBULAN, $arrTanggalPeriode);
                if($tanggalIndex !== false){
                    switch ($jenisKunjungan) {
                        case 'Total Kunjungan':
                            $dataGrafik[$tanggalIndex]  =   (int)$kunjungan->TOTALKUNJUNGAN;
                            break;
                        case 'Jumlah Perangkat':
                            $dataGrafik[$tanggalIndex]  =   (int)$kunjungan->JUMLAHPERANGKAT;
                            break;
                        case 'Jumlah Tamu':
                            $dataGrafik[$tanggalIndex]  =   (int)$kunjungan->JUMLAHTAMU;
                            break;
                        case 'Jumlah Registrasi':
                            $dataGrafik[$tanggalIndex]  =   (int)$kunjungan->JUMLAHREGISTRASI;
                            break;
                        case 'Jumlah Customer Terdaftar':
                            $dataGrafik[$tanggalIndex]  =   (int)$kunjungan->JUMLAHCUSTOMERTERDAFTAR;
                            break;
                    }
                }
            }

            $dataGrafikKunjungan[]  =   [
                'color'                     =>  $arrJenisKunjunganHex[$index],
                'borderColor'               =>  $arrJenisKunjunganHex[$index],
                'borderWidth'               =>  1.5,
                'pointBackgroundColor'      =>  'app.color.componentBg',
                'pointBorderWidth'          =>  1.5,
                'pointRadius'               =>  4,
                'pointHoverBackgroundColor' =>  $arrJenisKunjunganHex[$index],
                'pointHoverBorderColor'     =>  $arrJenisKunjunganHex[$index],
                'pointHoverRadius'          =>  7,
                'label'                     =>  $jenisKunjungan,
                'data'                      =>  $dataGrafik,
                'tension'                   =>  0.4
            ];
        }
            
        $dataKunjunganRekapDB   =   $statistikCustomerModel->getDataKunjunganRekap($tanggalAwal, $tanggalAkhir);
        $dataKunjunganRekap     =   [
            'totalKunjungan'    =>  (int)$dataKunjunganRekapDB['TOTALKUNJUNGAN'],
            'jumlahPerangkat'   =>  (int)$dataKunjunganRekapDB['JUMLAHPERANGKAT'],
            'jumlahTamu'        =>  (int)$dataKunjunganRekapDB['JUMLAHTAMU'],
            'jumlahRegistrasi'  =>  (int)$dataKunjunganRekapDB['JUMLAHREGISTRASI'],
            'jumlahTeregistrasi'=>  (int)$dataKunjunganRekapDB['JUMLAHCUSTOMERTERDAFTAR']
        ];

        $dataStatistikBeritaDB      =   $statistikCustomerModel->getDataStatistikBerita($tanggalAwal, $tanggalAkhir);
        $dataStatistikGaleriKlienDB =   $statistikCustomerModel->getDataStatistikGaleriKlien($tanggalAwal, $tanggalAkhir);
        $dataStatistikGaleriProyekDB=   $statistikCustomerModel->getDataStatistikGaleriProyek($tanggalAwal, $tanggalAkhir);
        $dataStatistikFeedDB        =   $statistikCustomerModel->getDataStatistikFeed($tanggalAwal, $tanggalAkhir);

        $dataStatistikBerita =   [
            'jumlahDilihat'     =>  array_sum(array_column($dataStatistikBeritaDB, 'JUMLAHDILIHAT')),
            'jumlahBerita'      =>  count($dataStatistikBeritaDB),
            'baseUrlImageBerita'=>  BASE_URL_ASSETS_SLIDE_BANNER,
            'dataBerita'        =>  $dataStatistikBeritaDB
        ];
        
        $dataStatistikGaleriKlien   =   [
            'jumlahDilihat'     =>  array_sum(array_column($dataStatistikGaleriKlienDB, 'JUMLAHDILIHAT')),
            'jumlahUser'        =>  array_sum(array_column($dataStatistikGaleriKlienDB, 'JUMLAHUSER')),
            'jumlahGaleriKlien' =>  count($dataStatistikGaleriKlienDB),
            'baseUrlGaleriKlien'=>  BASE_URL_ASSETS_GALERI_KLIEN_PROYEK,
            'dataGaleriKlien'   =>  array_map(function($item) {
                                        $item->IMAGE    =   json_decode($item->IMAGE)[0] ?? 'noimage.jpg';
                                        return (array)$item;
                                    }, $dataStatistikGaleriKlienDB)
        ];
        
        $dataStatistikGaleriProyek  =   [
            'jumlahDilihat'         =>  array_sum(array_column($dataStatistikGaleriProyekDB, 'JUMLAHDILIHAT')),
            'jumlahUser'            =>  array_sum(array_column($dataStatistikGaleriProyekDB, 'JUMLAHUSER')),
            'jumlahGaleriProyek'    =>  count($dataStatistikGaleriProyekDB),
            'baseUrlGaleriProyek'   =>  BASE_URL_ASSETS_GALERI_PROYEK,
            'dataGaleriProyek'      =>  array_map(function($item) {
                                            $item->IMAGE    =   json_decode($item->IMAGE)[0] ?? 'noimage.jpg';
                                            return (array)$item;
                                        }, $dataStatistikGaleriProyekDB)
        ];
        
        $dataStatistikFeed  =   [
            'jumlahDilihat' =>  array_sum(array_column($dataStatistikFeedDB, 'JUMLAHDILIHAT')),
            'jumlahUser'    =>  array_sum(array_column($dataStatistikFeedDB, 'JUMLAHUSER')),
            'jumlahFeed'    =>  count($dataStatistikFeedDB),
            'dataFeed'      =>  $dataStatistikFeedDB
        ];

        return $this->setResponseFormat('json')
                    ->respond([
                        "jumlahHariPeriode"         =>  $jumlahHariPeriode,
                        "arrTanggalPeriode"         =>  $arrTanggalPeriode,
                        "dataGrafikKunjungan"       =>  $dataGrafikKunjungan,
                        "dataKunjunganRekap"        =>  $dataKunjunganRekap,
                        "dataStatistikBerita"       =>  $dataStatistikBerita,
                        "dataStatistikGaleriKlien"  =>  $dataStatistikGaleriKlien,
                        "dataStatistikGaleriProyek" =>  $dataStatistikGaleriProyek,
                        "dataStatistikFeed"         =>  $dataStatistikFeed
                    ]);
    }
}

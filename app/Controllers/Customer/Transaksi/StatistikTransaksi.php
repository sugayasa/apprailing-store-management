<?php

namespace App\Controllers\Customer\Transaksi;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Models\MainOperation;
use App\Models\Customer\Transaksi\StatistikTransaksiModel;

class StatistikTransaksi extends ResourceController
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
            'bulanTahun'    =>  ['label' => 'Bulan Tahun', 'rules' => 'required|valid_date[Y-m]']
        ];

        $messages   =   [];

        if(!$this->validate($rules, $messages)) return $this->fail($this->validator->getErrors());

        $bulanTahun             =   $this->request->getVar('bulanTahun');
        $arrTanggalPeriode      =   [];
        $dataGrafikTransaksi    =   [];
        $tanggalPeriodeAwalTS   =   strtotime($bulanTahun . '-01');
        $tanggalPeriodeAkhirTS  =   strtotime(date('Y-m-t', $tanggalPeriodeAwalTS));
        $tanggalAwalFilter      =   date('Y-m-d', $tanggalPeriodeAwalTS);
        $tanggalAkhirFilter     =   date('Y-m-d', $tanggalPeriodeAkhirTS);
            
        while ($tanggalPeriodeAwalTS <= $tanggalPeriodeAkhirTS) {
            $arrTanggalPeriode[]    =   date('d', $tanggalPeriodeAwalTS);
            $tanggalPeriodeAwalTS   =   strtotime('+1 day', $tanggalPeriodeAwalTS);
        }

        $statistikTransaksiModel=   new StatistikTransaksiModel();
        $dataMerkDB             =   $statistikTransaksiModel->getDataMerk();
        $arrDataColorHex        =   [
            '#1f6bff', '#c1cbdf', '#ff9500', '#ffcc00', '#9acfa7',
            '#ff5c8d', '#6f42c1', '#20c997', '#fd7e14', '#0dcaf0',
            '#d63384', '#198754', '#ffc107', '#6610f2', '#00bcd4',
            '#e91e63', '#4caf50', '#ff5722', '#795548', '#607d8b'
        ];
        $arrDataColorClassBS    =   [
            'primary', 'secondary', 'warning', 'info', 'success',
            'danger', 'dark', 'light', 'muted', 'white',
            'indigo', 'teal', 'orange', 'pink', 'cyan',
            'lime', 'purple', 'brown', 'blue-gray'
        ];
        $dataTransaksi          =   $statistikTransaksiModel->getDataTransaksi($tanggalAwalFilter, $tanggalAkhirFilter);

        //Statistik Transaksi Per Merk
        foreach($dataMerkDB as $index => $keyMerk){
            $namaMerk   =   $keyMerk->NAMAMERK;
            $dataGrafik =   array_fill(0, count($arrTanggalPeriode), 0);

            foreach($dataTransaksi as $transaksi){
                if($transaksi->IDMERK == $keyMerk->IDMERK){
                    $tanggalIndex   =   array_search($transaksi->TANGGAL, $arrTanggalPeriode);
                    if($tanggalIndex !== false){
                        $dataGrafik[$tanggalIndex]   =   $transaksi->TOTALTRANSAKSI;
                    }
                }
            }

            $dataGrafikTransaksi[]  =   [
                'color'                     =>  $arrDataColorHex[$index],
                'borderColor'               =>  $arrDataColorHex[$index],
                'borderWidth'               =>  1.5,
                'pointBackgroundColor'      =>  'app.color.componentBg',
                'pointBorderWidth'          =>  1.5,
                'pointRadius'               =>  4,
                'pointHoverBackgroundColor' =>  $arrDataColorHex[$index],
                'pointHoverBorderColor'     =>  $arrDataColorHex[$index],
                'pointHoverRadius'          =>  7,
                'label'                     =>  $namaMerk,
                'data'                      =>  $dataGrafik,
                'tension'                   =>  0.4
            ];
        }

        //Rekap Transaksi Per Merk
        $dataRekapPerMerkDB =   $statistikTransaksiModel->getDataRekapPerMerk($tanggalAwalFilter, $tanggalAkhirFilter);
        $dataRekapPerMerk   =   array_map(function($item) use ($dataRekapPerMerkDB) {
            $totalTransaksi =   0;
            $totalNominal   =   0;

            array_filter($dataRekapPerMerkDB, function($dataRekap) use (&$totalTransaksi, &$totalNominal, $item) {
                if($dataRekap->IDMERK == $item->IDMERK){
                    $totalTransaksi +=   $dataRekap->TOTALTRANSAKSI;
                    $totalNominal   +=   $dataRekap->TOTALTRANSAKSINOMINAL;
                }
            });

            return [
                'NAMAMERK'              =>  $item->NAMAMERK,
                'LOGO'                  =>  $item->LOGO,
                'TOTALTRANSAKSI'        =>  $totalTransaksi,
                'TOTALTRANSAKSINOMINAL' =>  $totalNominal
            ];
        }, $dataMerkDB);

        //Rekap Transaksi Per Regional
        $rekapRegionalMargin    =   2;
        $mainOperation          =   new MainOperation();
        $dataDetailRegional     =   $mainOperation->getDataDetailRegionalCustomer();
        $dataRekapPerRegionalDB =   $statistikTransaksiModel->getDataRekapPerRegional($tanggalAwalFilter, $tanggalAkhirFilter);
        $dataRekapPerRegional   =   [];

        if($dataDetailRegional && count($dataDetailRegional) > 0){
            $jumlahRegional         =   count($dataDetailRegional);
            $rekapRegionalMargin    =   9 - $jumlahRegional;
            $totalTransaksiNominal  =   array_sum(array_column($dataRekapPerRegionalDB, 'TOTALNOMINAL'));
            $indexRegional          =   -1;
            $dataRekapPerRegional   =   array_map(function($itemRegional) use ($dataRekapPerRegionalDB, $totalTransaksiNominal, &$indexRegional, $arrDataColorClassBS) {
                $totalTransaksi =   0;
                $totalNominal   =   0;

                array_filter($dataRekapPerRegionalDB, function($dataRekap) use (&$totalTransaksi, &$totalNominal, $itemRegional) {
                    if($dataRekap->IDREGIONAL == $itemRegional->IDREGIONAL){
                        $totalTransaksi =   $dataRekap->TOTALTRANSAKSI;
                        $totalNominal   =   $dataRekap->TOTALNOMINAL;
                    }
                });

                $indexRegional++;
                return [
                    'NAMAREGIONAL'      =>  $itemRegional->NAMAREGIONAL,
                    'CLASSWARNA'        =>  $arrDataColorClassBS[$indexRegional],
                    'TOTALTRANSAKSI'    =>  (int) $totalTransaksi,
                    'TOTALNOMINAL'      =>  (int) $totalNominal,
                    'PERSENTASE'        =>  $totalTransaksiNominal > 0 ? number_format(($totalNominal / $totalTransaksiNominal) * 100, 2, '.', ',') : 0
                ];
            }, $dataDetailRegional);
        }

        $dataProdukBestSeller   =   $statistikTransaksiModel->getDataProdukBestSeller($tanggalAwalFilter, $tanggalAkhirFilter);
        $dataProdukBestSeller   =   array_map(function($item) {
            $item->IMAGE    =   json_decode($item->IMAGE)[0] ?? 'noimage.jpg';
            return (array)$item;
        }, $dataProdukBestSeller);

        $dataRiwayatTransaksi   =   $statistikTransaksiModel->getDataRiwayatTransaksi($tanggalAwalFilter, $tanggalAkhirFilter);

        return $this->setResponseFormat('json')
                    ->respond([
                        "arrTanggalPeriode"     =>  $arrTanggalPeriode,
                        "dataGrafikTransaksi"   =>  $dataGrafikTransaksi,
                        "dataRekapPerMerk"      =>  $dataRekapPerMerk,
                        "dataRekapPerRegional"  =>  $dataRekapPerRegional,
                        "rekapRegionalMargin"   =>  $rekapRegionalMargin <= 0 ? 1 : $rekapRegionalMargin,
                        "dataProdukBestSeller"  =>  $dataProdukBestSeller,
                        "dataRiwayatTransaksi"  =>  $dataRiwayatTransaksi,
                        "urlAssetLogoMerk"      =>  BASE_URL_ASSETS_CUSTOMER_MERK,
                        "urlAssetCustomerProduk"=>  BASE_URL_ASSETS_CUSTOMER_PRODUK
                    ]);
    }
}

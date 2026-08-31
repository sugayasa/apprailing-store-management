<?php

namespace App\Controllers\Customer\Customer;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Models\Customer\Customer\ReviewMarketingModel;
use App\Models\MainOperation;

class ReviewMarketing extends ResourceController
{
    use ResponseTrait;
    protected $userData, $currentDateTime;

    public function initController(RequestInterface $request, ResponseInterface $response, LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        try {
            $this->userData        = $request->userData;
            $this->currentDateTime = $request->currentDateTime;
        } catch (\Throwable $th) {
        }
    }

    public function index()
    {
        return $this->failForbidden('[E-AUTH-000] Forbidden Access');
    }

    public function getDataStatistik()
    {
        $validation =   $this->parametersValidator();
        if($validation !== true) return $this->fail($validation);

        $tanggalAwal           =    $this->request->getVar('tanggalAwal');
        $tanggalAkhir          =    $this->request->getVar('tanggalAkhir');
        $tanggalPeriodeAwalTS  =    strtotime($tanggalAwal);
        $tanggalPeriodeAkhirTS =    strtotime($tanggalAkhir);
        $jumlahHariPeriode     =    ($tanggalPeriodeAkhirTS - $tanggalPeriodeAwalTS) / (60 * 60 * 24);

        if ($jumlahHariPeriode > 60) return throwResponseNotAcceptable('Rentang tanggal tidak boleh lebih dari 60 hari');
        if ($jumlahHariPeriode < 1)  return throwResponseNotAcceptable('Rentang tanggal yang dipilih tidak valid');

        $arrTanggalPeriode  =   [];
        $tsLoop             =   $tanggalPeriodeAwalTS;

        while ($tsLoop <= $tanggalPeriodeAkhirTS) {
            $arrTanggalPeriode[]=   date('d M', $tsLoop);
            $tsLoop             =   strtotime('+1 day', $tsLoop);
        }

        $reviewMarketingModel   =   new ReviewMarketingModel();
        $arrJenisReview         =   ['Total Review', 'Total Marketing'];
        $arrJenisReviewHex      =   ['#1f6bff', '#c1cbdf'];
        $dataGrafikDB           =   $reviewMarketingModel->getDataGrafikReview($tanggalAwal, $tanggalAkhir);
        $dataGrafikReview       =   [];

        foreach($arrJenisReview as $mainIndex => $jenisReview){
            $dataGrafik =   array_fill(0, count($arrTanggalPeriode), 0);

            foreach ($dataGrafikDB as $dataDB) {
                $tanggalIndex   =   array_search($dataDB->TANGGALBULAN, $arrTanggalPeriode);
                if($tanggalIndex !== false){
                    switch ($jenisReview) {
                        case 'Total Review':
                            $dataGrafik[$tanggalIndex]  =   (int)$dataDB->TOTALREVIEW;
                            break;
                        case 'Total Marketing':
                            $dataGrafik[$tanggalIndex]  =   (int)$dataDB->TOTALMARKETING;
                            break;
                    }
                }
            }

            $dataGrafikReview[]   =   [
                'color'                     =>  $arrJenisReviewHex[$mainIndex],
                'borderColor'               =>  $arrJenisReviewHex[$mainIndex],
                'borderWidth'               =>  1.5,
                'pointBackgroundColor'      =>  'app.color.componentBg',
                'pointBorderWidth'          =>  1.5,
                'pointRadius'               =>  4,
                'pointHoverBackgroundColor' =>  $arrJenisReviewHex[$mainIndex],
                'pointHoverBorderColor'     =>  $arrJenisReviewHex[$mainIndex],
                'pointHoverRadius'          =>  7,
                'label'                     =>  $jenisReview,
                'data'                      =>  $dataGrafik,
                'tension'                   =>  0.4,
            ];
        }

        $ringkasanDB    =   $reviewMarketingModel->getDataRingkasanReview($tanggalAwal, $tanggalAkhir);
        $totalReview    =   (int)($ringkasanDB['TOTALREVIEW']   ?? 0);
        $totalMarketing =   (int)($ringkasanDB['TOTALMARKETING'] ?? 0);
        $ratingRerata   =   round((float)($ringkasanDB['RATINGRERATA'] ?? 0), 2);
        $rerataHarian   =   $jumlahHariPeriode > 0 ? number_format(($totalReview / $jumlahHariPeriode), 1, '.', ',') : 0;
        $dataRingkasan  =   [
            'totalReview'   =>  $totalReview,
            'ratingRerata'  =>  $ratingRerata,
            'totalMarketing'=>  $totalMarketing,
            'rerataHarian'  =>  $rerataHarian,
        ];

        $dataPeringkat  =   $reviewMarketingModel->getDataPeringkatMarketing($tanggalAwal, $tanggalAkhir);
        $dataTableReview=   $this->getDataTableReview(true);

        return $this->setResponseFormat('json')->respond([
            'jumlahHariPeriode' => $jumlahHariPeriode,
            'arrTanggalPeriode' => $arrTanggalPeriode,
            'dataGrafikReview'  => $dataGrafikReview,
            'dataRingkasan'     => $dataRingkasan,
            'dataPeringkat'     => $dataPeringkat,
            'dataTableReview'   => $dataTableReview
        ]);
    }

    public function getDataTableReview($return = false)
    {
        if(!$return){
            $validation =   $this->parametersValidator();
            if($validation !== true) return $this->fail($validation);
        }

        $mainOperation          =   new MainOperation();
        $reviewMarketingModel   =   new ReviewMarketingModel();

        $pageNumber     =   $this->request->getVar('pageNumber')  ? (int)$this->request->getVar('pageNumber')  : 1;
        $dataPerPage    =   $this->request->getVar('dataPerPage') ? (int)$this->request->getVar('dataPerPage') : 10;
        $tanggalAwal    =   $this->request->getVar('tanggalAwal');
        $tanggalAwal    =   date('Y-m-d', strtotime($tanggalAwal));
        $tanggalAkhir   =   $this->request->getVar('tanggalAkhir');
        $tanggalAkhir   =   date('Y-m-d', strtotime($tanggalAkhir));

        $baseDataReview =  $reviewMarketingModel->getDataDaftarReview($tanggalAwal, $tanggalAkhir);
        $totalDataReview=  $baseDataReview->countAllResults(false);
        $pageProperty   =  $mainOperation->generatePageProperty($pageNumber, $dataPerPage, $totalDataReview);
        $dataReview     =  [];

        if ($totalDataReview > 0) $dataReview   =   $baseDataReview->asObject()->findAll($dataPerPage, ($pageNumber - 1) * $dataPerPage);
        $dataReturn     =   [
            'dataReview'   => $dataReview,
            'pageProperty' => $pageProperty,
        ];
        
        if($return) return $dataReturn;
        return $this->setResponseFormat('json')->respond(['dataTableReview' => $dataReturn]);
    }

    private function parametersValidator($isTableReview = false)
    {
        $rules  =   [
            'tanggalAwal'   =>  ['label' => 'Tanggal Awal',  'rules' => 'required|valid_date'],
            'tanggalAkhir'  =>  ['label' => 'Tanggal Akhir', 'rules' => 'required|valid_date'],
        ];

        if ($isTableReview) {
            if(!$this->validate(array_merge($rules, APP_PAGE_PROPERTY_DEFAULT_RULES), APP_PAGE_PROPERTY_DEFAULT_MESSAGES)) return $this->fail($this->validator->getErrors());
        } else {
            if(!$this->validate($rules)) return $this->fail($this->validator->getErrors());
        }

        return true;
    }
}
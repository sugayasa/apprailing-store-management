<?php

namespace App\Controllers\Pengaturan;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use CodeIgniter\HTTP\Exceptions\HTTPException;
use App\Libraries\HmacHandler;
use App\Libraries\CacheDB;
use App\Models\MainOperation;
// use App\Models\Pengaturan\VariabelSistemModel;
// use App\Libraries\StorageFactory;

class VariabelSistem extends ResourceController
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

    public function syncDataBarangSistemUtama()
    {
        try {
            $client =   \Config\Services::curlrequest();
            $hmac   =   new HmacHandler();

            $payload        =   ['dataTipe' => 'BarangSistemUtama'];
            $headers        =   $hmac->generateHeaders($payload);
            $response       =   $client->request('POST', API_RICH_GROUP_URL_MAIN . API_RICH_GROUP_URL_DATA_BARANG, [
                'headers'           =>  $headers,
                'json'              =>  $payload,
                'timeout'           =>  5,
                'connect_timeout'   =>  10,
            ]);

            $responseData   =   json_decode($response->getBody(), true);
            $responseStatus =   $responseData['status'];

            switch((int)$responseStatus){
                case 200    :
                    $cacheDB    =   new CacheDB();
                    $dataBarang =   $responseData['dataBarang'] ?? [];
                    $cacheKey   =   $cacheDB->getCacheKeyName('dataBarangSistemUtama');
                    
                    $cacheDB->clear($cacheKey);
                    $cacheDB->remember($cacheKey, 0, function() use ($dataBarang) {
                        return $dataBarang;
                    });
                    return throwResponseOK("[S-SYNC-000] Sinkronisasi data barang sistem utama berhasil dilakukan");
                    break;
                default     :
                    return throwResponseNotAcceptable(
                        "[E-SYNC-{$responseStatus}] Sinkronisasi data barang sistem utama gagal dilakukan: " . ($responseData['msg'] ?? 'Unknown Error')
                    );
                    break;
            }

        } catch (HTTPException $e) {
            $statusCode = $e->getCode();

            switch ($statusCode) {
                case 403:
                    return $this->failForbidden('[E-SYNC-403] Forbidden: ' . $e->getMessage());
                case 500:
                    return $this->failServerError('[E-SYNC-500] Server Error: ' . $e->getMessage());
                default:
                    return $this->fail('[E-SYNC-' . $statusCode . '] HTTP Error: ' . $e->getMessage(), $statusCode);
            }

        } catch (\CodeIgniter\HTTP\Exceptions\ConnectionException $e) {
            return throwResponseError(
                504,
                '[E-SYNC-504] Gateway Timeout: Tidak dapat terhubung ke server utama. ' . $e->getMessage()
            );

        } catch (\Throwable $e) {
            return throwResponseError(
                500,
                '[E-SYNC-999] Unexpected Error: ' . $e->getMessage()
            );
        }
    }

    public function getDataBarangSistemUtama()
    {
        $rules      =   [
            'pageNumber'    =>  ['label' => 'Page Number', 'rules' => 'required|integer'],
            'dataPerPage'   =>  ['label' => 'Data Per Page', 'rules' => 'required|integer'],
            'searchKeyword' =>  ['label' => 'Keyword Pencarian', 'rules' => 'permit_empty|string'],
        ];

        $messages   =   [
            'pageNumber'    =>  [
                'required'  =>  'Data kiriman tidak valid',
                'integer'   =>  'Data kiriman tidak valid'
            ],
            'dataPerPage'   =>  [
                'required'  =>  'Data kiriman tidak valid',
                'integer'   =>  'Data kiriman tidak valid'
            ],
            'searchKeyword' =>  [
                'string'    =>  'Keyword pencarian harus berupa teks'
            ]
        ];

        if(!$this->validate($rules, $messages)) return $this->fail($this->validator->getErrors());

        $pageNumber     =   $this->request->getVar('pageNumber') ? (int)$this->request->getVar('pageNumber') : 1;
        $dataPerPage    =   $this->request->getVar('dataPerPage') ? (int)$this->request->getVar('dataPerPage') : 8;
        $searchKeyword  =   $this->request->getVar('searchKeyword') ? $this->request->getVar('searchKeyword') : '';

        try {
            $cacheDB    =   new CacheDB();
            $cacheKey   =   $cacheDB->getCacheKeyName('dataBarangSistemUtama');
            $dataBarang =   $cacheDB->get($cacheKey);

            if ($dataBarang === null) {
                return throwResponseNotFound(
                    "[E-DATA-404] Data barang sistem utama tidak ditemukan. Silakan lakukan sinkronisasi data terlebih dahulu."
                );
            }
            
            $dataBarang =   array_filter($dataBarang, function($item) use ($searchKeyword) {
                log_message('debug', 'Filtering item: ' . json_encode($item) . ' with keyword: ' . $searchKeyword);
                log_message('debug', 'is searchKeyword empty: ' . (empty($searchKeyword) ? 'true' : 'false'));
                if (empty($searchKeyword)) return true;
                $searchKeywordLower =   strtolower($searchKeyword);

                return strpos(strtolower($item['NAMAMERK']), $searchKeywordLower) !== false ||
                       strpos(strtolower($item['KATEGORIBARANG']), $searchKeywordLower) !== false ||
                       strpos(strtolower($item['KUALITASBARANG']), $searchKeywordLower) !== false ||
                       strpos(strtolower($item['FINISHBARANG']), $searchKeywordLower) !== false ||
                       strpos(strtolower($item['NAMAKODEBARANG']), $searchKeywordLower) !== false;
            });
            
            $mainOperation  =   new MainOperation();
            $totalNumberData=   count($dataBarang);
            $pageProperty   =   $mainOperation->generatePageProperty($pageNumber, $dataPerPage, $totalNumberData);

            if ($totalNumberData === 0) {
                $dataReturn =   [
                    "listData"      =>  [],
                    "pageProperty"  =>  $pageProperty
                ];

                return throwResponseNotFound(
                    "Tidak ada data barang yang ditemukan",
                    $dataReturn
                );
            }

            $dataBarang     =   array_slice($dataBarang, ($pageNumber - 1) * $dataPerPage, $dataPerPage);
            $dataBarang     =   encodeDatabaseArrayResultKey($dataBarang, ['IDBARANG']);

            return throwResponseOK(
                "[S-DATA-000] Data barang sistem utama berhasil diambil",
                [
                    'listData'  =>  $dataBarang,
                    'pageProperty'  =>  $pageProperty
                ]
            );
        } catch (\Throwable $e) {
            return throwResponseError(
                500,
                '[E-DATA-999] Unexpected Error: ' . $e->getMessage()
            );
        }
    }
}

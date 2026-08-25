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
use App\Models\Pengaturan\VariabelSistemModel;

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

    public function getRowPengaturanSistem()
    {
        $rules      =   [
            'searchKeyword' =>  ['label' => 'Kata Kunci Pencarian', 'rules' => 'permit_empty|string']
        ];

        $messages   =   [];

        if(!$this->validate($rules, $messages)) return $this->fail($this->validator->getErrors());

        $variabelSistemModel=   new VariabelSistemModel();
        $searchKeyword      =   $this->request->getVar('searchKeyword');
        $dataPengaturan     =   $variabelSistemModel->getDataPengaturanSistem($searchKeyword);
        $rowPengaturan      =   '';

        if(empty($dataPengaturan) || is_null($dataPengaturan)) return throwResponseNotFound("Tidak ada data pengaturan yang ditemukan");

        foreach ($dataPengaturan as $keyPengaturan) {
            $idPengaturan   =   $keyPengaturan->IDPENGATURANSISTEM;
            $elemInput      =   '';

            switch($idPengaturan){
                case 1   :
                    $elemInput  =   $this->generateComboBoxAPIOngkirProvider($keyPengaturan->DATA);
                    break;
                case 2   :
                default :
                    $elemInput  =   '<input type="text" class="pengaturan-sistem-input form-control" value="' . $keyPengaturan->DATA . '">';
                    break;
            }

            $rowPengaturan  .=  '<tr data-id-pengaturan="' . hashidEncode($idPengaturan) . '">';
            $rowPengaturan  .=  '<td width="75%">';
            $rowPengaturan  .=  $keyPengaturan->NAMA . '<br><small class="text-muted">' . $keyPengaturan->DESKRIPSI . '</small>';
            $rowPengaturan  .=  '</td>';
            $rowPengaturan  .=  '<td>' . $elemInput . '</td>';
            $rowPengaturan  .=  '</tr>';
        }

        return $this->setResponseFormat('json')->respond([
            "rowPengaturan" =>  $rowPengaturan
        ]);
    }

    private function generateComboBoxAPIOngkirProvider($selectedValue)
    {
        $variabelSistemModel=   new VariabelSistemModel();
        $dataProvider       =   $variabelSistemModel->getDataPengaturanSistemAPIOngkirProvider();
        $comboBox           =   '<select class="pengaturan-sistem-input form-select">';

        foreach ($dataProvider as $key => $value) {
            $selected   =   ($value->IDAPIPROVIDER === $selectedValue) ? ' selected' : '';
            $comboBox   .=   '<option value="' . hashidEncode($value->IDAPIPROVIDER) . '"' . $selected . '>' . $value->NAMAPROVIDER . '</option>';
        }
        $comboBox       .=  '</select>';

        return $comboBox;
    }

    public function simpanPengaturanSistem()
    {
        $rules      =   [
            'dataPengaturan'                    => 'required',
            'dataPengaturan.*.idPengaturan'     => 'required|alpha_numeric',
            'dataPengaturan.*.valuePengaturan'  => 'permit_empty|string'
        ];

        $messages   =   [
            'dataPengaturan'    =>  [
                'required'  =>  'Data kiriman tidak valid'
            ],
            'dataPengaturan.*.idPengaturan' =>  [
                'alpha_numeric' =>  'Data kiriman tidak valid'
            ]
        ];

        if(!$this->validate($rules, $messages)) return $this->fail($this->validator->getErrors());

        $mainOperation  =   new MainOperation();
        $dataPengaturan =   $this->request->getVar('dataPengaturan');
        $totalUpdate    =   0;

        foreach($dataPengaturan as $keyPengaturan){
            $idPengaturan   =   hashidDecode($keyPengaturan->idPengaturan) ?? null;
            $valuePengaturan=   $keyPengaturan->valuePengaturan ?? null;
            
            switch ($idPengaturan) {
                case 1  :   $valuePengaturan    =   hashidDecode($valuePengaturan); break;
                case 2  :   
                default :   $valuePengaturan    =   $valuePengaturan; break;
            }

            $procUpdate  =   $mainOperation->updateDataTable(
                'a_pengaturansistem',
                ['DATA' => $valuePengaturan],
                ['IDPENGATURANSISTEM' => $idPengaturan]
            );

            if($procUpdate['status']) $totalUpdate++;
        }

        if($totalUpdate > 0) return throwResponseOK($totalUpdate." Data pengaturan sistem berhasil diperbarui");
        else return throwResponseNotAcceptable("[E-UPDATE-000] Tidak ada data pengaturan sistem yang diperbarui");
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

    public function getDataWilayahOngkir()
    {
        $rules      =   [
            'keywordProvinsi'       =>  ['label' => 'Keyword Pencarian Provinsi', 'rules' => 'permit_empty|string'],
            'keywordKotaKabupaten'  =>  ['label' => 'Keyword Pencarian Kota/Kabupaten', 'rules' => 'permit_empty|string'],
            'keyword'               =>  ['label' => 'Keyword Pencarian', 'rules' => 'permit_empty|string'],
        ];

        $messages   =   [
            'keywordProvinsi'   =>  [
                'string'    =>  'Keyword pencarian provinsi harus berupa teks'
            ],
            'keywordKotaKabupaten'  =>  [
                'string'    =>  'Keyword pencarian kota/kabupaten harus berupa teks'
            ],
            'keyword' =>  [
                'string'    =>  'Keyword pencarian harus berupa teks'
            ]
        ];

        if(!$this->validate(array_merge($rules, APP_PAGE_PROPERTY_DEFAULT_RULES), array_merge($messages, APP_PAGE_PROPERTY_DEFAULT_MESSAGES))) return $this->fail($this->validator->getErrors());

        $mainOperation      =   new MainOperation();
        $variabelSistemModel=   new VariabelSistemModel();

        $pageNumber             =   $this->request->getVar('pageNumber') ? (int)$this->request->getVar('pageNumber') : 1;
        $dataPerPage            =   $this->request->getVar('dataPerPage') ? (int)$this->request->getVar('dataPerPage') : 25;
        $keywordProvinsi        =   $this->request->getVar('keywordProvinsi') ? $this->request->getVar('keywordProvinsi') : '';
        $keywordKotaKabupaten   =   $this->request->getVar('keywordKotaKabupaten') ? $this->request->getVar('keywordKotaKabupaten') : '';
        $keyword                =   $this->request->getVar('keyword') ? $this->request->getVar('keyword') : '';

        $baseData       =   $variabelSistemModel->getDataWilayahOngkir($keywordProvinsi, $keywordKotaKabupaten, $keyword);
        $totalNumberData=   $baseData->countAllResults(false);
        $pageProperty   =   $mainOperation->generatePageProperty($pageNumber, $dataPerPage, $totalNumberData);

        if($totalNumberData > 0){
            $listData   =   $baseData->get($dataPerPage, ($pageNumber - 1) * $dataPerPage)->getResultObject();

            return $this->setResponseFormat('json')->respond([
                "listData"      =>  $listData,
                "pageProperty"  =>  $pageProperty
            ]);
        } else {
            $dataReturn =   [
                "listData"      =>  [],
                "pageProperty"  =>  $pageProperty
            ];
            return throwResponseNotFound('Tidak ada data yang ditemukan', $dataReturn);
        }
    }

    public function syncDataWilayahOngkir()
    {
        $variabelSistemModel    =   new VariabelSistemModel();
        $dataPengaturan         =   $variabelSistemModel->getDataPengaturanSistem();
        $idAPIOngkirProvider    =   null;
        $apiOngkirProviderKey   =   null;

        foreach ($dataPengaturan as $keyPengaturan) {
            switch ($keyPengaturan->IDPENGATURANSISTEM) {
                case 1  :   $idAPIOngkirProvider    =   $keyPengaturan->DATA; break;
                case 2  :   $apiOngkirProviderKey   =   trim($keyPengaturan->DATA); break;
                default :   break;
            }
        }

        if (empty($idAPIOngkirProvider)) {
            return throwResponseNotAcceptable('[E-SYNC-101] API Ongkir Provider belum ditentukan pada menu Variabel Sistem');
        }

        if (empty($apiOngkirProviderKey)) {
            return throwResponseNotAcceptable('[E-SYNC-102] API key Ongkir belum diatur pada menu Variabel Sistem');
        }
        
        $mainOperation  =   new MainOperation();
        $baseURLAPI     =   $mainOperation->getBaseURLAPIByProvider($idAPIOngkirProvider);

        if (empty($baseURLAPI) || is_null($baseURLAPI)) {
            return throwResponseNotAcceptable('[E-SYNC-103] Data URL API Ongkir tidak ditemukan untuk provider yang ditetapkan');
        }

        switch ($idAPIOngkirProvider) {
            case 30 :   
            default :   return $this->getDataWilayahOngkirFromAPICoId($baseURLAPI, $apiOngkirProviderKey); break;
        }
    }

    private function getDataWilayahOngkirFromAPICoId($baseURLAPI, $apiOngkirProviderKey)
    {
        try {
            $client     =   \Config\Services::curlrequest();
            $response   =   $client->request('GET', $baseURLAPI . '/courier/v1/locations/provinces', [
                'headers'           =>  ['x-api-co-id' => $apiOngkirProviderKey],
                'timeout'           =>  5,
                'connect_timeout'   =>  10,
            ]);

            $responseData   =   json_decode($response->getBody(), true);

            if (!($responseData['is_success'] ?? false)) {
                return throwResponseNotAcceptable(
                    '[E-SYNC-201] Gagal mengambil data provinsi: ' . ($responseData['message'] ?? 'Unknown Error')
                );
            }

            $dataProvinsi   =   $responseData['data'] ?? [];

            if (empty($dataProvinsi)) return throwResponseNotFound('[E-SYNC-404] Tidak ada data provinsi yang dikembalikan oleh provider');

            $mainOperation          =   new MainOperation();
            $variabelSistemModel    =   new VariabelSistemModel();
            $totalInsertProvinsi    =   0;
            $totalUpdateProvinsi    =   0;
            $totalInsertKota        =   0;
            $totalUpdateKota        =   0;
            $totalInsertKecamatan   =   0;
            $totalUpdateKecamatan   =   0;

            foreach ($dataProvinsi as $keyProvinsi) {
                $namaProvinsi   =   trim($keyProvinsi['name'] ?? '');
                $kodeProvinsi   =   trim($keyProvinsi['code'] ?? '');

                if (empty($namaProvinsi)) continue;

                $isProvinsiExist    =   $mainOperation->isDataExist(
                    'm_wilayahprovinsi',
                    ['NAMAPROVINSI' => $namaProvinsi],
                    'dbcustomer'
                );

                $idWilayahProvinsi  =   null;

                if (!$isProvinsiExist) {
                    $procInsert =   $mainOperation->insertDataTable(
                        'm_wilayahprovinsi',
                        [
                            'NAMAPROVINSI'      =>  $namaProvinsi,
                            'KODEAPIPROVINSI'   =>  $kodeProvinsi,
                        ],
                        'dbcustomer'
                    );

                    if ($procInsert['status']) {
                        $totalInsertProvinsi++;
                        $idWilayahProvinsi  =   $procInsert['insertID'] ?? null;
                    }
                } else {
                    $procUpdate =   $mainOperation->updateDataTable(
                        'm_wilayahprovinsi',
                        ['KODEAPIPROVINSI' => $kodeProvinsi],
                        ['NAMAPROVINSI' => $namaProvinsi],
                        'dbcustomer'
                    );

                    if ($procUpdate['status']) $totalUpdateProvinsi++;

                    $idWilayahProvinsi  =   $variabelSistemModel->getIdWilayahProvinsiByName($namaProvinsi);
                }

                if (empty($idWilayahProvinsi) || empty($kodeProvinsi)) continue;

                try {
                    $responseKota   =   $client->request('GET', $baseURLAPI . '/courier/v1/locations/cities', [
                        'headers'           =>  ['x-api-co-id' => $apiOngkirProviderKey],
                        'query'             =>  ['province' => $kodeProvinsi],
                        'timeout'           =>  5,
                        'connect_timeout'   =>  10,
                    ]);

                    $responseKotaData    =   json_decode($responseKota->getBody(), true);

                    if (!($responseKotaData['is_success'] ?? false)) {
                        log_message('error', '[E-SYNC] Gagal mengambil data kota untuk provinsi ' . $namaProvinsi . ': ' . ($responseKotaData['message'] ?? 'Unknown Error'));
                        continue;
                    }

                    $dataKota   =   $responseKotaData['data'] ?? [];

                    foreach ($dataKota as $keyKota) {
                        $namaKota   =   trim($keyKota['name'] ?? '');
                        $kodeKota   =   trim($keyKota['code'] ?? '');

                        if (empty($namaKota)) continue;
                        if (substr($namaKota, 0, 4) !== 'Kota' && substr($namaKota, 0, 9) !== 'Kabupaten') {
                            $namaKota   =   'Kabupaten ' . $namaKota;
                        }

                        $isKotaExist    =   $mainOperation->isDataExist(
                            'm_wilayahkotakabupaten',
                            [
                                'IDWILAYAHPROVINSI' =>  $idWilayahProvinsi,
                                'NAMAKOTAKABUPATEN' =>  $namaKota,
                            ],
                            'dbcustomer'
                        );

                        $idWilayahKotaKabupaten  =   null;

                        if (!$isKotaExist) {
                            $procInsertKota =   $mainOperation->insertDataTable(
                                'm_wilayahkotakabupaten',
                                [
                                    'IDWILAYAHPROVINSI'     =>  $idWilayahProvinsi,
                                    'NAMAKOTAKABUPATEN'     =>  $namaKota,
                                    'KODEAPIKOTAKABUPATEN'  =>  $kodeKota,
                                ],
                                'dbcustomer'
                            );

                            if ($procInsertKota['status']) {
                                $totalInsertKota++;
                                $idWilayahKotaKabupaten  =   $procInsertKota['insertID'] ?? null;
                            }
                        } else {
                            $procUpdateKota =   $mainOperation->updateDataTable(
                                'm_wilayahkotakabupaten',
                                ['KODEAPIKOTAKABUPATEN' => $kodeKota],
                                [
                                    'IDWILAYAHPROVINSI' =>  $idWilayahProvinsi,
                                    'NAMAKOTAKABUPATEN' =>  $namaKota,
                                ],
                                'dbcustomer'
                            );

                            if ($procUpdateKota['status']) $totalUpdateKota++;

                            $idWilayahKotaKabupaten  =   $variabelSistemModel->getIdWilayahKotaKabupatenByName($idWilayahProvinsi, $namaKota);
                        }

                        if (empty($idWilayahKotaKabupaten) || empty($kodeKota)) continue;

                        try {
                            $responseKecamatan   =   $client->request('GET', $baseURLAPI . '/courier/v1/locations/districts', [
                                'headers'           =>  ['x-api-co-id' => $apiOngkirProviderKey],
                                'query'             =>  ['city' => $kodeKota],
                                'timeout'           =>  5,
                                'connect_timeout'   =>  10,
                            ]);

                            $responseKecamatanData  =   json_decode($responseKecamatan->getBody(), true);

                            if (!($responseKecamatanData['is_success'] ?? false)) {
                                log_message('error', '[E-SYNC] Gagal mengambil data kecamatan untuk kota ' . $namaKota . ': ' . ($responseKecamatanData['message'] ?? 'Unknown Error'));
                                continue;
                            }

                            $dataKecamatan  =   $responseKecamatanData['data'] ?? [];

                            foreach ($dataKecamatan as $keyKecamatan) {
                                $namaKecamatan   =   trim($keyKecamatan['name'] ?? '');
                                $kodeKecamatan   =   trim($keyKecamatan['code'] ?? '');

                                if (empty($namaKecamatan)) continue;

                                $isKecamatanExist   =   $mainOperation->isDataExist(
                                    'm_wilayahkecamatan',
                                    [
                                        'IDWILAYAHKOTAKABUPATEN' =>  $idWilayahKotaKabupaten,
                                        'NAMAKECAMATAN'          =>  $namaKecamatan,
                                    ],
                                    'dbcustomer'
                                );

                                if (!$isKecamatanExist) {
                                    $procInsertKecamatan    =   $mainOperation->insertDataTable(
                                        'm_wilayahkecamatan',
                                        [
                                            'IDWILAYAHKOTAKABUPATEN' =>  $idWilayahKotaKabupaten,
                                            'NAMAKECAMATAN'          =>  $namaKecamatan,
                                            'KODEAPIKECAMATAN'       =>  $kodeKecamatan,
                                        ],
                                        'dbcustomer'
                                    );

                                    if ($procInsertKecamatan['status']) $totalInsertKecamatan++;
                                } else {
                                    $procUpdateKecamatan    =   $mainOperation->updateDataTable(
                                        'm_wilayahkecamatan',
                                        ['KODEAPIKECAMATAN' => $kodeKecamatan],
                                        [
                                            'IDWILAYAHKOTAKABUPATEN' =>  $idWilayahKotaKabupaten,
                                            'NAMAKECAMATAN'          =>  $namaKecamatan,
                                        ],
                                        'dbcustomer'
                                    );

                                    if ($procUpdateKecamatan['status']) $totalUpdateKecamatan++;
                                }
                            }
                        } catch (\Throwable $e) {
                            log_message('error', '[E-SYNC] Gagal mengambil data kecamatan untuk kota ' . $namaKota . ': ' . $e->getMessage());
                        }
                    }
                } catch (\Throwable $e) {
                    log_message('error', '[E-SYNC] Gagal mengambil data kota untuk provinsi ' . $namaProvinsi . ': ' . $e->getMessage());
                }
            }

            return throwResponseOK(
                "[S-SYNC-200] Sinkronisasi data wilayah ongkir berhasil dilakukan ({$totalInsertProvinsi} provinsi baru, {$totalUpdateProvinsi} provinsi diperbarui, {$totalInsertKota} kota baru, {$totalUpdateKota} kota diperbarui, {$totalInsertKecamatan} kecamatan baru, {$totalUpdateKecamatan} kecamatan diperbarui)"
            );

        } catch (HTTPException $e) {
            $statusCode =   $e->getCode();

            switch ($statusCode) {
                case 401:
                    return throwResponseNotAcceptable('[E-SYNC-401] API key Ongkir tidak valid: ' . $e->getMessage());
                case 403:
                    return throwResponseNotAcceptable('[E-SYNC-403] Forbidden: ' . $e->getMessage());
                case 500:
                    return throwResponseNotAcceptable('[E-SYNC-500] Server Error: ' . $e->getMessage());
                default:
                    return throwResponseNotAcceptable('[E-SYNC-' . $statusCode . '] HTTP Error: ' . $e->getMessage(), $statusCode);
            }
        } catch (\Throwable $e) {
            return throwResponseError(
                500,
                '[E-SYNC-999] Unexpected Error: ' . $e->getMessage()
            );
        }
    }
}

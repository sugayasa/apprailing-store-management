<?php

namespace App\Controllers\Utilitas;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use CodeIgniter\HTTP\Exceptions\HTTPException;
use App\Models\MainOperation;
use App\Models\Utilitas\CekOngkosKirimModel;

class CekOngkosKirim extends ResourceController
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

    public function cekOngkosKirim()
    {
        $rules      =   [
            'idKecamatanAsal'   =>  ['label' => 'Wilayah Asal', 'rules' => 'required|alpha_numeric'],
            'idKecamatanTujuan' =>  ['label' => 'Wilayah Tujuan', 'rules' => 'required|alpha_numeric'],
            'berat'             =>  ['label' => 'Berat Barang', 'rules' => 'required|numeric|greater_than[0]'],
            'nilaiBarang'       =>  ['label' => 'Nilai Barang', 'rules' => 'permit_empty|numeric|greater_than[0]'],
            'asuransi'          =>  ['label' => 'Asuransi', 'rules' => 'in_list[0,1]'],
            'panjang'           =>  ['label' => 'Panjang', 'rules' => 'permit_empty|numeric|greater_than[0]'],
            'lebar'             =>  ['label' => 'Lebar', 'rules' => 'permit_empty|numeric|greater_than[0]'],
            'tinggi'            =>  ['label' => 'Tinggi', 'rules' => 'permit_empty|numeric|greater_than[0]'],
        ];

        $messages   =   [
            'idKecamatanAsal'   =>  [
                'required'      =>  'Wilayah asal belum dipilih',
                'alpha_numeric' =>  'Wilayah asal yang dipilih tidak valid.'
            ],
            'idKecamatanTujuan' =>  [
                'required'      =>  'Wilayah tujuan belum dipilih',
                'alpha_numeric' =>  'Wilayah tujuan yang dipilih tidak valid.'
            ],
            'asuransi'  =>  [
                'in_list'   =>  'Status asuransi yang dipilih tidak valid. Pilih salah satu dari opsi yang tersedia.'
            ]
        ];

        if(!$this->validate($rules, $messages)) return $this->fail($this->validator->getErrors());

        $mainOperation          =   new MainOperation();
        $idAPIOngkirProvider    =   $mainOperation->getDataPengaturanSistemById(1);
        $apiOngkirProviderKey   =   $mainOperation->getDataPengaturanSistemById(2);

        if (empty($idAPIOngkirProvider)) {
            return throwResponseNotAcceptable('[E-SYNC-101] API Ongkir Provider belum ditentukan pada menu Variabel Sistem');
        }

        if (empty($apiOngkirProviderKey)) {
            return throwResponseNotAcceptable('[E-SYNC-102] API key Ongkir belum diatur pada menu Variabel Sistem');
        }
        
        $baseURLAPI =   $mainOperation->getBaseURLAPIByProvider($idAPIOngkirProvider);

        if (empty($baseURLAPI) || is_null($baseURLAPI)) {
            return throwResponseNotAcceptable('[E-SYNC-103] Data URL API Ongkir tidak ditemukan untuk provider yang ditetapkan');
        }

        $idKecamatanAsal       =   $this->request->getVar('idKecamatanAsal');
        $idKecamatanAsal       =   hashidDecode($idKecamatanAsal) ?? null;
        $idKecamatanTujuan     =   $this->request->getVar('idKecamatanTujuan');
        $idKecamatanTujuan     =   hashidDecode($idKecamatanTujuan) ?? null;
        $berat                 =   $this->request->getVar('berat');
        $nilaiBarang           =   $this->request->getVar('nilaiBarang') ?? '';
        $asuransi              =   $this->request->getVar('asuransi') == '1' ? true : false;
        $panjang               =   $this->request->getVar('panjang') ?? '';
        $lebar                 =   $this->request->getVar('lebar') ?? '';
        $tinggi                =   $this->request->getVar('tinggi') ?? '';

        $cekOngkosKirimModel    =   new CekOngkosKirimModel();
        $kodeAPIKecamatanAsal   =   $cekOngkosKirimModel->getKodeAPIKecamatan($idKecamatanAsal);
        $kodeAPIKecamatanTujuan =   $cekOngkosKirimModel->getKodeAPIKecamatan($idKecamatanTujuan);

        if (empty($kodeAPIKecamatanAsal)) return throwResponseNotFound('[E-RATE-101] Data kode API kecamatan asal tidak ditemukan');
        if (empty($kodeAPIKecamatanTujuan)) return throwResponseNotFound('[E-RATE-102] Data kode API kecamatan tujuan tidak ditemukan');

        switch ($idAPIOngkirProvider) {
            case 30: // API Co ID
                return $this->cekOngkosKirimAPICoId([
                    'baseURLAPI'            =>  $baseURLAPI,
                    'apiOngkirProviderKey'  =>  $apiOngkirProviderKey,
                    'kodeAPIKecamatanAsal'  =>  $kodeAPIKecamatanAsal,
                    'kodeAPIKecamatanTujuan'=>  $kodeAPIKecamatanTujuan,
                    'berat'                 =>  $berat,
                    'nilaiBarang'           =>  $nilaiBarang,
                    'asuransi'              =>  $asuransi,
                    'panjang'               =>  $panjang,
                    'lebar'                 =>  $lebar,
                    'tinggi'                =>  $tinggi
                ]);
            default:
                return throwResponseNotAcceptable('[E-RATE-100] Provider API Ongkir tidak valid atau belum didukung');
        }
    }

    private function cekOngkosKirimAPICoId($arrParameters){
        extract($arrParameters);
        try {
            $client =   \Config\Services::curlrequest();

            $queryParams=   [
                'origin_district_code'      =>  $kodeAPIKecamatanAsal,
                'destination_district_code' =>  $kodeAPIKecamatanTujuan,
                'weight'                    =>  $berat,
                'insurance'                 =>  $asuransi ? 'true' : 'false',
            ];

            if ($nilaiBarang !== '') $queryParams['item_value']  =   (int)$nilaiBarang;
            if ($panjang !== '') $queryParams['length']          =   (int)$panjang;
            if ($lebar !== '') $queryParams['width']             =   (int)$lebar;
            if ($tinggi !== '') $queryParams['height']           =   (int)$tinggi;

            $response   =   $client->request('GET', $baseURLAPI . '/courier/v2/rates', [
                'headers'           =>  ['x-api-co-id' => $apiOngkirProviderKey],
                'query'             =>  $queryParams,
                'timeout'           =>  5,
                'connect_timeout'   =>  10,
            ]);

            $responseData   =   json_decode($response->getBody(), true);

            if (!($responseData['is_success'] ?? false)) {
                return throwResponseNotAcceptable(
                    '[E-RATE-201] Gagal mengambil data ongkos kirim: ' . ($responseData['message'] ?? 'Unknown Error')
                );
            }

            $dataRate   =   $responseData['data'] ?? [];
            if (empty($dataRate['rates'])) return throwResponseNotFound('[E-RATE-404] Tidak ada data tarif ongkos kirim yang dikembalikan oleh provider');

            return throwResponseOK(
                "[S-RATE-000] Data tarif ongkos kirim berhasil diambil",
                [
                    'listData'      =>  $dataRate['rates'] ?? [],
                    'parsingType'   =>  'API Co ID',
                ]
            );

        } catch (HTTPException $e) {
            $statusCode =   $e->getCode();

            switch ($statusCode) {
                case 401:
                    return throwResponseNotAcceptable('[E-RATE-401] API key Ongkir tidak valid: ' . $e->getMessage());
                case 402:
                    return throwResponseNotAcceptable('[E-RATE-402] Saldo API Ongkir tidak mencukupi');
                case 429:
                    return throwResponseNotAcceptable(429, '[E-RATE-429] Terlalu banyak permintaan ke provider, silakan coba lagi nanti');
                case 500:
                    return throwResponseNotAcceptable(500, '[E-RATE-500] Server Error: ' . $e->getMessage());
                default:
                    return throwResponseNotAcceptable('[E-RATE-' . $statusCode . '] HTTP Error: ' . $e->getMessage());
            }
        } catch (\Throwable $e) {
            return throwResponseNotAcceptable(
                500,
                '[E-RATE-999] Unexpected Error: ' . $e->getMessage()
            );
        }
    }
}

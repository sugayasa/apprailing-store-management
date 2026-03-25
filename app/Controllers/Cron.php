<?php
namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use Psr\Log\LoggerInterface;
use App\Libraries\CacheDB;
use App\Models\MainOperation;
use App\Models\CronModel;

class Cron extends ResourceController
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

    public function getPerubahanDataStok()
    {
        $cacheDB        =   new CacheDB();
        $cronModel      =   new CronModel();
        $mainOperation  =   new MainOperation();
        $dataRegional   =   $mainOperation->getDataDetailRegional();
        $cacheKey       =   "data_stok_per_regional";
        $dataCacheStok  =   $cacheDB->get($cacheKey);

        if($dataRegional){
            $arrDataStokPerRegional =   [];
            foreach($dataRegional as $detailRegional){
                $idKotaRegional             =   $detailRegional->IDKOTA;
                $namaDatabaseRegional       =   $detailRegional->NAMADATABASE;
                $arrDatabaseRegionalLain    =   $mainOperation->getDataDetailRegional([$idKotaRegional]);
                $arrDatabaseRegionalLain    =   array_map(function($item){ return $item->NAMADATABASE; }, $arrDatabaseRegionalLain);
                $detailStokPerRegional      =   $cronModel->getDataDetailStokPerRegional($namaDatabaseRegional, $arrDatabaseRegionalLain, $idKotaRegional);

                $dataCacheStokRegional      =   array_filter($dataCacheStok ?? [], function($item) use ($idKotaRegional) {
                    return $item['idKotaRegional'] == $idKotaRegional;
                });
                
                $dataCacheStokRegional  =   array_values($dataCacheStokRegional);
                $dataCacheStokRegional  =   !empty($dataCacheStokRegional) ? $dataCacheStokRegional[0]['detailStokPerRegional'] : [];
                $hashCache              =   md5(serialize($dataCacheStokRegional));
                $hashNew                =   md5(serialize($detailStokPerRegional));

                $arrPerubahan   =   [];
                if($hashCache !== $hashNew){
                    // Index data cache berdasarkan IDBARANG
                    $indexedCache   =   [];
                    foreach($dataCacheStokRegional as $itemCache){
                        $idBarang               =   is_object($itemCache) ? $itemCache->IDBARANG : $itemCache['IDBARANG'];
                        $indexedCache[$idBarang]=   $itemCache;
                    }
                    
                    // Index data database berdasarkan IDBARANG
                    $indexedDB      =   [];
                    foreach($detailStokPerRegional as $itemDB){
                        $idBarang               =   is_object($itemDB) ? $itemDB->IDBARANG : $itemDB['IDBARANG'];
                        $indexedDB[$idBarang]   =   $itemDB;
                    }
                    
                    // Compare setiap barang
                    $allIdBarang    =   array_unique(array_merge(array_keys($indexedCache), array_keys($indexedDB)));
                    
                    foreach($allIdBarang as $idBarang){
                        $oldData        =   $indexedCache[$idBarang] ?? null;
                        $newData        =   $indexedDB[$idBarang] ?? null;
                        $fields         =   ['STOKFISIK', 'STOKSOTERTAHAN', 'STOKBELUMKIRIM', 'STOKBARANGREJECT', 'STOKBARANGMUTASIREGIONAL'];
                        $changedFields  =   [];
                        
                        // Barang baru ditambahkan atau dihapus
                        if((is_null($oldData) && !is_null($newData)) || (!is_null($oldData) && is_null($newData))){
                            $changedFields[]=   $fields;
                            $insertData     =   true;
                            continue;
                        }
                        
                        // Compare field-field yang berubah
                        foreach($fields as $field){
                            $oldValue   =   is_object($oldData) ? $oldData->$field : $oldData[$field];
                            $newValue   =   is_object($newData) ? $newData->$field : $newData[$field];
                            
                            if($oldValue != $newValue){
                                $changedFields[]    =   $field;
                            }
                        }
                        
                        if(!empty($changedFields)){
                            $insertData     =   true;
                        }
                    }
                    
                    if(!empty($arrPerubahan)){
                        var_dump($arrPerubahan);
                    }
                } else {
                    echo "Data stok per regional dengan ID Kota Regional $idKotaRegional tidak mengalami perubahan.<br/>";
                }
                
                $arrDataStokPerRegional[]   =   [
                    "idKotaRegional"        =>  $idKotaRegional,
                    "detailStokPerRegional" =>  $detailStokPerRegional
                ];
            }
        
            $cacheDB->clear($cacheKey);
            $cacheDB->save($cacheKey, $arrDataStokPerRegional, 0);
        }
    }
}
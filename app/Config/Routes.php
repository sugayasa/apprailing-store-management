<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

/*
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Index');
$routes->setDefaultMethod('main');
$routes->setTranslateURIDashes(false);
$routes->set404Override('App\Controllers\Index::response404');
// The Auto Routing (Legacy) is very dangerous. It is easy to create vulnerable apps
// where controller filters or CSRF protection are bypassed.
// If you don't want to define all routes, please use the Auto Routing (Improved).
// Set `$autoRoutesImproved` to true in `app/Config/Feature.php` and set the following to true.
// $routes->setAutoRoute(false);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->post('/', 'Index::index');
$routes->get('/', 'Index::main');
$routes->get('/logoutPage', 'Index::main', ['as' => 'logoutPage']);
$routes->get('/loginPage', 'Index::loginPage');
$routes->post('/mainPage', 'Index::mainPage', ['filter' => 'auth:mustBeLoggedIn']);

$routes->post('access/check', 'Access::check');
$routes->get('access/logout/(:any)', 'Access::logout/$1');
$routes->get('access/captcha/(:any)', 'Access::captcha/$1');

$routes->get('cron/getPerubahanDataStok', 'Cron::getPerubahanDataStok');

$routes->get('databaseTool/migrate', 'DatabaseTool::migrate', ['filter' => 'databaseTool']);
$routes->get('databaseTool/rollback', 'DatabaseTool::rollback', ['filter' => 'databaseTool']);
$routes->get('databaseTool/seed/(:any)', 'DatabaseTool::seed/$1', ['filter' => 'databaseTool']);

$routes->group('access', ['filter' => 'auth:mustNotBeLoggedIn'], function($routes) {
    $routes->post('login', 'Access::login', ['filter' => 'auth:mustNotBeLoggedIn']);
});

$routes->group('access', ['filter' => 'auth:mustBeLoggedIn'], function($routes) {
    $functionRoute =   'Access';
    $routes->post('getDataOption', $functionRoute.'::getDataOption');
    $routes->post('getDataOptionByKey/(:any)/(:any)/(:any)', $functionRoute.'::getDataOptionByKey/$1/$2/$3');
    $routes->post('detailProfileSetting', $functionRoute.'::detailProfileSetting');
    $routes->post('saveDetailProfileSetting', $functionRoute.'::saveDetailProfileSetting');
});

$routes->group('assets', [], function($routes) {
    $routes->get('logoMerk/(:any)', 'Assets::logoMerk/$1');
    $routes->get('logoMarketplace/(:any)', 'Assets::logoMarketplace/$1');
    $routes->get('cardLevelLoyalti/(:any)', 'Assets::cardLevelLoyalti/$1');
    $routes->get('iconLevelLoyalti/(:any)', 'Assets::iconLevelLoyalti/$1');
    $routes->get('pdfKatalog/thumbnail/(:any)', 'Assets::pdfKatalogThumbnail/$1');
    $routes->get('pdfKatalog/file/(:any)', 'Assets::pdfKatalogFile/$1');
    $routes->get('photoBarang/(:any)', 'Assets::photoBarang/$1');
    $routes->get('imageMarketing/(:any)', 'Assets::imageMarketing/$1');
    $routes->get('imageSlideOnboarding/(:any)', 'Assets::imageSlideOnboarding/$1');
    $routes->get('imageSlideBanner/(:any)', 'Assets::imageSlideBanner/$1');
    $routes->get('imageSlideKolaborasi/produk/(:any)', 'Assets::imageSlideKolaborasiProduk/$1');
    $routes->get('imageSlideKolaborasi/thumbnail/(:any)', 'Assets::imageSlideKolaborasiThumbnail/$1');
    $routes->get('videoCompanyProfile/(:any)', 'Assets::videoCompanyProfile/$1');
    $routes->get('videoCaraPasang/(:any)', 'Assets::videoCaraPasang/$1');
    $routes->get('imageGaleriKlien/logo/(:any)', 'Assets::imageGaleriKlienLogo/$1');
    $routes->get('imageGaleriKlien/proyek/(:any)', 'Assets::imageGaleriKlienProyek/$1');
    $routes->get('imageGaleriProyek/(:any)', 'Assets::imageGaleriProyek/$1');
    $routes->get('customerAvatar/(:any)', 'Assets::customerAvatar/$1');
    $routes->get('customerMerk/(:any)', 'Assets::customerMerk/$1');
    $routes->get('customerSosmedMarketplace/(:any)', 'Assets::customerSosmedMarketplace/$1');
    $routes->get('customerProduk/(:any)', 'Assets::customerProduk/$1');
});

$routes->group('view', ['filter' => 'auth:mustBeLoggedIn'], function($routes) {
    $functionRoute =   'View';
    $routes->post('dashboard', $functionRoute.'::dashboard');
    $routes->post('katalog-produk', $functionRoute.'::katalogProduk');
    $routes->post('daftar-harga', $functionRoute.'::daftarHarga');
    $routes->post('stok-barang', $functionRoute.'::stokBarang');
    $routes->post('monitoring-mutasi-stok', $functionRoute.'::monitoringMutasiStok');
    $routes->post('utilitas-cek-ongkos-kirim', $functionRoute.'::utilitasCekOngkosKirim');
    $routes->post('pengaturan-level-menu', $functionRoute.'::pengaturanLevelMenu');
    $routes->post('pengaturan-daftar-pengguna', $functionRoute.'::pengaturanDaftarPengguna');
    $routes->post('pengaturan-variabel-sistem', $functionRoute.'::pengaturanVariabelSistem');
    $routes->post('customer-data-dasar-merk', $functionRoute.'::customerDataDasarMerk');
    $routes->post('customer-data-dasar-kategori-produk', $functionRoute.'::customerDataDasarKategoriProduk');
    $routes->post('customer-data-dasar-level-loyalti', $functionRoute.'::customerDataDasarLevelLoyalti');
    $routes->post('customer-data-dasar-sosmed-marketplace', $functionRoute.'::customerDataDasarSosmedMarketplace');
    $routes->post('customer-data-dasar-daftar-marketing', $functionRoute.'::customerDataDasarDaftarMarketing');
    $routes->post('customer-konten-pengenalan-aplikasi', $functionRoute.'::customerKontenPengenalanAplikasi');
    $routes->post('customer-konten-galeri-klien', $functionRoute.'::customerKontenGaleriKlien');
    $routes->post('customer-konten-galeri-proyek', $functionRoute.'::customerKontenGaleriProyek');
    $routes->post('customer-konten-tutorial-pemasangan', $functionRoute.'::customerKontenTutorialPemasangan');
    $routes->post('customer-konten-profil-perusahaan', $functionRoute.'::customerKontenProfilPerusahaan');
    $routes->post('customer-konten-feed', $functionRoute.'::customerKontenFeed');
    $routes->post('customer-konten-berita-informasi', $functionRoute.'::customerKontenBeritaInformasi');
    $routes->post('customer-konten-slide-kolaborasi', $functionRoute.'::customerKontenSlideKolaborasi');
    $routes->post('customer-produk-katalog', $functionRoute.'::customerProdukKatalog');
    $routes->post('customer-customer-statistik', $functionRoute.'::customerCustomerStatistik');
    $routes->post('customer-customer-daftar', $functionRoute.'::customerCustomerDaftar');
    $routes->post('customer-customer-kritik-saran', $functionRoute.'::customerCustomerKritikSaran');
    $routes->post('customer-transaksi-statistik', $functionRoute.'::customerTransaksiStatistik');
    $routes->post('customer-transaksi-daftar', $functionRoute.'::customerTransaksiDaftar');
});

$routes->group('dashboard', ['filter' => 'auth:mustBeLoggedIn'], function($routes) {
    $functionRoute =   'Dashboard';
    $routes->post('getDataDashboard', $functionRoute.'::getDataDashboard');
});

$routes->group('katalogProduk', ['filter' => 'auth:mustBeLoggedIn'], function($routes) {
    $functionRoute =   'KatalogProduk';
    $routes->post('getDataKatalogProduk', $functionRoute.'::getDataKatalogProduk');
});

$routes->group('monitoringMutasiStok', ['filter' => 'auth:mustBeLoggedIn'], function($routes) {
    $functionRoute =   'MonitoringMutasiStok';
    $routes->post('getDataMonitoringMutasiStok', $functionRoute.'::getDataMonitoringMutasiStok');
});

$routes->group('utilitas', ['filter' => 'auth:mustBeLoggedIn'], function($routes) {
    $routes->group('cekOngkosKirim', ['filter' => 'auth:mustBeLoggedIn'], function($routes) {
        $functionRoute =   'Utilitas\CekOngkosKirim';
        $routes->post('cekOngkosKirim', $functionRoute.'::cekOngkosKirim');
    });
});
$routes->group('pengaturan', ['filter' => 'auth:mustBeLoggedIn'], function($routes) {
    $routes->group('userLevelMenu', ['filter' => 'auth:mustBeLoggedIn'], function($routes) {
        $functionRoute =   'Pengaturan\UserLevelMenu';
        $routes->post('getDataLevelUser', $functionRoute.'::getDataLevelUser');
        $routes->post('getDetailMenuLevelUser', $functionRoute.'::getDetailMenuLevelUser');
        $routes->post('saveLevelUser', $functionRoute.'::saveLevelUser');
        $routes->post('saveLevelMenu', $functionRoute.'::saveLevelMenu');
    });
    $routes->group('daftarPengguna', ['filter' => 'auth:mustBeLoggedIn'], function($routes) {
        $functionRoute =   'Pengaturan\DaftarPengguna';
        $routes->post('getData', $functionRoute.'::getData');
        $routes->post('saveData', $functionRoute.'::saveData');
    });
    $routes->group('variabelSistem', ['filter' => 'auth:mustBeLoggedIn'], function($routes) {
        $functionRoute =   'Pengaturan\VariabelSistem';
        $routes->post('getRowPengaturanSistem', $functionRoute.'::getRowPengaturanSistem');
        $routes->post('simpanPengaturanSistem', $functionRoute.'::simpanPengaturanSistem');
        $routes->post('getDataBarangSistemUtama', $functionRoute.'::getDataBarangSistemUtama');
        $routes->post('syncDataBarangSistemUtama', $functionRoute.'::syncDataBarangSistemUtama');
        $routes->post('getDataWilayahOngkir', $functionRoute.'::getDataWilayahOngkir');
        $routes->post('syncDataWilayahOngkir', $functionRoute.'::syncDataWilayahOngkir');
    });
});

$routes->group('customer', ['filter' => 'auth:mustBeLoggedIn'], function($routes) {
    $routes->group('dataDasar', ['filter' => 'auth:mustBeLoggedIn'], function($routes) {
        $routes->group('merk', ['filter' => 'auth:mustBeLoggedIn'], function($routes) {
            $functionRoute =   'Customer\DataDasar\Merk';
            $routes->post('getData', $functionRoute.'::getData');
            $routes->post('uploadLogo', $functionRoute.'::uploadLogo');
            $routes->post('saveData', $functionRoute.'::saveData');
            $routes->post('uploadPdfKatalogThumbnail', $functionRoute.'::uploadPdfKatalogThumbnail');
            $routes->post('uploadPdfKatalogFile', $functionRoute.'::uploadPdfKatalogFile');
            $routes->post('saveDataKatalog', $functionRoute.'::saveDataKatalog');
        });
        $routes->group('kategoriProduk', ['filter' => 'auth:mustBeLoggedIn'], function($routes) {
            $functionRoute =   'Customer\DataDasar\KategoriProduk';
            $routes->post('getData', $functionRoute.'::getData');
            $routes->post('saveData', $functionRoute.'::saveData');
        });
        $routes->group('levelLoyalti', ['filter' => 'auth:mustBeLoggedIn'], function($routes) {
            $functionRoute =   'Customer\DataDasar\LevelLoyalti';
            $routes->post('getData', $functionRoute.'::getData');
            $routes->post('uploadIcon', $functionRoute.'::uploadIcon');
            $routes->post('uploadCard', $functionRoute.'::uploadCard');
            $routes->post('saveData', $functionRoute.'::saveData');
        });
        $routes->group('sosmedMarketplace', ['filter' => 'auth:mustBeLoggedIn'], function($routes) {
            $functionRoute =   'Customer\DataDasar\SosmedMarketplace';
            $routes->post('getData', $functionRoute.'::getData');
            $routes->post('uploadIcon', $functionRoute.'::uploadIcon');
            $routes->post('saveDataTipe', $functionRoute.'::saveDataTipe');
            $routes->post('saveUrutanTipe', $functionRoute.'::saveUrutanTipeSosmedMarketplace');
            $routes->post('saveDataAkun', $functionRoute.'::saveDataAkun');
            $routes->post('deleteDataAkun', $functionRoute.'::deleteDataAkun');
        });
        $routes->group('daftarMarketing', ['filter' => 'auth:mustBeLoggedIn'], function($routes) {
            $functionRoute =   'Customer\DataDasar\DaftarMarketing';
            $routes->post('getData', $functionRoute.'::getData');
            $routes->post('uploadImageMarketing', $functionRoute.'::uploadImageMarketing');
            $routes->post('saveData', $functionRoute.'::saveData');
        });
    });
    $routes->group('konten', ['filter' => 'auth:mustBeLoggedIn'], function($routes) {
        $routes->group('pengenalanAplikasi', ['filter' => 'auth:mustBeLoggedIn'], function($routes) {
            $functionRoute =   'Customer\Konten\PengenalanAplikasi';
            $routes->post('getData', $functionRoute.'::getData');
            $routes->post('uploadImage', $functionRoute.'::uploadImage');
            $routes->post('saveUrutanSlide', $functionRoute.'::saveUrutanSlide');
            $routes->post('saveData', $functionRoute.'::saveData');
        });
        $routes->group('galeriKlien', ['filter' => 'auth:mustBeLoggedIn'], function($routes) {
            $functionRoute =   'Customer\Konten\GaleriKlien';
            $routes->post('getData', $functionRoute.'::getData');
            $routes->post('uploadLogoKlien', $functionRoute.'::uploadLogoKlien');
            $routes->post('saveDataKlien', $functionRoute.'::saveDataKlien');
            $routes->post('uploadImageGaleriKlien', $functionRoute.'::uploadImageGaleriKlien');
            $routes->post('saveDataKlienGaleri', $functionRoute.'::saveDataKlienGaleri');
        });
        $routes->group('galeriProyek', ['filter' => 'auth:mustBeLoggedIn'], function($routes) {
            $functionRoute =   'Customer\Konten\GaleriProyek';
            $routes->post('getData', $functionRoute.'::getData');
            $routes->post('uploadImage', $functionRoute.'::uploadImage');
            $routes->post('saveData', $functionRoute.'::saveData');
        });
        $routes->group('tutorialPemasangan', ['filter' => 'auth:mustBeLoggedIn'], function($routes) {
            $functionRoute =   'Customer\Konten\TutorialPemasangan';
            $routes->post('getData', $functionRoute.'::getData');
            $routes->post('uploadThumbnailVideo', $functionRoute.'::uploadThumbnailVideo');
            $routes->post('getDetail', $functionRoute.'::getDetail');
            $routes->post('saveData', $functionRoute.'::saveData');
            $routes->post('saveUrutanTutorial', $functionRoute.'::saveUrutanTutorial');
        });
        $routes->group('profilPerusahaan', ['filter' => 'auth:mustBeLoggedIn'], function($routes) {
            $functionRoute =   'Customer\Konten\ProfilPerusahaan';
            $routes->post('getData', $functionRoute.'::getData');
            $routes->post('uploadThumbnailVideo', $functionRoute.'::uploadThumbnailVideo');
            $routes->post('getDetail', $functionRoute.'::getDetail');
            $routes->post('saveData', $functionRoute.'::saveData');
            $routes->post('saveUrutanProfilPerusahaan', $functionRoute.'::saveUrutanProfilPerusahaan');
        });
        $routes->group('feed', ['filter' => 'auth:mustBeLoggedIn'], function($routes) {
            $functionRoute =   'Customer\Konten\Feed';
            $routes->post('getData', $functionRoute.'::getData');
            $routes->post('saveData', $functionRoute.'::saveData');
        });
        $routes->group('beritaInformasi', ['filter' => 'auth:mustBeLoggedIn'], function($routes) {
            $functionRoute =   'Customer\Konten\BeritaInformasi';
            $routes->post('getData', $functionRoute.'::getData');
            $routes->post('uploadImage', $functionRoute.'::uploadImage');
            $routes->post('getDetail', $functionRoute.'::getDetail');
            $routes->post('saveData', $functionRoute.'::saveData');
        });
        $routes->group('slideKolaborasi', ['filter' => 'auth:mustBeLoggedIn'], function($routes) {
            $functionRoute =   'Customer\Konten\SlideKolaborasi';
            $routes->post('getData', $functionRoute.'::getData');
            $routes->post('uploadImageProduk', $functionRoute.'::uploadImageProduk');
            $routes->post('uploadImageThumbnail', $functionRoute.'::uploadImageThumbnail');
            $routes->post('getDetail', $functionRoute.'::getDetail');
            $routes->post('saveData', $functionRoute.'::saveData');
        });
    });
    $routes->group('produk', ['filter' => 'auth:mustBeLoggedIn'], function($routes) {
        $routes->group('katalog', ['filter' => 'auth:mustBeLoggedIn'], function($routes) {
            $functionRoute =   'Customer\Produk\Katalog';
            $routes->post('getData', $functionRoute.'::getData');
            $routes->post('getDetail', $functionRoute.'::getDetail');
            $routes->post('uploadFotoProduk', $functionRoute.'::uploadFotoProduk');
            $routes->post('getDataProdukPadanan', $functionRoute.'::getDataProdukPadanan');
            $routes->post('saveData', $functionRoute.'::saveData');
        });
    });
    $routes->group('customer', ['filter' => 'auth:mustBeLoggedIn'], function($routes) {
        $routes->group('statistikCustomer', ['filter' => 'auth:mustBeLoggedIn'], function($routes) {
            $functionRoute =   'Customer\Customer\StatistikCustomer';
            $routes->post('getDataStatistik', $functionRoute.'::getDataStatistik');
        });
        $routes->group('daftarCustomer', ['filter' => 'auth:mustBeLoggedIn'], function($routes) {
            $functionRoute =   'Customer\Customer\DaftarCustomer';
            $routes->post('getData', $functionRoute.'::getData');
            $routes->post('getDataTableDetail', $functionRoute.'::getDataTableDetail');
        });
        $routes->group('kritikSaran', ['filter' => 'auth:mustBeLoggedIn'], function($routes) {
            $functionRoute =   'Customer\Customer\KritikSaran';
            $routes->post('getData', $functionRoute.'::getData');
        });
    });
    $routes->group('transaksi', ['filter' => 'auth:mustBeLoggedIn'], function($routes) {
        $routes->group('statistikTransaksi', ['filter' => 'auth:mustBeLoggedIn'], function($routes) {
            $functionRoute =   'Customer\Transaksi\StatistikTransaksi';
            $routes->post('getDataStatistik', $functionRoute.'::getDataStatistik');
        });
        $routes->group('daftarTransaksi', ['filter' => 'auth:mustBeLoggedIn'], function($routes) {
            $functionRoute =   'Customer\Transaksi\DaftarTransaksi';
            $routes->post('getData', $functionRoute.'::getData');
            $routes->post('getDetail', $functionRoute.'::getDetail');
        });
    });
});
/*
 * --------------------------------------------------------------------
 * Additional Routing
 * --------------------------------------------------------------------
 *
 * There will often be times that you need additional routing and you
 * need it to be able to override any defaults in this file. Environment
 * based routes is one such time. require() additional route files here
 * to make that happen.
 *
 * You will have access to the $routes object within that file without
 * needing to reload it.
 */
if (is_file(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
    require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}

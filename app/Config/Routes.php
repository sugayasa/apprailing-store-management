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
    $routes->get('photoBarang/(:any)', 'Assets::photoBarang/$1');
});

$routes->group('view', ['filter' => 'auth:mustBeLoggedIn'], function($routes) {
    $functionRoute =   'View';
    $routes->post('dashboard', $functionRoute.'::dashboard');
    $routes->post('katalog-produk', $functionRoute.'::katalogProduk');
    $routes->post('daftar-harga', $functionRoute.'::daftarHarga');
    $routes->post('pengaturan-level-menu', $functionRoute.'::pengaturanLevelMenu');
    $routes->post('pengaturan-daftar-pengguna', $functionRoute.'::pengaturanDaftarPengguna');
    $routes->post('pengaturan-variabel-sistem', $functionRoute.'::pengaturanVariabelSistem');
});

$routes->group('dashboard', ['filter' => 'auth:mustBeLoggedIn'], function($routes) {
    $functionRoute =   'Dashboard';
    $routes->post('getDataDashboard', $functionRoute.'::getDataDashboard');
});

$routes->group('katalogProduk', ['filter' => 'auth:mustBeLoggedIn'], function($routes) {
    $functionRoute =   'KatalogProduk';
    $routes->post('getDataKatalogProduk', $functionRoute.'::getDataKatalogProduk');
});

$routes->group('pengaturan', ['filter' => 'auth:mustBeLoggedIn'], function($routes) {
    $routes->group('levelMenu', ['filter' => 'auth:mustBeLoggedIn'], function($routes) {
        $functionRoute =   'Pengaturan\LevelMenu';
        $routes->post('getDataLevel', $functionRoute.'::getDataLevel');
        $routes->post('getMenuLevelAdmin', $functionRoute.'::getMenuLevelAdmin');
        $routes->post('addLevelAdmin', $functionRoute.'::addLevelAdmin');
        $routes->post('saveLevelMenu', $functionRoute.'::saveLevelMenu');
    });
    $routes->group('userAdmin', ['filter' => 'auth:mustBeLoggedIn'], function($routes) {
        $functionRoute =   'Pengaturan\UserAdmin';
        $routes->post('getDataUserAdmin', $functionRoute.'::getDataUserAdmin');
        $routes->post('saveUserAdmin', $functionRoute.'::saveUserAdmin');
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

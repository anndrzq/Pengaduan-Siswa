<?php

namespace Config;

// Create a new instance of our RouteCollection class.
$routes = Services::routes();

// Load the system's routing file first, so that the app and ENVIRONMENT
// can override as needed.
if (file_exists(SYSTEMPATH . 'Config/Routes.php')) {
	require SYSTEMPATH . 'Config/Routes.php';
}

/**
 * --------------------------------------------------------------------
 * Router Setup
 * --------------------------------------------------------------------
 */
$routes->setDefaultNamespace('App\Controllers');
$routes->setDefaultController('Home');
$routes->setDefaultMethod('index');
$routes->setTranslateURIDashes(false);
$routes->set404Override();
$routes->setAutoRoute(true);

/*
 * --------------------------------------------------------------------
 * Route Definitions
 * --------------------------------------------------------------------
 */

// We get a performance increase by specifying the default
// route since we don't have to scan directories.
$routes->get('/', 'welcome');
$routes->get('notfound', 'Auth::notfound');

// Admin - Manage Data
$routes->group('admin', ['filter' => 'role:1'], function ($routes) {
	$routes->get('manage-user', 'Admin\ManageUser::index');
	$routes->get('manage-user/un-verified', 'Admin\ManageUser::user_unverified');

	$routes->get('manage-user/(:any)', 'Admin\ManageUser::detail/$1');
	$routes->put('manage-user/user_activation', 'Admin\ManageUser::user_activation');
	$routes->delete('manage-user/soft_delete', 'Admin\ManageUser::soft_delete');
});

// Admin - Pengaduan
$routes->group('admin', ['filter' => 'role:1,2'], function ($routes) {
	$routes->get('pengaduan', 'Admin\Pengaduan::index');
	$routes->get('pengaduan/masuk', 'Admin\Pengaduan::pengaduan_masuk');
	$routes->get('pengaduan/di-proses', 'Admin\Pengaduan::pengaduan_diproses');
	$routes->get('pengaduan/selesai', 'Admin\Pengaduan::pengaduan_diselesaikan');

	$routes->get('pengaduan/(:num)', 'Admin\Pengaduan::detail/$1');
	$routes->delete('pengaduan/(:num)', 'Admin\Pengaduan::soft_delete/$1');
	$routes->put('pengaduan/(:num)', 'Admin\Pengaduan::approval/$1');
});

// user
$routes->get('pengaduan', 'Pengaduan::index', ['filter' => 'role:3']);
$routes->get('pengaduan/(:num)', 'Pengaduan::detail/$1', ['filter' => 'role:3']);
$routes->delete('pengaduan/(:num)', 'Pengaduan::soft_delete/$1', ['filter' => 'role:3']);

$routes->get('user/ubah-password', 'User::ubah_password');

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
if (file_exists(APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php')) {
	require APPPATH . 'Config/' . ENVIRONMENT . '/Routes.php';
}

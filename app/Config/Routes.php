<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */

// =====================
// PUBLIC ROUTES (TIDAK TERKENA FILTER)
// =====================
$routes->get('/', 'Auth::login');
$routes->get('login', 'Auth::login');
$routes->post('login', 'Auth::attempt');
$routes->get('logout', 'Auth::logout');

// =====================
// AUTH ROUTES (DIBAWAH FILTER AUTH)
// =====================
$routes->group('', ['filter' => 'auth'], function ($routes) {

    // DASHBOARD UTAMA
    $routes->get('dashboard', 'Dashboard::index');

    // CLIENT / USER PANEL
    $routes->group('client', function($routes) {
        $routes->get('/', 'Client::index'); 
        $routes->get('scan_check', 'Client::scan_check');
        $routes->get('pinjam', 'Client::createPinjam');
        $routes->get('minta', 'Client::createMinta');
        $routes->post('store', 'Client::store');
        $routes->get('konfirmasi_pinjam', 'Client::konfirmasi_pinjam'); 
        $routes->post('proses_pinjam', 'Client::proses_pinjam');
        $routes->get('konfirmasi_kembali', 'Client::konfirmasi_kembali');
        $routes->post('proses_aksi', 'Client::proses_aksi');
    });

   // MANAJEMEN BARANG
$routes->group('barang', ['filter' => 'auth'], function($routes) {
    $routes->get('/', 'Barang::index');
    $routes->get('create', 'Barang::create');
    $routes->post('store', 'Barang::store');
    $routes->get('edit/(:num)', 'Barang::edit/$1');
    $routes->post('update/(:num)', 'Barang::update/$1');
    $routes->get('delete/(:num)', 'Barang::delete/$1');

    // --- RUTE TAMBAHAN UNTUK EXCEL ---
    $routes->get('export', 'Barang::export');             
    $routes->get('downloadTemplate', 'Barang::downloadTemplate'); 
    $routes->post('import', 'Barang::import');           

    // --- RUTE TAMBAHAN UNTUK CETAK QR (WAJIB DITAMBAH) ---
    $routes->get('cetakSatuan/(:num)', 'Barang::cetakSatuan/$1'); // Untuk cetak label satu barang
    $routes->get('cetakSemua', 'Barang::cetakSemua');             // Untuk cetak semua label sekaligus
});

    // MANAJEMEN TRANSAKSI
    $routes->group('transaksi', function($routes) {
        $routes->get('/', 'Transaksi::index');
        $routes->get('create', 'Transaksi::create');
        $routes->post('store', 'Transaksi::store');
        $routes->get('edit/(:num)', 'Transaksi::edit/$1');
        $routes->post('update/(:num)', 'Transaksi::update/$1');
        $routes->get('delete/(:num)', 'Transaksi::delete/$1');
        $routes->get('approve/(:num)', 'Transaksi::approve/$1');
        $routes->get('reject/(:num)', 'Transaksi::reject/$1');
        $routes->get('kembali/(:num)', 'Transaksi::kembali/$1');
    });

    // MASTER DATA LAINNYA
    $routes->group('lokasi', function($routes) {
        $routes->get('/', 'Lokasi::index');
        $routes->get('create', 'Lokasi::create');
        $routes->post('store', 'Lokasi::store');
        $routes->get('edit/(:num)', 'Lokasi::edit/$1');
        $routes->post('update/(:num)', 'Lokasi::update/$1');
        $routes->get('delete/(:num)', 'Lokasi::delete/$1');
    });

    $routes->group('users', function($routes) {
        $routes->get('/', 'Users::index');
        $routes->get('create', 'Users::create');
        $routes->post('store', 'Users::store');
        $routes->get('edit/(:num)', 'Users::edit/$1');
        $routes->post('update/(:num)', 'Users::update/$1');
        $routes->get('delete/(:num)', 'Users::delete/$1');
    });
});

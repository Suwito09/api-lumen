<?php

/** @var \Laravel\Lumen\Routing\Router $router */

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

//$router->get('/', function () use ($router) {
//    return $router->app->version();
//});

$router->get('/', function () {
    return view('home');
});

$router->get('/login', function () {
    return view('login');
});

$router->get('/register', function () {
    return view('register');
});

$router->get('/admin/login', function () {
    return view('admin.login');
});

$router->group(['prefix' => 'admin'], function () use ($router) {

    // Login admin (tanpa middleware)
    $router->post('login', 'AdminController@login');

    // Dashboard tanpa middleware (untuk testing, bisa dipindah ke bawah kalau mau pakai middleware)
    $router->get('dashboard', function () {
        return view('admin.dashboard');
    });

    // Rute yang butuh autentikasi admin
    $router->group(['middleware' => 'auth.admin'], function () use ($router) {
        $router->post('wisata/tambah', 'AdminController@tambahTempat');
        $router->get('wisata/semua', 'AdminController@getSemuaWisata');
        $router->put('wisata/update/{id}', 'AdminController@updateWisata');
        $router->delete('wisata/hapus/{id}', 'AdminController@hapusWisata');
    });
});

$router->group(['prefix' => 'pengguna'], function () use ($router) {
    $router->post('register', 'PenggunaController@register');
    $router->post('login', 'PenggunaController@login');

    $router->group(['middleware' => 'apikey'], function () use ($router) {
        $router->get('wisata', 'WisataController@index');
        $router->get('wisata/jenis', 'WisataController@byJenis');
        $router->get('wisata/cari/{nama}', 'WisataController@cari'); 
        $router->get('wisata/peta', 'WisataController@lokasiPeta');
    });
});

$router->get('/wisata', 'WisataController@index');
//$router->get('/wisata/jenis', 'WisataController@byJenis');
$router->get('/wisata/cari/{nama}', 'WisataController@cari');
$router->get('/wisata/lokasi', 'WisataController@lokasi');



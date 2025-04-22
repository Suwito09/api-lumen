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

$router->get('/routes', function () use ($router) {
    return $router->getRoutes();
});

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
    $router->group(['middleware' => 'auth.admin'], function () use ($router) {
        $router->post('login', 'AdminController@login');
        $router->get('dashboard', function () {
            return view('admin.dashboard');
        });
        $router->post('wisata/tambah', 'AdminController@tambahTempat');
        $router->get('wisata/semua', 'AdminController@getSemuaWisata');
        $router->put('wisata/update/{id}', 'AdminController@updateTempat');
        $router->delete('wisata/hapus/{id}', 'AdminController@deleteTempat');
    });
});

$router->group(['prefix' => 'pengguna'], function () use ($router) {
    
    $router->post('register', 'PenggunaController@register');
    $router->post('login', 'PenggunaController@login');

    $router->group(['middleware' => 'apikey'], function () use ($router) {
        $router->get('wisata', 'WisataController@index');
        $router->get('wisata/cari/{nama}', 'WisataController@cari'); 
    });
});

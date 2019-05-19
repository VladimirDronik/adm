<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
/*
Route::get('/', function () {
    return view('welcome');
});
*/

Route::get('/', 'HomeController@index')->name('home');


Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/devices', 'DevicesController@index');
Route::get('/devices/select/{id_device}', 'DevicesController@select')->name('id_device');

//AJAX return
Route::post('/getobject','AJAX\ObjectsController@load_to_port');
Route::post('/add_object_to_port','AJAX\ObjectsController@add_to_port');

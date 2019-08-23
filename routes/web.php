<?php


Auth::routes();

Route::group(['middleware' => ['auth']], function () {

    Route::get('/', 'HomeController@index')->name('home');
    Route::get('home', 'HomeController@index')->name('home');

    Route::get('devices', 'DeviceController@index');
    Route::get('objects', 'ObjectController@index');
    Route::get('devices/select/{id_device}', 'DeviceController@select')->name('id_device');
    Route::get('rooms', 'RoomController@index');

    Route::get('views', 'ViewController@index');
    Route::get('views/room/{idRoom}', 'ViewController@getFilteredViews')->name('idRoom');

    //Objects
    Route::post('getobject', 'AJAX\ObjectController@load_to_port');
    Route::post('add_object_to_port', 'AJAX\ObjectController@add_to_port');

    //Ports
    Route::post('getmethod', 'AJAX\PortController@load_method');
    Route::post('loaddata', 'AJAX\PortController@load_data');
    Route::post('savemethod', 'AJAX\PortController@save_method');
    Route::post('savenameport', 'AJAX\PortController@save_name_port');
    Route::post('addports', 'AJAX\PortController@add_ports');

    //Devices
    Route::post('/savedevicesettings', 'AJAX\DeviceController@save_device_settings');
    Route::post('/newdevice', 'AJAX\DeviceController@newdevice');
    Route::post('/deletedevice', 'AJAX\DeviceController@deletedevice');

    //Rooms
    Route::post('rooms/addRoom', 'AJAX\RoomController@addRoom');
    Route::post('rooms/deleteRoom', 'AJAX\RoomController@deleteRoom');
    Route::post('rooms/sort', 'AJAX\RoomController@sort');
    Route::post('rooms/saveNameRoom', 'AJAX\RoomController@saveNameRoom');
    Route::post('rooms/updateImage', 'AJAX\RoomController@updateImage');
    Route::post('rooms/updateColor', 'AJAX\RoomController@updateColor');

});
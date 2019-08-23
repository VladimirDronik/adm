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

    Route::group(['namespace' => 'Ajax'], function () {
        //Objects
        Route::post('getobject', 'ObjectController@load_to_port');
        Route::post('add_object_to_port', 'ObjectController@add_to_port');

        //Ports
        Route::post('getmethod', 'PortController@load_method');
        Route::post('loaddata', 'PortController@load_data');
        Route::post('savemethod', 'PortController@save_method');
        Route::post('savenameport', 'PortController@save_name_port');
        Route::post('addports', 'PortController@add_ports');

        //Devices
        Route::post('savedevicesettings', 'DeviceController@save_device_settings');
        Route::post('newdevice', 'DeviceController@newdevice');
        Route::post('deletedevice', 'DeviceController@deletedevice');

        //Rooms
        Route::post('rooms/addRoom', 'RoomController@addRoom');
        Route::post('rooms/deleteRoom', 'RoomController@deleteRoom');
        Route::post('rooms/sort', 'RoomController@sort');
        Route::post('rooms/saveNameRoom', 'RoomController@saveNameRoom');
        Route::post('rooms/updateImage', 'RoomController@updateImage');
        Route::post('rooms/updateColor', 'RoomController@updateColor');
    });
});
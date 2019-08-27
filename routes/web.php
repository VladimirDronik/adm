<?php

Auth::routes();

Route::group(['middleware' => ['auth']], function () {

    Route::redirect('/','home');
    Route::get('home', 'HomeController@index')->name('home');

    Route::resource('views', 'ViewController')->except('show','destroy');
    Route::resource('objects', 'ObjectController')->except('show','destroy');

    Route::resource('devices', 'DeviceController')->except('show','destroy');
    Route::resource('rooms', 'RoomController')->except('show','destroy');
    Route::resource('scenes', 'SceneController')->except('show','destroy');
    Route::resource('termostats', 'TermostatController')->except('show','destroy');

    Route::group(['namespace' => 'Ajax'], function () {

        Route::post('devices/delete', 'DeviceController@delete')->name('ajax.devices.delete');

        Route::post('views/delete', 'ViewController@delete')->name('ajax.views.delete');
        Route::post('views/active', 'ViewController@active')->name('ajax.views.active');

        Route::post('objects/delete', 'ObjectController@delete')->name('ajax.objects.delete');
        Route::post('scenes/delete', 'SceneController@delete')->name('ajax.scenes.delete');
        Route::post('termostats/delete', 'TermostatController@delete')->name('ajax.termostats.delete');

        Route::post('getobject', 'ObjectController@load_to_port');  // todo check
        Route::post('add_object_to_port', 'ObjectController@add_to_port');  // todo check

        //Ports
        Route::post('getmethod', 'PortController@load_method');
        Route::post('loaddata', 'PortController@load_data');
        Route::post('savemethod', 'PortController@save_method');
        Route::post('savenameport', 'PortController@save_name_port');
        Route::post('addports', 'PortController@add_ports');

        //Devices
        Route::post('savedevicesettings', 'DeviceController@save_device_settings');
        Route::post('newdevice', 'DeviceController@newdevice');

        //Rooms
        Route::post('rooms/addRoom', 'RoomController@addRoom');
        Route::post('rooms/deleteRoom', 'RoomController@deleteRoom');
        Route::post('rooms/sort', 'RoomController@sort');
        Route::post('rooms/saveNameRoom', 'RoomController@saveNameRoom');
        Route::post('rooms/updateImage', 'RoomController@updateImage');
        Route::post('rooms/updateColor', 'RoomController@updateColor');
    });
});
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

    Route::group(['namespace' => 'Ajax', 'as' => 'ajax.'], function () {

        Route::group(['prefix' => 'devices', 'as' => 'devices.'], function () {
            Route::post('delete', 'DeviceController@delete')->name('delete');
            Route::post('update', 'DeviceController@update')->name('update');
            Route::post('ports/update', 'DeviceController@updatePort')->name('ports.update');
        });

        Route::post('views/delete', 'ViewController@delete')->name('views.delete');
        Route::post('views/active', 'ViewController@active')->name('views.active');

        Route::post('objects/delete', 'ObjectController@delete')->name('objects.delete');
        Route::post('scenes/delete', 'SceneController@delete')->name('scenes.delete');
        Route::post('termostats/delete', 'TermostatController@delete')->name('termostats.delete');

        Route::post('getobject', 'ObjectController@load_to_port');  // todo check
        Route::post('add_object_to_port', 'ObjectController@add_to_port');  // todo check

        //Ports
        Route::post('getmethod', 'PortController@load_method');
        Route::post('loaddata', 'PortController@load_data');
        Route::post('savemethod', 'PortController@save_method');
        Route::post('savenameport', 'PortController@save_name_port');

        //Rooms
        Route::post('rooms/addRoom', 'RoomController@addRoom');
        Route::post('rooms/deleteRoom', 'RoomController@deleteRoom');
        Route::post('rooms/sort', 'RoomController@sort');
        Route::post('rooms/saveNameRoom', 'RoomController@saveNameRoom');
        Route::post('rooms/updateImage', 'RoomController@updateImage');
        Route::post('rooms/updateColor', 'RoomController@updateColor');
    });
});
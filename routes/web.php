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

        Route::group(['prefix' => 'rooms', 'as' => 'rooms.'], function () {
            Route::post('sort', 'RoomController@sort')->name('sort');
            Route::post('delete', 'RoomController@delete')->name('delete');
            Route::post('store', 'RoomController@store')->name('store');
            Route::post('update/name', 'RoomController@updateName')->name('update.name');
            Route::post('update/image', 'RoomController@updateImage')->name('update.image');
            Route::post('update/color', 'RoomController@updateColor')->name('update.color');
        });

        // todo

        Route::post('getobject', 'ObjectController@load_to_port');  // todo check
        Route::post('add_object_to_port', 'ObjectController@add_to_port');  // todo check

        //Ports
        Route::post('getmethod', 'PortController@load_method');
        Route::post('loaddata', 'PortController@load_data');
        Route::post('savemethod', 'PortController@save_method');
        Route::post('savenameport', 'PortController@save_name_port');
    });
});
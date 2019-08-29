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

    Route::get('menu', 'MenuController@index')->name('menu.index');

    Route::group(['namespace' => 'Ajax', 'as' => 'ajax.'], function () {

        Route::group(['prefix' => 'devices', 'as' => 'devices.'], function () {
            Route::post('delete', 'DeviceController@delete')->name('delete');
            Route::post('update', 'DeviceController@update')->name('update');
            Route::post('ports', 'DeviceController@ports')->name('ports');
            Route::post('ports/update', 'DeviceController@updatePort')->name('ports.update');
        });

        Route::group(['prefix' => 'views', 'as' => 'views.'], function () {
            Route::post('delete', 'ViewController@delete')->name('delete');
            Route::post('active', 'ViewController@active')->name('active');
        });

        Route::post('objects/delete', 'ObjectController@delete')->name('objects.delete');
        Route::post('termostats/delete', 'TermostatController@delete')->name('termostats.delete');

        Route::group(['prefix' => 'scenes', 'as' => 'scenes.'], function () {
            Route::post('delete', 'SceneController@delete')->name('delete');
            Route::post('sort', 'SceneController@sort')->name('sort');
            Route::post('active', 'SceneController@active')->name('active');
        });

        Route::group(['prefix' => 'menu', 'as' => 'menu.'], function () {
            Route::post('sort', 'MenuController@sort')->name('sort');
            Route::post('active', 'MenuController@active')->name('active');
        });

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
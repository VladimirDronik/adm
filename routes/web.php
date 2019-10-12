<?php

Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

Route::group(['middleware' => ['auth']], function () {

    Route::redirect('/','home');
    Route::get('home', 'HomeController@index')->name('home');

    Route::get('profile','ProfileController@edit')->name('profile.edit');
    Route::put('profile','ProfileController@update')->name('profile.update');

    Route::resource('views', 'ViewController')->except('show','destroy');
    Route::resource('objects', 'ObjectController')->except('show','destroy');

    Route::resource('devices', 'DeviceController')->except('show','destroy');
    Route::resource('rooms', 'RoomController')->except('show','create','store','destroy');
    Route::resource('scenes', 'SceneController')->except('show','destroy');
    Route::resource('termostats', 'TermostatController')->except('show','destroy');
    Route::resource('events', 'EventController')->except('show','destroy');

    Route::get('network', 'NetworkController@edit')->name('network.edit');
    Route::put('network', 'NetworkController@update')->name('network.update');
    Route::get('menu', 'MenuController@index')->name('menu.index');
    Route::get('graphs', 'GraphController@index')->name('graphs.index');

    Route::resource('scripts', 'ScriptController')->except('show','destroy');

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

        Route::group(['prefix' => 'objects', 'as' => 'objects.'], function () {
            Route::post('methods', 'ObjectController@methods')->name('methods');
            Route::post('view/all', 'ObjectController@getViewAll')->name('view.all');
            Route::post('delete', 'ObjectController@delete')->name('delete');
        });

        Route::post('view_objects/view/all', 'ViewObjectController@getViewAll')->name('view_objects.view.all');
        Route::post('add_object_to_view', 'ViewObjectController@addObjectToView');
        Route::post('view_objects/method/all', 'ViewObjectController@getMethodAll')->name('view_objects.method.all');
        Route::post('add_method_to_view', 'ViewObjectController@addMethodToView');

        Route::group(['prefix' => 'methods', 'as' => 'methods.'], function () {
            Route::post('delete', 'MethodController@delete')->name('delete');
            Route::post('store', 'MethodController@store')->name('store');
        });

        Route::group(['prefix' => 'ports', 'as' => 'ports.'], function () {
            Route::post('update/comment', 'PortController@updateComment')->name('update.comment');
            Route::post('method/all', 'PortController@getMethodAll')->name('method.all');
        });

        Route::post('add_object_to_port', 'ObjectController@addObjectToPort');
        Route::post('add_method_to_port', 'PortController@addMethodToPort');

        Route::post('getmethod', 'PortController@getViewMethod');
        Route::post('loaddata', 'PortController@getViewData')->name('load.data');
        Route::post('savemethod', 'PortController@storeMethod');

        Route::post('events/delete', 'EventController@delete')->name('events.delete');
        Route::post('events/validation/name', 'EventController@validateName')->name('events.validation.name');

        Route::group(['prefix' => 'points', 'as' => 'points.'], function () {
            Route::post('delete', 'SchedulerPointController@delete')->name('delete');
            Route::post('store', 'SchedulerPointController@store')->name('store');
        });

        Route::post('graphs/period/data', 'GraphController@getPeriodData')->name('graphs.period.data');

        Route::group(['prefix' => 'scripts', 'as' => 'scripts.'], function () {
            Route::post('delete', 'ScriptController@delete')->name('delete');
        });
    });
});
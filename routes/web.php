<?php

Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

Route::get('generate/fake', 'HomeController@generateFake')->name('generate.fake');
Route::get('access/error', 'HomeController@accessError')->name('accescarbmonoxides.error');

Route::group(['middleware' => ['auth']], function () {

    Route::redirect('/','home');
    Route::get('home', 'HomeController@index')->name('home');

    Route::get('profile','ProfileController@edit')->name('profile.edit');
    Route::put('profile','ProfileController@update')->name('profile.update');

    Route::resource('views', 'ViewController')->except('show','destroy')->middleware('can:views');
    Route::resource('objects', 'ObjectController')->except('show','destroy')->middleware('can:objects');


    Route::get('devices/{idDevice}/editport/{idPort}', 'DeviceController@editPort')->middleware('can:devices');
    Route::get('devices/sendconfig/{idDevice}', 'DeviceController@sendConfig')->name('devices.sendconfig')->middleware('can:devices');
    Route::get('devices/sendallconfigs', 'DeviceController@sendAllConfigs')->name('devices.sendallconfigs')->middleware('can:devices');


    Route::resource('ports', 'PortController')->except('show', 'destroy')->middleware('can:devices');
    Route::resource('devices', 'DeviceController')->except('show','destroy')->middleware('can:devices');
    Route::resource('counts', 'CountController')->except('show','destroy')->middleware('can:devices');
    Route::resource('switches', 'SwitchController')->except('show','destroy')->middleware('can:devices');
    Route::resource('relays', 'RelayController')->except('show','destroy')->middleware('can:devices');
    Route::resource('lamps', 'LampController')->except('show','destroy')->middleware('can:devices');
    Route::resource('dimmers', 'DimmerController')->except('show','destroy')->middleware('can:devices');

    Route::resource('settings', 'SettingController')->except('show','destroy')->middleware('can:settings');
    Route::resource('scenes', 'SceneController')->except('show','destroy')->middleware('can:scenes');
    Route::resource('termostats', 'TermostatController')->except('show','destroy')->middleware('can:devices');
    Route::resource('motionsensors', 'MotionsensorsController')->except('show','destroy')->middleware('can:devices');
    Route::resource('lightstats', 'LightstatController')->except('show','destroy')->middleware('can:devices');
    Route::resource('carbmonoxide', 'CarbmonoxideController')->except('show','destroy')->middleware('can:devices');
    Route::resource('manometr', 'ManometrController')->except('show','destroy')->middleware('can:devices');
    Route::resource('usensors', 'UsensorController')->except('show','destroy')->middleware('can:devices');
    Route::resource('drycontacts', 'DrycontactController')->except('show','destroy')->middleware('can:devices');
    Route::resource('events', 'EventController')->except('show','destroy')->middleware('can:events');
    Route::resource('logs', 'LogController')->only('index')->middleware('can:logs');
    Route::resource('users', 'UserController')->except('show','destroy')->middleware('can:rooms');
    Route::resource('notifications', 'NotificationController')->except('show','destroy')->middleware('can:settings');
    Route::resource('virtuals', 'VirtualsController')->except('show','destroy')->middleware('can:devices');

    Route::resource('rooms', 'RoomController')->except('show','create','store','destroy')->middleware('can:rooms');
    Route::get('rooms/group/{id}', 'RoomGroupController@index')->name('rooms.group.index')->middleware('can:rooms');

    Route::get('network', 'NetworkController@edit')->name('network.edit')->middleware('can:network');
    Route::put('network', 'NetworkController@update')->name('network.update')->middleware('can:network');
    Route::get('menu', 'MenuController@index')->name('menu.index')->middleware('can:menu');
    Route::get('graphs/termostats', 'GraphController@termostats')->name('graphs.termostats.index')->middleware('can:graphs');
    Route::get('graphs/lights', 'GraphController@lights')->name('graphs.lights.index')->middleware('can:graphs');
    Route::get('graphs/humidities', 'GraphController@humidities')->name('graphs.humidities.index')->middleware('can:graphs');
    Route::get('graphs/counts', 'GraphController@counts')->name('graphs.counts.index')->middleware('can:graphs');
    Route::get('logs/settings', 'LogController@settings')->name('logs.settings')->middleware('can:logs');


    Route::resource('scripts', 'ScriptController')->except('show','destroy')->middleware('can:scripts');



    Route::group(['namespace' => 'Ajax', 'as' => 'ajax.'], function () {

        Route::group(['prefix' => 'devices', 'as' => 'devices.'], function () {
            Route::post('delete', 'DeviceController@delete')->name('delete');
            Route::post('update', 'DeviceController@update')->name('update');
            Route::post('ports', 'DeviceController@ports')->name('ports');
            Route::post('ports/update', 'DeviceController@updatePort')->name('ports.update');
            Route::post('check/server', 'DeviceController@checkServer')->name('check.server');
            Route::post('objects_ports', 'DeviceController@objectsPorts')->name('objects_ports');
            Route::post('type_controller', 'DeviceController@typeController')->name('type_controller');
        });

        Route::group(['prefix' => 'views', 'as' => 'views.'], function () {
            Route::post('delete', 'ViewController@delete')->name('delete');
            Route::post('active', 'ViewController@active')->name('active');
            Route::post('sort', 'ViewController@sort')->name('sort');
        });

        Route::post('manometr/delete', 'ManometrController@delete')->name('manometr.delete');
        Route::post('termostats/delete', 'TermostatController@delete')->name('termostats.delete');
        Route::post('lightstats/delete', 'LightstatController@delete')->name('lightstats.delete');
        Route::post('usensors/delete', 'UsensorController@delete')->name('usensors.delete');
        Route::post('drycontacts/delete', 'DrycontactController@delete')->name('drycontacts.delete');
        Route::post('motionsensors/delete', 'MotionsensorController@delete')->name('motionsensors.delete');
        Route::post('carbmonoxide/delete', 'CarbmonoxideController@delete')->name('carbmonoxide.delete');
        Route::post('virtuals/delete', 'VirtualsController@delete')->name('virtuals.delete');

        Route::group(['prefix' => 'logs', 'as' => 'logs.'], function () {
            Route::post('active', 'LogsController@active')->name('active');
        });

        Route::group(['prefix' => 'scenes', 'as' => 'scenes.'], function () {
            Route::post('delete', 'SceneController@delete')->name('delete');
            Route::post('sort', 'SceneController@sort')->name('sort');
            Route::post('active', 'SceneController@active')->name('active');
        });

        Route::group(['prefix' => 'counts', 'as' => 'counts.'], function () {
            Route::post('delete', 'CountController@delete')->name('delete');
        });

        Route::group(['prefix' => 'switches', 'as' => 'switches.'], function () {
            Route::post('delete', 'SwitchController@delete')->name('delete');
        });

        Route::group(['prefix' => 'relays', 'as' => 'relays.'], function () {
            Route::post('delete', 'RelayController@delete')->name('delete');
        });

        Route::group(['prefix' => 'lamps', 'as' => 'lamps.'], function () {
            Route::post('delete', 'LampController@delete')->name('delete');
        });

        Route::group(['prefix' => 'dimmers', 'as' => 'dimmers.'], function () {
            Route::post('delete', 'DimmerController@delete')->name('delete');
        });

        Route::group(['prefix' => 'settings', 'as' => 'settings.'], function () {
            Route::post('delete', 'SettingController@delete')->name('delete');
        });

        Route::group(['prefix' => 'users', 'as' => 'users.'], function () {
            Route::post('delete', 'UserController@delete')->name('delete');
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
            Route::post('getObjects', 'ObjectController@getObjects')->name('getObjects');
            Route::post('methods', 'ObjectController@methods')->name('methods');
            Route::post('view/all', 'ObjectController@getViewAll')->name('view.all');
            Route::post('delete', 'ObjectController@delete')->name('delete');
            Route::post('delete/all', 'ObjectController@deleteAll')->name('delete.all');
            Route::post('store', 'ObjectController@store')->name('store');
        });

        Route::post('view_objects/view/all', 'ViewObjectController@getViewAll')->name('view_objects.view.all');
        Route::post('add_object_to_view', 'ViewObjectController@addObjectToView');
        Route::post('view_objects/method/all', 'ViewObjectController@getMethodAll')->name('view_objects.method.all');
        Route::post('view_objects/method/off/all', 'ViewObjectController@getMethodOffAll')->name('view_objects.method.off.all');
        Route::post('add_method_to_view', 'ViewObjectController@addMethodToView');
        Route::post('add_off_method_to_view', 'ViewObjectController@addOffMethodToView');

        Route::group(['prefix' => 'methods', 'as' => 'methods.'], function () {
            Route::post('delete', 'MethodController@delete')->name('delete');
            Route::post('store', 'MethodController@store')->name('store');
        });

        Route::group(['prefix' => 'messages', 'as' => 'messages.'], function () {
            Route::post('delete', 'MessageController@delete')->name('delete');
            Route::post('store', 'MessageController@store')->name('store');
        });


        Route::group(['prefix' => 'ports', 'as' => 'ports.'], function () {
            Route::post('update/comment', 'PortController@updateComment')->name('update.comment');
            Route::post('method/all', 'PortController@getMethodAll')->name('method.all');
            Route::post('object/methods', 'PortController@getObjectMethods')->name('object.methods');
            Route::post('edit/methods', 'PortController@getPortMethods')->name('edit.methods');
            Route::post('edit/method/delete', 'PortController@deletePortMethod')->name('edit.method.delete');
            Route::post('update/method', 'PortController@updatePortMethod')->name('update.method');
        });

        Route::post('add_object_to_port', 'ObjectController@addObjectToPort');
        Route::post('add_method_to_port', 'PortController@addMethodToPort');

        Route::post('getmethod', 'PortController@getViewMethod');
        Route::post('loaddata', 'PortController@getViewData')->name('load.data');
        Route::post('savemethod', 'PortController@storeMethod');

        Route::post('events/delete', 'EventController@delete')->name('events.delete');
        Route::post('events/validation/name', 'EventController@validateName')->name('events.validation.name');
        Route::post('events/system', 'EventController@system')->name('events.system');
        Route::post('events/hidden', 'EventController@hidden')->name('events.hidden');
        Route::post('events/active', 'EventController@active')->name('events.active');

        Route::group(['prefix' => 'points', 'as' => 'points.'], function () {
            Route::post('delete', 'SchedulerPointController@delete')->name('delete');
            Route::post('store', 'SchedulerPointController@store')->name('store');
        });

        Route::post('graphs/termostats/period/data', 'GraphController@getTermostatsPeriodData')->name('graphs.termostats.period.data');
        Route::post('graphs/humidities/period/data', 'GraphController@getHumiditiesPeriodData')->name('graphs.humidities.period.data');
        Route::post('graphs/lights/period/data', 'GraphController@getLightsPeriodData')->name('graphs.lights.period.data');
        Route::post('graphs/counts/period/data', 'GraphController@getCountsPeriodData')->name('graphs.counts.period.data');

        Route::group(['prefix' => 'scripts', 'as' => 'scripts.'], function () {
            Route::post('delete', 'ScriptController@delete')->name('delete');
        });
    });
});
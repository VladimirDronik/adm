<?php

Route::get('login', 'Auth\LoginController@showLoginForm')->name('login');
Route::post('login', 'Auth\LoginController@login');
Route::post('logout', 'Auth\LoginController@logout')->name('logout');

Route::get('generate/fake', 'HomeController@generateFake')->name('generate.fake');
Route::get('access/error', 'HomeController@accessError')->name('accescarbmonoxides.error');

Route::group(['middleware' => ['auth']], function () {

    Route::redirect('/', 'home');
    Route::get('home', 'HomeController@index')->name('home');

    Route::get('profile', 'ProfileController@edit')->name('profile.edit');
    Route::put('profile', 'ProfileController@update')->name('profile.update');

    Route::resource('views', 'ViewController')->except('show', 'destroy')->middleware('can:views');
    Route::resource('pages', 'PageController')->except('show', 'destroy')->middleware('can:views');
    Route::get('pages/{idPage}/edit', 'PageController@edit')->middleware('can:devices');
    Route::get('pages/{idPage}/edit/{idTab}', 'PageController@edit')->middleware('can:devices');
    Route::resource('objects', 'ObjectController')->except('show', 'destroy')->middleware('can:objects');
    Route::resource('engineering', 'EngineeringController')->except('show', 'destroy')->middleware('can:views');
    Route::resource('boiler', 'BoilerController')->except('show', 'destroy')->middleware('can:devices');
    Route::resource('boiler_gvs', 'BoilerGVSController')->except('show', 'destroy')->middleware('can:devices');

    Route::get('page/{idPage}/createElement', 'ElementController@create')->name('page.createElement')->middleware('can:views');
    Route::resource('elements', 'ElementController')->except('show', 'destroy', 'create')->middleware('can:views');

    Route::get('devices/{idDevice}/editport/{idPort}', 'DeviceController@editPort')->middleware('can:devices');
    Route::get('devices/sendconfig/{idDevice}', 'DeviceController@sendConfig')->name('devices.sendconfig')->middleware('can:devices');
    Route::get('devices/sendallconfigs', 'DeviceController@sendAllConfigs')->name('devices.sendallconfigs')->middleware('can:devices');

    Route::resource('ports', 'PortController')->except('show', 'destroy')->middleware('can:devices');
    Route::resource('devices', 'DeviceController')->except('show', 'destroy')->middleware('can:devices');
    Route::resource('counts', 'CountController')->except('show', 'destroy')->middleware('can:devices');
    Route::resource('switches', 'SwitchController')->except('show', 'destroy')->middleware('can:devices');
    Route::resource('relays', 'RelayController')->except('show', 'destroy')->middleware('can:devices');
    Route::resource('lamps', 'LampController')->except('show', 'destroy', 'index')->middleware('can:devices');
    Route::resource('dimmers', 'DimmerController')->except('show', 'destroy')->middleware('can:devices');
    Route::resource('curtains', 'CurtainController')->except('show', 'destroy')->middleware('can:devices');
    Route::resource('locks', 'LockController')->except('show', 'destroy')->middleware('can:devices');
    Route::resource('conditioners', 'ConditionerController')->except('show', 'destroy')->middleware('can:devices');
    Route::resource('led_tapes', 'LedTapeController')->except('show', 'destroy', 'index')->middleware('can:devices');
    Route::get('illumination', 'IlluminationController@index')->name('illumination.index')->middleware('can:devices');

    Route::group(['prefix' => 'mod_bus', 'as' => 'mod_bus.'], function () {
        Route::resource('buses', 'ModbusBusController')->except('show', 'destroy')->middleware('can:mod_bus');
        Route::resource('slavers', 'ModbusSlaverController')->except('show', 'destroy')->middleware('can:mod_bus');
        Route::resource('dali_devices', 'DaliDeviceController')->only('edit', 'update')->middleware('can:mod_bus');
        Route::resource('registers', 'ModbusRegisterController')->except('show', 'destroy')->middleware('can:mod_bus');
    });

    Route::resource('cameras', 'CameraController')->except('show', 'destroy', 'index')->middleware('can:cameras');
    Route::get('cameras/{camera}/stream', 'CameraController@getStream')->middleware('can:cameras')->name('cameras.get_stream');
    Route::resource('recorders', 'RecorderController')->except('show', 'destroy', 'index')->middleware('can:cameras');
    Route::get('cctv', 'CctvController@index')->name('cctv.index')->middleware('can:cameras');

    Route::resource('settings', 'SettingController')->except('show', 'destroy')->middleware('can:settings');
    Route::resource('time_zone', 'TimeZoneSettingController')->except('show', 'destroy', 'index')->middleware('can:settings');
    Route::resource('scenes', 'SceneController')->except('show', 'destroy')->middleware('can:scenes');
    Route::resource('termostats', 'TermostatController')->except('show', 'destroy')->middleware('can:devices');
    Route::resource('sensors', 'SensorController')->except('show', 'destroy', 'update')->middleware('can:devices');
    Route::put('sensors/{sensorObject}', 'SensorController@update')->name('sensors.update');
    Route::resource('hygrostats', 'HygrostatController')->except('show', 'destroy')->middleware('can:devices');
    Route::resource('motionsensors', 'MotionsensorsController')->except('show', 'destroy')->middleware('can:devices');
    Route::resource('lightstats', 'LightstatController')->except('show', 'destroy')->middleware('can:devices');
    Route::resource('pressurestats', 'PressurestatController')->except('show', 'destroy')->middleware('can:devices');
    Route::resource('carbdioxides', 'CarbdioxideController')->except('show', 'destroy')->middleware('can:devices');
    Route::resource('carbmonoxide', 'CarbmonoxideController')->except('show', 'destroy')->middleware('can:devices');
    Route::resource('manometr', 'ManometrController')->except('show', 'destroy')->middleware('can:devices');
    Route::resource('usensors', 'UsensorController')->except('show', 'destroy')->middleware('can:devices');
    Route::resource('drycontacts', 'DrycontactController')->except('show', 'destroy')->middleware('can:devices');
    Route::resource('scheduler', 'SchedulerController')->except('show', 'destroy')->middleware('can:events');
    Route::resource('logs', 'LogController')->only('index')->middleware('can:logs');
    Route::resource('users', 'UserController')->except('show', 'destroy')->middleware('can:rooms');
    Route::resource('notifications', 'NotificationController')->except('show', 'destroy')->middleware('can:settings');
    Route::resource('virtuals', 'VirtualsController')->except('show', 'destroy')->middleware('can:devices');
    Route::resource('yandexstations', 'YandexStationController')->except('show', 'destroy', 'create', 'store')->middleware('can:devices');
    Route::get('yandexstations/reset_user', 'YandexStationController@resetUser')->name('yandexstations.reset_user')->middleware('can:devices');
    Route::resource('regulators', 'RegulatorController')->except('show', 'destroy')->middleware('can:devices');

    //Route::get('termostats/{termostat}/edit/{tab?}', 'TermostatController@edit')->name('termostats.edit')->middleware('can:devices');

    Route::resource('rooms', 'RoomController')->except('show', 'create', 'store', 'destroy')->middleware('can:rooms');
    Route::get('rooms/group/{id}', 'RoomGroupController@index')->name('rooms.group.index')->middleware('can:rooms');

    Route::resource('menu', 'MenuController')->except('show', 'create', 'store', 'destroy')->middleware('can:rooms');
    Route::get('menu/group/{id}', 'MenuGroupController@index')->name('menu.group.index')->middleware('can:rooms');

    Route::get('network', 'NetworkController@edit')->name('network.edit')->middleware('can:network');
    Route::put('network', 'NetworkController@update')->name('network.update')->middleware('can:network');
    Route::get('graphs/termostats', 'GraphController@termostats')->name('graphs.termostats.index')->middleware('can:graphs');
    Route::get('graphs/hygrostats', 'GraphController@hygrostats')->name('graphs.hygrostats.index')->middleware('can:graphs');
    Route::get('graphs/lights', 'GraphController@lights')->name('graphs.lights.index')->middleware('can:graphs');
    Route::get('graphs/pressures', 'GraphController@pressures')->name('graphs.pressures.index')->middleware('can:graphs');
    Route::get('graphs/carbdioxides', 'GraphController@carbdioxides')->name('graphs.carbdioxides.index')->middleware('can:graphs');
    Route::get('graphs/humidities', 'GraphController@humidities')->name('graphs.humidities.index')->middleware('can:graphs');
    Route::get('graphs/counts', 'GraphController@counts')->name('graphs.counts.index')->middleware('can:graphs');
    Route::get('logs/settings', 'LogController@settings')->name('logs.settings')->middleware('can:logs');

    Route::resource('scripts', 'ScriptController')->except('show', 'destroy')->middleware('can:scripts');

    Route::group(['namespace' => 'Ajax', 'as' => 'ajax.'], function () {
        Route::group(['prefix' => 'cameras', 'as' => 'cameras.'], function () {
            Route::post('sort', 'CameraController@sort')->name('sort');
            Route::post('delete', 'CameraController@delete')->name('delete');
            Route::post('active', 'CameraController@active')->name('active');
        });

        Route::group(['prefix' => 'mod_bus', 'as' => 'mod_bus.'], function () {
            Route::group(['prefix' => 'buses', 'as' => 'buses.'], function () {
                Route::post('delete', 'ModbusBusController@delete')->name('delete');
            });
            Route::group(['prefix' => 'slavers', 'as' => 'slavers.'], function () {
                Route::post('delete', 'ModbusSlaverController@delete')->name('delete');
                Route::post('registers', 'ModbusSlaverController@getRegisters')->name('registers');
                Route::post('network_assembly', 'ModbusSlaverController@networkAssembly')->name('network_assembly');
                Route::post('network_expansion', 'ModbusSlaverController@networkExpansion')->name('network_expansion');
                Route::post('switch_status', 'ModbusSlaverController@switchStatus')->name('switch_status');
                Route::post('set_brightness', 'ModbusSlaverController@setBrightness')->name('set_brightness');
                Route::post('set_cct', 'ModbusSlaverController@setCct')->name('set_cct');
                Route::post('add_dali_device_to_group', 'ModbusSlaverController@addDaliDeviceToGroup')->name('add_dali_device_to_group');
                Route::post('remove_dali_device_from_group', 'ModbusSlaverController@removeDaliDeviceFromGroup')->name('remove_dali_device_from_group');
                Route::post('create_dali_device_group', 'ModbusSlaverController@createDaliDeviceGroup')->name('create_dali_device_group');
                Route::post('remove_dali_device_group', 'ModbusSlaverController@removeDaliDeviceGroup')->name('remove_dali_device_group');
                Route::post('all', 'ModbusSlaverController@getSlavers')->name('get');
            });
            Route::group(['prefix' => 'registers', 'as' => 'registers.'], function () {
                Route::post('delete', 'ModbusRegisterController@delete')->name('delete');
                Route::post('read', 'ModbusRegisterController@read')->name('read');
                Route::post('write', 'ModbusRegisterController@write')->name('write');
            });
        });

        Route::group(['prefix' => 'recorders', 'as' => 'recorders.'], function () {
            Route::post('sort', 'RecorderController@sort')->name('sort');
            Route::post('delete', 'RecorderController@delete')->name('delete');
        });

        Route::group(['prefix' => 'labels', 'as' => 'labels.'], function () {
            Route::post('related_parameters', 'LabelController@relatedParameters')->name('related_parameters');
        });

        Route::group(['prefix' => 'led_tapes', 'as' => 'led_tapes.'], function () {
            Route::post('delete', 'LedTapeController@delete')->name('delete');
        });

        Route::group(['prefix' => 'regulators', 'as' => 'regulators.'], function () {
            Route::post('delete', 'RegulatorController@delete')->name('delete');
        });

        Route::group(['prefix' => 'conditioners', 'as' => 'conditioners.'], function () {
            Route::post('delete', 'ConditionerController@delete')->name('delete');
            Route::group(['prefix' => 'set', 'as' => 'set.'], function () {
                Route::post('status', 'ConditionerController@setStatus')->name('status');
                Route::post('temp', 'ConditionerController@setTemp')->name('temp');
                Route::post('mode', 'ConditionerController@setMode')->name('mode');
                Route::post('fan', 'ConditionerController@setFan')->name('fan');
                Route::post('vdir', 'ConditionerController@setVdir')->name('vdir');
                Route::post('hdir', 'ConditionerController@setHdir')->name('hdir');
            });
        });

        Route::group(['prefix' => 'devices', 'as' => 'devices.'], function () {
            Route::post('delete', 'DeviceController@delete')->name('delete');
            Route::post('update', 'DeviceController@update')->name('update');
            Route::post('ports', 'DeviceController@ports')->name('ports');
            Route::post('ports/update', 'DeviceController@updatePort')->name('ports.update');
            Route::post('check/server', 'DeviceController@checkServer')->name('check.server');
            Route::post('objects_ports', 'DeviceController@objectsPorts')->name('objects_ports');
            Route::post('type_controller', 'DeviceController@typeController')->name('type_controller');
            Route::post('get', 'DeviceController@get')->name('get');
            Route::post('extension_module/delete', 'DeviceController@extensionModuleDelete')->name('extension_module.delete');
        });

        Route::group(['prefix' => 'boiler', 'as' => 'boiler.'], function () {
            Route::post('auto/delete', 'BoilerController@boilerAutoDelete')->name('auto.delete');
        });

        Route::group(['prefix' => 'views', 'as' => 'views.'], function () {
            Route::post('delete', 'ViewController@delete')->name('delete');
            Route::post('active', 'ViewController@active')->name('active');
            Route::post('sort', 'ViewController@sort')->name('sort');
        });

        Route::post('manometr/delete', 'ManometrController@delete')->name('manometr.delete');
        Route::post('termostats/delete', 'TermostatController@delete')->name('termostats.delete');
        Route::post('hygrostats/delete', 'HygrostatController@delete')->name('hygrostats.delete');
        Route::post('lightstats/delete', 'LightstatController@delete')->name('lightstats.delete');
        Route::post('pressurestats/delete', 'PressurestatController@delete')->name('pressurestats.delete');
        Route::post('carbdioxides/delete', 'CarbdioxideController@delete')->name('carbdioxides.delete');
        Route::post('usensors/delete', 'UsensorController@delete')->name('usensors.delete');
        Route::post('drycontacts/delete', 'DrycontactController@delete')->name('drycontacts.delete');
        Route::post('motionsensors/delete', 'MotionsensorController@delete')->name('motionsensors.delete');
        Route::post('carbmonoxide/delete', 'CarbmonoxideController@delete')->name('carbmonoxide.delete');
        Route::post('virtuals/delete', 'VirtualsController@delete')->name('virtuals.delete');
        Route::post('engineering/delete', 'EngineeringController@delete')->name('engineering.delete');
        Route::post('locks/delete', 'LockController@delete')->name('locks.delete');

        Route::group(['prefix' => 'curtains', 'as' => 'curtains.'], function () {
            Route::post('delete', 'CurtainsController@delete')->name('delete');
            Route::post('stop', 'CurtainsController@stop')->name('stop');
            Route::group(['prefix' => 'set', 'as' => 'set.'], function () {
                Route::post('status', 'CurtainsController@setStatus')->name('status');
                Route::post('percent', 'CurtainsController@setPercent')->name('percent');
            });
        });

        Route::group(['prefix' => 'yandexstations', 'as' => 'yandexstations.'], function () {
            Route::post('delete', 'YandexStationController@delete')->name('delete');
            Route::post('auth', 'YandexStationController@auth')->name('auth');
            Route::post('sync_stations', 'YandexStationController@syncStations')->name('sync_stations');
            Route::post('get_qr', 'YandexStationController@getQr')->name('get_qr');
            Route::post('login_qr', 'YandexStationController@loginQr')->name('login_qr');
        });

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
            Route::post('generate_server_id', 'SettingController@generateServerId')->name('generate_server_id');
            Route::post('delete', 'SettingController@delete')->name('delete');
        });

        Route::group(['prefix' => 'users', 'as' => 'users.'], function () {
            Route::post('delete', 'UserController@delete')->name('delete');
        });

        Route::group(['prefix' => 'actions', 'as' => 'actions.'], function () {
            Route::post('getForEvent', 'ActionController@getForEvent')->name('getForEvent');
            Route::post('add', 'ActionController@addAction')->name('add');
            Route::post('delete', 'ActionController@delete')->name('delete');
        });

        Route::group(['prefix' => 'event', 'as' => 'event.'], function () {
            Route::post('create', 'EventController@create')->name('create');
            Route::post('update', 'EventController@update')->name('update');
            Route::post('delete', 'EventController@delete')->name('delete');
            Route::post('create', 'EventController@create')->name('create');
        });

        Route::group(['prefix' => 'page', 'as' => 'page.'], function () {
            Route::post('delete', 'PageController@delete')->name('delete');
            Route::post('store', 'PageController@store')->name('store');
            Route::post('update/name', 'PageController@updateName')->name('update.name');
            Route::post('update/link', 'PageController@updateLink')->name('update.link');
        });

        Route::group(['prefix' => 'element', 'as' => 'element.'], function () {
            Route::post('sort', 'ElementController@sort')->name('sort');
            Route::post('active', 'ElementController@active')->name('active');
            Route::post('delete', 'ElementController@delete')->name('delete');
            Route::post('update/image', 'ElementController@updateImage')->name('update.image');
            Route::post('update/name', 'ElementController@updateName')->name('update.name');
        });

        Route::group(['prefix' => 'menu', 'as' => 'menu.'], function () {
            Route::post('sort', 'MenuController@sort')->name('sort');
            Route::post('active', 'MenuController@active')->name('active');
            Route::post('delete', 'MenuController@delete')->name('delete');
            Route::post('store', 'MenuController@store')->name('store');
            Route::post('update/image', 'MenuController@updateImage')->name('update.image');
            Route::post('update/name', 'MenuController@updateName')->name('update.name');
            Route::post('add', 'MenuController@add')->name('add');
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
            Route::post('properties', 'ObjectController@properties')->name('properties');
            Route::post('methodsAndHandles', 'ObjectController@methodsAndHandles')->name('methodsAndHandles');
            Route::post('view/all', 'ObjectController@getViewAll')->name('view.all');
            Route::post('delete', 'ObjectController@delete')->name('delete');
            Route::post('delete/all', 'ObjectController@deleteAll')->name('delete.all');
            Route::post('store', 'ObjectController@store')->name('store');

            Route::group(['prefix' => 'sensor', 'as' => 'sensor.'], function () {
                Route::post('add_param', 'ObjectController@addParam')->name('add_param');
                Route::post('get_params', 'ObjectController@getParams')->name('get_params');
                Route::post('add_address_param', 'ObjectController@addAddressParam')->name('add_address_param');
                Route::post('delete_param', 'ObjectController@deleteParam')->name('delete_param');
                Route::post('delete', 'ObjectController@sensorDelete')->name('delete');
            });
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

        Route::post('load_yandexstations', 'YandexStationController@load')->name('yandexstations.load');

        Route::post('add_object_to_port', 'ObjectController@addObjectToPort');
        Route::post('add_method_to_port', 'PortController@addMethodToPort');

        Route::post('getmethod', 'PortController@getViewMethod');
        Route::post('loaddata', 'PortController@getViewData')->name('load.data');
        Route::post('savemethod', 'PortController@storeMethod');

        Route::post('scheduler/delete', 'SchedulerController@delete')->name('scheduler.delete');
        Route::post('scheduler/validation/name', 'SchedulerController@validateName')->name('scheduler.validation.name');
        Route::post('scheduler/system', 'SchedulerController@system')->name('scheduler.system');
        Route::post('scheduler/hidden', 'SchedulerController@hidden')->name('scheduler.hidden');
        Route::post('scheduler/active', 'SchedulerController@active')->name('scheduler.active');

        Route::group(['prefix' => 'points', 'as' => 'points.'], function () {
            Route::post('delete', 'SchedulerPointController@delete')->name('delete');
            Route::post('store', 'SchedulerPointController@store')->name('store');
        });

        Route::post('graphs/termostats/period/data', 'GraphController@getTermostatsPeriodData')->name('graphs.termostats.period.data');
        Route::post('graphs/humidities/period/data', 'GraphController@getHumiditiesPeriodData')->name('graphs.humidities.period.data');
        Route::post('graphs/lights/period/data', 'GraphController@getLightsPeriodData')->name('graphs.lights.period.data');
        Route::post('graphs/pressures/period/data', 'GraphController@getPressuresPeriodData')->name('graphs.pressures.period.data');
        Route::post('graphs/carbdioxides/period/data', 'GraphController@getCarbdioxidesPeriodData')->name('graphs.carbdioxides.period.data');
        Route::post('graphs/counts/period/data', 'GraphController@getCountsPeriodData')->name('graphs.counts.period.data');
        Route::post('graphs/sensors_params/period/data', 'GraphController@getSensorsParamsPeriodData')->name('graphs.sensors_params.period.data');

        Route::group(['prefix' => 'scripts', 'as' => 'scripts.'], function () {
            Route::post('delete', 'ScriptController@delete')->name('delete');
        });
    });
});

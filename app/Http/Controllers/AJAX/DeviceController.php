<?php

namespace App\Http\Controllers\AJAX;

use Illuminate\Http\Request;
use App\Device AS device;
use App\Devtypes AS devtypes;
use App\Port AS port;
use App\Http\Controllers\Controller;


class DeviceController extends Controller
{

    /**
     * Сохранение настроек контроллера
     *
     */
    public function save_device_settings()
    {
        device::save_device_settings($_POST['id_device'], $_POST['description'], $_POST['ip_device']);
    }

    /**
     * Добавление новго устройства
     */
    public function newdevice()
    {

        //Добавляем устройство в таблицу устройств
        $id_new_device = device::newdevice($_POST['type'], $_POST['description'], $_POST['ip_device']);

        //TODO: запрос в таблицу типов устройств для получения данных о типе устройства
        $devtype = devtypes::where('id', $_POST['type'])->firstOrFail();


        //Возвращаем id добавленного устройства
        return response()->json(array('success' => true, 'id_new_device'=>$id_new_device,
            'totalports' => $devtype->total_ports, 'start_in' => $devtype->start_in, 'end_in' => $devtype->end_in,
            'start_out' => $devtype->start_out, 'end_out' => $devtype->end_out));
    }

    /**
     * Удаление устройства
     *
     */
    public function deletedevice()
    {
        //Удаление самого устройства
        device::where('id',$_POST['id_device'])->delete();

        //Удаление портов устройства
        port::where('id_device',$_POST['id_device'])->delete();
    }
}



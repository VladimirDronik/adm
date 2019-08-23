<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{

    public $timestamps = false;


    /**
     * Сохранение настроек контроллера
     *
     * @param int id
     * @param string description
     * @param string ip_address
     */
    static public function save_device_settings($id, $description, $ip_address)
    {
        device::where('id', $id)->update(['description' => $description, 'ip_address' => $ip_address]);
    }

    /**
     * Добавление нового устройства
     *
     * @param int type
     * @param string description
     * @param string ip_address
     */
    static public function newdevice($type, $description, $ip_address)
    {

       $lastid =  device::insertGetId(['type' => $type, 'description' => $description,  'active' => 0, 'ip_address' => $ip_address]);


       return $lastid;
    }
}

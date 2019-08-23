<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Device
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Device newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Device newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Device query()
 * @mixin \Eloquent
 */
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
        Device::where('id', $id)->update(['description' => $description, 'ip_address' => $ip_address]);
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
       $lastid =  Device::insertGetId(['type' => $type, 'description' => $description,  'active' => 0, 'ip_address' => $ip_address]);

       return $lastid;
    }
}

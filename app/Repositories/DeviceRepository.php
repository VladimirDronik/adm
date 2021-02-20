<?php

namespace App\Repositories;

use App\Models\Device;
use App\Models\DevType;
use Illuminate\Support\Facades\DB;

class DeviceRepository {

    public function getByName($name = '', $pagination_count = 30)
    {
        $query = Device::with('devtype');

        if (!empty($name)) {
            $query->where('name','like','%'.$name.'%');
        }

        return $query->orderBy('id')->paginate($pagination_count);
    }

    public function getAllToArray()
    {
        $devices = Device::select('id', 'description')->orderBy('description')
            ->pluck('description','id')->toArray();

        return $devices;
    }

    /**
     * Вывод всех устройств по типу
     */
    public function getAllByTypeToArray()
    {

        $devices = DB::table('devices')->join('devtypes', 'devices.type', '=', 'devtypes.id')
            ->select('devices.id', 'description')
            ->where('devtypes.name','=','Hite-pro')
            ->orderBy('description')
            ->pluck('description','devices.id')
            ->toArray();

        return $devices;

    }

    public function getDevTypesToArray()
    {
        return DevType::orderBy('id')->pluck('name','id')->toArray();
    }

    public static function getDevTypeById($id)
    {
        return DevType::select('name')->where('id', $id)->first()->name;
    }

    public static function getDevByIdDevice($id)
    {
        $device = Device::where('id', $id)->first();
        $type = '';
        $address = '';
        $password = '';

        if($id) {
            $type = self::getDevTypeById(Device::where('id', $id)->with('devtype')->first()->type);
            $address = $device->ip_address;
            $password = $device->password;
        }

        return ['type' => $type, 'address' => $address, 'password' => $password];
    }

    public static function getAllDevicesForConfigs()
    {
        return Device::select('id')->where('active', 1)
            ->where('changed', 1)->get();
    }

}
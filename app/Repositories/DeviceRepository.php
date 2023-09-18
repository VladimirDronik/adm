<?php

namespace App\Repositories;

use App\Models\Device;
use App\Models\DevType;
use App\Models\Port;

class DeviceRepository
{
    public function getByName($name = '', $pagination_count = 30)
    {
        $query = Device::with('devtype');

        if (! empty($name)) {
            $query->where('name', 'like', '%'.$name.'%');
        }

        return $query->orderBy('id')->paginate($pagination_count);
    }

    public function getAllToArray()
    {

        //Выводим все контроллеры, кроме Hite-pro
        $devices = Device::select('devices.id', 'devices.description')
            ->join('devtypes', 'devices.type', '=', 'devtypes.id', 'left outer')
            ->pluck('devices.description', 'devices.id')->toArray();

        return $devices;
    }

    /**
     * Вывод всех устройств по типу, кроме перечисленных
     */
    public function getAllWithoutTypesToArray(array $devicesTypes = [])
    {

        $query = Device::query();

        $query->join('devtypes', 'devices.type', '=', 'devtypes.id')
            ->select('devices.id', 'description');

        foreach ($devicesTypes as $devType) {

            $query->where('devtypes.name', '!=', $devType);
        }

        $devices = $query->orderBy('description')
            ->pluck('description', 'devices.id')
            ->toArray();

        return $devices;

    }

    /**
     * Вывод всех устройств по типу
     */
    public function getAllByTypesToArray(array $devicesTypes, $pluck = true)
    {

        $query = Device::query();

        $query->join('devtypes', 'devices.type', '=', 'devtypes.id')
            ->select('devices.id', 'description');

        foreach ($devicesTypes as $devType) {
            $query->orwhere('devtypes.name', $devType);
        }

        //Управляем форматом вывода - для загрузки через страницу используем pluck, для загрузки через AJAX не используем
        if ($pluck) {
            $devices = $query->orderBy('description')
                ->pluck('description', 'devices.id')
                ->toArray();
        } else {
            $devices = $query->orderBy('description')
                ->get()->toArray();
        }

        return $devices;

    }

    public function getDevTypesToArray()
    {
        return DevType::orderBy('name')->pluck('name', 'name')->toArray();
    }

    public static function getDevTypeById($id)
    {
        return DevType::select('name')->where('id', $id)->first()->name;
    }

    public function getIdTypeByName($name)
    {
        return DevType::select('id')->where('name', $name)->first()->id;

    }

    public static function getDevByIdDevice($id)
    {
        $device = Device::where('id', $id)->first();
        $type = '';
        $address = '';
        $password = '';

        if ($id) {
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

    /**
     * Получение id устройства по id порта
     */
    public static function getDevByPort($idPort)
    {

        return Port::select('id_device')->where('id', $idPort)->first()->id_device;
    }
}

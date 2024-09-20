<?php

namespace App\Repositories;

use App\Models\Port;
use App\Models\Device;
use App\Models\DevType;
use Illuminate\Database\Eloquent\Collection;

class DeviceRepository
{
    public function getByName(string $name = '', int $perPage = 30)
    {
        $query = Device::with('devtype');

        if (! empty($name)) {
            $query->where('name', 'like', '%'.$name.'%');
        }

        return $query->orderBy('id')->paginate($perPage);
    }

    public function getAllToArray(): array
    {
        // Выводим все контроллеры, кроме Hite-pro
        return Device::select('devices.id', 'devices.description')
            ->join('devtypes', 'devices.type', '=', 'devtypes.id', 'left outer')
            ->pluck('devices.description', 'devices.id')
            ->toArray();
    }

    /**
     * Вывод всех устройств по типу, кроме перечисленных
     */
    public function getAllWithoutTypesToArray(array $devicesTypes = []): array
    {
        $query = Device::query();

        $query->join('devtypes', 'devices.type', '=', 'devtypes.id')
            ->select('devices.id', 'description');

        foreach ($devicesTypes as $devType) {
            $query->where('devtypes.name', '!=', $devType);
        }

        return $query->orderBy('description')
            ->pluck('description', 'devices.id')
            ->toArray();
    }

    /**
     * Вывод всех устройств по типу
     */
    public function getAllByTypesToArray(array $devicesTypes, bool $pluck = true): array
    {
        $query = Device::query();

        $query->join('devtypes', 'devices.type', '=', 'devtypes.id')
            ->select('devices.id', 'description');

        foreach ($devicesTypes as $devType) {
            $query->orwhere('devtypes.name', $devType);
        }

        // Управляем форматом вывода - для загрузки через страницу используем pluck, для загрузки через AJAX не используем
        if ($pluck) {
            $devices = $query->orderBy('description')
                ->pluck('description', 'devices.id')
                ->toArray();
        } else {
            $devices = $query->orderBy('description')
                ->get()
                ->toArray();
        }

        return $devices;
    }

    public function getDevTypesToArray(): array
    {
        return DevType::orderBy('name')
            ->pluck('name', 'name')
            ->toArray();
    }

    public static function getDevTypeById(int $id)
    {
        return DevType::select('name')
            ->where('id', $id)
            ->first()
            ->name;
    }

    public function getIdTypeByName(string $name)
    {
        return DevType::select('id')
            ->where('name', $name)
            ->first()
            ->id;
    }

    public static function getDevByIdDevice(int $id): array
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

    public static function getAllDevicesForConfigs(): Collection
    {
        return Device::select('id')
            ->where('active', 1)
            ->where('changed', 1)
            ->get();
    }

    /**
     * Получение id устройства по id порта
     */
    public static function getDevByPort(int $idPort)
    {
        return Port::select('id_device')
            ->where('id', $idPort)
            ->first()
            ->id_device;
    }
}

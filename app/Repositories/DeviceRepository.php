<?php

namespace App\Repositories;

use App\Models\Device;
use App\Models\DevType;

class DeviceRepository {

    public function getByName($name = '', $pagination_count = 15)
    {
        $query = Device::with('devtype');

        if (!empty($name)) {
            $query->where('name','like','%'.$name.'%');
        }

        return $query->orderBy('id')->paginate($pagination_count);
    }

    public function getAllToArray()
    {
        $devices = Device::select('id','description')->orderBy('description')->pluck('description','id')->toArray();
        array_walk($devices, function (&$device, $key) { $device = $key.' - '.$device; });

        return $devices;
    }

    public function getDevTypesToArray()
    {
        return DevType::orderBy('id')->pluck('name','id')->toArray();
    }
}
<?php

namespace App\Repositories;

use App\Models\Device;
use App\Models\DevType;

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

    public function getDevTypesToArray()
    {
        return DevType::orderBy('id')->pluck('name','id')->toArray();
    }

    public function getDevTypeById($id)
    {
        return DevType::select('name')->where('id', $id)->first()->name;
    }

    public function getDevByIdDevice($id)
    {
        return $this->getDevTypeById(Device::where('id', $id)->with('devtype')->first());
    }
}
<?php

namespace App\Repositories;

use App\Models\Device;
use App\Models\ExtensionModuleType;
use App\Models\Port;


class ExtensionModuleRepository {

    public function getPortsForModule(Device $device)
    {
        return $device->ports()->where('status', 'I2C')->pluck('num_port');
    }

    public function getModuleTypes()
    {
        return ExtensionModuleType::pluck('name', 'id');
    }
}
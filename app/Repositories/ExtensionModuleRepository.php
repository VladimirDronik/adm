<?php

namespace App\Repositories;

use App\Models\Device;
use App\Models\ExtensionModuleType;

class ExtensionModuleRepository
{
    public function getPortsForModuleByStatus(Device $device, string $status)
    {
        return $device->ports()->where('status', $status)->pluck('num_port');
    }

    public function getModuleTypes()
    {
        return ExtensionModuleType::pluck('name', 'id');
    }
}

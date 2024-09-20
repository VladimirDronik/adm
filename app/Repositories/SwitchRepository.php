<?php

namespace App\Repositories;

use App\Models\Port;
use App\Models\DeviceSwitch;

class SwitchRepository
{
    public function getAll(int $perPage = 30)
    {
        $switches = DeviceSwitch::with('object')
            ->orderBy('id', 'desc')
            ->paginate($perPage);

        $switches->map(function ($item) {
            $port = Port::where('object', $item->object->id)->first();
            if ($port) {
                $item->port = $port;
            }
        });

        return $switches;
    }
}

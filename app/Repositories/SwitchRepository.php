<?php

namespace App\Repositories;

use App\Models\DeviceSwitch;
use App\Models\Port;

class SwitchRepository
{
    public function getAll($pagination_count = 30)
    {
        $switches = DeviceSwitch::with('object')->orderBy('id', 'desc')->paginate($pagination_count);

        $switches->map(function ($item) {
            $port = Port::where('object', $item->object->id)->first();
            if ($port) {
                $item->port = $port;
            }
        });

        return $switches;
    }
}

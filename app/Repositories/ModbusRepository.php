<?php

namespace App\Repositories;

use App\Models\ModbusBus;
use App\Models\ModbusRegister;
use App\Models\ModbusSlaver;
use App\Models\ModbusSlaversType;

class ModbusRepository
{
    public function getAllBusesByType(string $type, $elementsPerPage = 30)
    {
        return ModbusBus::where('type', $type)->paginate($elementsPerPage);
    }

    public function getAllBusesToArray()
    {
        return ModbusBus::select('id', 'device')
            ->orderBy('device')
            ->pluck('device', 'id')
            ->toArray();
    }

    public function getBusesWhereHasSlaversToArray()
    {
        return ModbusBus::whereHas('slavers')
            ->select('id', 'device')
            ->orderBy('device')
            ->pluck('device', 'id')
            ->toArray();
    }

    public function getAllSlavers(int $bus = null, $elementsPerPage = 30)
    {
        $slavers = ModbusSlaver::query();

        if ($bus) {
            $slavers->where('bus', $bus);
        }

        return $slavers->paginate($elementsPerPage);
    }

    public function getSlaversWhereHasRegistersToArray()
    {
        return ModbusSlaver::whereHas('registers')
            ->select('id', 'name')
            ->orderBy('name')
            ->pluck('name', 'id')
            ->toArray();
    }

    public function getAllSlaversToArray()
    {
        return ModbusSlaver::select('id', 'name')
            ->orderBy('name')
            ->pluck('name', 'id')
            ->toArray();
    }

    public function getAllSlaversTypesToArray()
    {
        return ModbusSlaversType::select('id', 'name')
            ->orderBy('name')
            ->pluck('name', 'id')
            ->toArray();
    }

    public function getAllRegisters(int $slaver = null, int $isSystem = null, $elementsPerPage = 30)
    {
        $registers = ModbusRegister::query();

        if ($slaver) {
            $registers->where('slaver_id', $slaver);
        }

        if (!$isSystem) {
            $registers->where('is_system', 0);
        }

        return $registers->paginate($elementsPerPage);
    }

    public function getRegistersBySlaverToArray(int $slaverId)
    {
        return ModbusRegister::where('slaver_id', $slaverId)
            ->orderBy('name')
            ->pluck('name', 'id')
            ->toArray();
    }
}

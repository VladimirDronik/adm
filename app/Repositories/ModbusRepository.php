<?php

namespace App\Repositories;

use App\Models\ModbusBus;
use App\Models\DaliDevice;
use App\Models\ModbusSlaver;
use App\Models\ModbusRegister;
use App\Models\ModbusSlaversType;

class ModbusRepository
{
    public function getAllBusesByType(string $type, int $perPage = 30)
    {
        return ModbusBus::where('type', $type)->paginate($perPage);
    }

    public function getAllBusesToArray(): array
    {
        return ModbusBus::select('id', 'device')
            ->orderBy('device')
            ->pluck('device', 'id')
            ->toArray();
    }

    public function getBusesWhereHasSlaversToArray(): array
    {
        return ModbusBus::whereHas('slavers')
            ->select('id', 'device')
            ->orderBy('device')
            ->pluck('device', 'id')
            ->toArray();
    }

    public function getAllSlavers(?int $bus = null, int $perPage = 30)
    {
        $slavers = ModbusSlaver::query();

        if ($bus) {
            $slavers->where('bus', $bus);
        }

        return $slavers->paginate($perPage);
    }

    public function getSlaversWhereHasRegistersToArray(): array
    {
        return ModbusSlaver::whereHas('registers')
            ->select('id', 'name')
            ->orderBy('name')
            ->pluck('name', 'id')
            ->toArray();
    }

    public function getAllSlaversToArray(): array
    {
        return ModbusSlaver::select('id', 'name')
            ->orderBy('name')
            ->pluck('name', 'id')
            ->toArray();
    }

    public function getAllByTypePurpose(array $purposes): array
    {
        return ModbusSlaver::whereHas('relatedType', function ($query) use ($purposes) {
            $query->whereIn('purpose', $purposes);
        })->select('id', 'name')->orderBy('name')->pluck('name', 'id')->toArray();
    }

    /**
     * Получить список устройств отфильтрованных по полям типа устройства
     */
    public function getFilteredSlaversToArray(array $purpose, ?bool $relay = null): array
    {
        return ModbusSlaver::select('id', 'name')->whereHas('relatedType', function ($query) use ($purpose, $relay) {
            $query->whereIn('purpose', $purpose);

            if ($relay !== null) {
                $query->orWhere('relay', $relay);
            }
        })
            ->orderBy('name')
            ->pluck('name', 'id')
            ->toArray();
    }

    public function getAllSlaversTypesToArray(): array
    {
        return ModbusSlaversType::select('id', 'name')
            ->orderBy('name')
            ->pluck('name', 'id')
            ->toArray();
    }

    public function getAllRegisters(?int $slaver = null, ?int $isSystem = null, int $perPage = 30)
    {
        $registers = ModbusRegister::query();

        if ($slaver) {
            $registers->where('slaver_id', $slaver);
        }

        if (! $isSystem) {
            $registers->where('is_system', 0);
        }

        return $registers->paginate($perPage);
    }

    public function getRegistersBySlaverToArray(int $slaverId): array
    {
        return ModbusRegister::where('slaver_id', $slaverId)
            ->orderBy('name')
            ->pluck('name', 'id')
            ->toArray();
    }

    public function getDaliDevicesNotRelatedToCurrentGroupToArray(int $currentDeviceId): array
    {
        return DaliDevice::whereDoesntHave('groups', function ($query) use ($currentDeviceId) {
            $query->where('id', $currentDeviceId);
        })
            ->where('is_group', 0)
            ->orderBy('name')
            ->pluck('name', 'id_object')
            ->toArray();
    }
}

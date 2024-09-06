<?php

namespace App\Services\Modbus;

use App\Models\ModbusBus;

class BusService
{
    public function prepare(ModbusBus $bus, array $data)
    {
        switch ($data['type']) {
            case ModbusBus::TYPE_RTU:
                if (array_key_exists('device_select', $data)) {
                    $bus->device = $data['device_select'];
                }
                $bus->baudrate = $data['baudrate'];
                $bus->length = $data['length'];
                $bus->parity = $data['parity'];
                $bus->stopbits = $data['stopbits'];
                break;
            case ModbusBus::TYPE_TCP:
                $bus->device = $data['device_text'];
                $bus->ip_address = $data['ip_address'];
                $bus->port = $data['port'];
                break;
        }
    }

    /**
     * Создание шины
     */
    public function store(array $data): int
    {
        $bus = new ModbusBus();

        $bus->type = $data['type'];
        $this->prepare($bus, $data);

        $bus->save();

        return $bus->id;
    }

    /**
     * Изменение шины
     */
    public function update(ModbusBus $bus, array $data): int
    {
        $data['type'] = $bus->type;
        $this->prepare($bus, $data);

        $bus->save();

        exec('supervisorctl restart modbus_id'.$bus->id);

        return $bus->id;
    }

    /**
     * Удалить шину
     *
     * @return bool
     */
    public function delete(int $id)
    {
        return ModbusBus::destroy($id);

        return true;
    }
}

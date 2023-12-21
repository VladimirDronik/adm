<?php

namespace App\Services\Modbus;

use App\Models\ModbusSlaver;

class SlaverService
{
    public function prepare(ModbusSlaver $slaver, array $data)
    {
        $slaver->name = $data['name'];
        $slaver->type = $data['type'];
        $slaver->bus = $data['bus'];
        $slaver->address = $data['address'];
    }

    /**
     * Создание устройства
     */
    public function store(array $data): int
    {
        $slaver = new ModbusSlaver();

        $this->prepare($slaver, $data);

        $slaver->save();

        return $slaver->id;
    }

    /**
     * Изменение устройства
     */
    public function update(ModbusSlaver $slaver, array $data): int
    {
        $this->prepare($slaver, $data);

        $slaver->save();

        return $slaver->id;
    }

    /**
     * Удалить устройство
     *
     * @return bool
     */
    public function delete(int $id)
    {
        return ModbusSlaver::destroy($id);

        return true;
    }
}

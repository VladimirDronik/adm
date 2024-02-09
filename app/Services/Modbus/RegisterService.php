<?php

namespace App\Services\Modbus;

use App\Models\ModbusRegister;

class RegisterService
{
    public function prepare(ModbusRegister $register, array $data)
    {
        $register->name = $data['name'];
        $register->slaver_id = $data['slaver_id'];
        $register->register_type = $data['register_type'];
        $register->starting_register = $data['starting_register'];
        $register->registers_quantity = $data['registers_quantity'];
        $register->data_format = $data['data_format'];
        $register->units = array_key_exists('units', $data) ? $data['units'] : null;
        $register->scale_unit = array_key_exists('scale_unit', $data) ? $data['scale_unit'] : null;
        $register->access = $data['access'];

        $polling = array_key_exists('polling', $data);
        $register->polling = $polling;
        $register->polling_cycle = $polling ? $data['polling_cycle'] : null;
    }

    /**
     * Создание регистра
     */
    public function store(array $data): int
    {
        $register = new ModbusRegister();

        $this->prepare($register, $data);

        $register->save();

        return $register->id;
    }

    /**
     * Изменение регистра
     */
    public function update(ModbusRegister $register, array $data): int
    {
        $this->prepare($register, $data);

        $register->save();

        return $register->id;
    }

    /**
     * Удалить регистр
     *
     * @return bool
     */
    public function delete(int $id)
    {
        return ModbusRegister::destroy($id);

        return true;
    }
}

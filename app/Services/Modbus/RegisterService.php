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

    /**
     * Запуск скрипта чтения данных регистра
     *
     * @param int $registerId
     * @return array
     */
    public function read(int $registerId): array
    {
        $output = [];
        $resultCode = null;

        chdir(env('SERVER_FOLDER').'/scripts');
        exec('php modbus_read.php ' . $registerId, $output, $resultCode);

        return [
            'code' => $resultCode,
            'output' => $output,
        ];
    }

    /**
     * Запуск скрипта записи данных регистра
     *
     * @param int $registerId
     * @param string $value
     * @return array
     */
    public function write(int $registerId, string $value): array
    {
        $output = [];
        $resultCode = null;

        $register = ModbusRegister::find($registerId);

        chdir(env('SERVER_FOLDER').'/scripts');
        exec('php modbus_write.php ' . $registerId . ' ' . $value, $output, $resultCode);

        if ($resultCode === 0) {
            $register->update(['last_value' => $value]);
        }

        return [
            'code' => $resultCode,
            'output' => $output,
        ];
    }
}

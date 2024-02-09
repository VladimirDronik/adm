<?php

namespace App\Services\Modbus;

use App\Models\DaliDevice;
use App\Models\HomeObject;
use App\Models\ModbusSlaver;
use App\Models\ObjType;
use App\Models\Script;
use App\Models\Method;
use App\Models\ModbusRegister;
use Database\Seeders\ScriptsTableSeeder;
use Illuminate\Support\Facades\DB;

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

        DB::transaction(function () use ($slaver) {
            $slaver->save();

            $pathToJson = storage_path('app/modbus_registers/' . $slaver->relatedType->type . '.json');
            $registersData = [];

            if (file_exists($pathToJson)) {
                $registersData = json_decode(file_get_contents($pathToJson), true);
            }

            if (!empty($registersData)) {
                $registersData = array_map(function ($registerData) use ($slaver) {
                    $registerData['slaver_id'] = $slaver->id;
                    return $registerData;
                }, $registersData);

                ModbusRegister::insert($registersData);
            }

            if ($slaver->relatedType->type == 'ecodim-dali-gw2') {
                $this->addAdditionalRegistersForEcodimDali($slaver->id);
            }
        });

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
        $modbusSlaver = ModbusSlaver::findOrFail($id);

        DB::transaction(function () use ($modbusSlaver) {
            if ($modbusSlaver->relatedType->type == 'ecodim-dali-gw2' && $modbusSlaver->daliDevices->isNotEmpty()) {
                foreach ($modbusSlaver->daliDevices as $daliDevice) {
                    if ($daliDevice->object) {
                        $daliDevice->object->delete();
                    } else {
                        $daliDevice->delete();
                    }
                }
            }

            $modbusSlaver->delete();
        });

        return true;
    }

    /**
     * Изменение устройства DALI
     */
    public function updateDaliDevice(DaliDevice $daliDevice, array $data): int
    {
        $daliDevice->name = $data['name'];

        $daliDevice->room = $data['room'];

        $daliDevice->save();

        return $daliDevice->id;
    }

    /**
     * Запуск скрипта сборки сети для DALI
     *
     * @param int $id
     * @return array
     */
    public function networkAssembly(int $id): array
    {
        $output = [];
        $resultCode = null;

        chdir(env('SERVER_FOLDER').'/scripts');
        exec('php dali_assembling.php ' . $id, $output, $resultCode);

        $this->createDaliDevicesObjectsAndMethods();

        return [
            'code' => $resultCode,
            'output' => $output,
        ];
    }

    /**
     * Запуск скрипта расширения сети для DALI
     *
     * @param int $id
     * @return array
     */
    public function networkExpansion(int $id): array
    {
        $output = [];
        $resultCode = null;

        chdir(env('SERVER_FOLDER').'/scripts');
        exec('php dali_expanding.php ' . $id, $output, $resultCode);

        $this->createDaliDevicesObjectsAndMethods();

        return [
            'code' => $resultCode,
            'output' => $output,
        ];
    }

    /**
     * Создание объектов и методов для всех записей в таблице dali_devices
     *
     * @return void
     */
    private function createDaliDevicesObjectsAndMethods(): void
    {
        $daliDevices = DaliDevice::get();

        if ($daliDevices->isNotEmpty()) {
            $scripts = ScriptsTableSeeder::getDaliDeviceScripts();
            $methods = [];

            foreach ($scripts as $script) {
                $scriptId = $this->getScriptId($script);
                $methods[] = [
                    'name' => $script['name'],
                    'script' => $scriptId,
                    'comment' => $script['name'],
                    'is_system' => 1,
                ];
            }

            foreach ($daliDevices as $daliDevice) {
                if (!$daliDevice->id_object) {
                    $uniqueName = HomeObject::getUniqueObjectName(0, $daliDevice->name);

                    $object = HomeObject::create([
                        'name' => $uniqueName,
                        'type' => ObjType::TYPE_DALI,
                        'status' => 'off',
                        'is_system' => 1,
                    ]);

                    $daliDevice->update([
                        'id_object' => $object->id,
                    ]);

                    foreach ($methods as $method) {
                        $method['id_object'] = $object->id;
                        Method::create($method);
                    }
                }
            }
        }
    }

    private function getScriptId(array $scriptArray): int
    {
        $script = Script::where('link', $scriptArray['link'])
            ->where('system', 1)
            ->first();

        if (! $script) {
            $script = Script::create($scriptArray);
        }

        return $script->id;
    }

    /**
     * Добавление дополнительных регистров для устройства типа ecodim-dali-gw2, которые формируются в цикле
     *
     * @return void
     */
    private function addAdditionalRegistersForEcodimDali(int $slaverId): void
    {
        $registersData = [];

        for ($address = 0; $address < 64; $address ++) {
            $registers = [
                [
                    'slaver_id' => $slaverId,
                    'name' => 'Установка уровня яркости устройства А' . $address,
                    'alias' => 'dali_set_brightness_a' . $address,
                    'starting_register' => 3000 + $address * 5,
                    'access' => 'rw',
                    'register_type' => 'holding',
                    'registers_quantity' => 1,
                    'data_format' => 'u16',
                    'polling' => 0,
                    'is_system' => 1,
                ],
                [
                    'slaver_id' => $slaverId,
                    'name' => 'Команда управления устройством А' . $address,
                    'alias' => 'dali_send_cmd_a' . $address,
                    'starting_register' => 3001 + $address * 5,
                    'access' => 'rw',
                    'register_type' => 'holding',
                    'registers_quantity' => 1,
                    'data_format' => 'u16',
                    'polling' => 0,
                    'is_system' => 1,
                ],
                [
                    'slaver_id' => $slaverId,
                    'name' => 'Присутствие на шине устройства А' . $address,
                    'alias' => 'dali_is_on_bus_a' . $address,
                    'starting_register' => 3002 + $address * 5,
                    'access' => 'ro',
                    'register_type' => 'holding',
                    'registers_quantity' => 1,
                    'data_format' => 'u16',
                    'polling' => 0,
                    'is_system' => 1,
                ],
                [
                    'slaver_id' => $slaverId,
                    'name' => 'Запрос состояния устройства А' . $address,
                    'alias' => 'dali_device_status_a' . $address,
                    'starting_register' => 3003 + $address * 5,
                    'access' => 'ro',
                    'register_type' => 'holding',
                    'registers_quantity' => 1,
                    'data_format' => 'u16',
                    'polling' => 0,
                    'is_system' => 1,
                ],
                [
                    'slaver_id' => $slaverId,
                    'name' => 'Запрос текущего уровня яркости устройства А' . $address,
                    'alias' => 'dali_get_brightness_a' . $address,
                    'starting_register' => 3004 + $address * 5,
                    'access' => 'ro',
                    'register_type' => 'holding',
                    'registers_quantity' => 1,
                    'data_format' => 'u16',
                    'polling' => 0,
                    'is_system' => 1,
                ],
                [
                    'slaver_id' => $slaverId,
                    'name' => 'Установка цветовой температуры устройства А' . $address,
                    'alias' => 'dali_set_temperature_a' . $address,
                    'starting_register' => 3320 + $address * 5,
                    'access' => 'rw',
                    'register_type' => 'holding',
                    'registers_quantity' => 1,
                    'data_format' => 'u16',
                    'polling' => 0,
                    'is_system' => 1,
                ],
                [
                    'slaver_id' => $slaverId,
                    'name' => 'Регулирование цветовой температурой устройства А' . $address,
                    'alias' => 'dali_set_temperature_by_step_a' . $address,
                    'starting_register' => 3321 + $address * 5,
                    'access' => 'rw',
                    'register_type' => 'holding',
                    'registers_quantity' => 1,
                    'data_format' => 'u16',
                    'polling' => 0,
                    'is_system' => 1,
                ],
                [
                    'slaver_id' => $slaverId,
                    'name' => 'Запрос вариантов управления цветом устройства А' . $address,
                    'alias' => 'dali_cct_variants_a' . $address,
                    'starting_register' => 3322 + $address * 5,
                    'access' => 'ro',
                    'register_type' => 'holding',
                    'registers_quantity' => 1,
                    'data_format' => 'u16',
                    'polling' => 0,
                    'is_system' => 1,
                ],
                [
                    'slaver_id' => $slaverId,
                    'name' => 'Запрос статуса устройства А' . $address,
                    'alias' => 'dali_temperature_status_a' . $address,
                    'starting_register' => 3323 + $address * 5,
                    'access' => 'ro',
                    'register_type' => 'holding',
                    'registers_quantity' => 1,
                    'data_format' => 'u16',
                    'polling' => 0,
                    'is_system' => 1,
                ],
                [
                    'slaver_id' => $slaverId,
                    'name' => 'Запрос цветовой температуры устройства А' . $address,
                    'alias' => 'dali_get_temperature_a' . $address,
                    'starting_register' => 3324 + $address * 5,
                    'access' => 'ro',
                    'register_type' => 'holding',
                    'registers_quantity' => 1,
                    'data_format' => 'u16',
                    'polling' => 0,
                    'is_system' => 1,
                ],
            ];

            $registersData = array_merge($registersData, $registers);
        }

        ModbusRegister::insert($registersData);
    }
}

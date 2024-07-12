<?php

namespace App\Services\Modbus;

use App\Models\Script;
use App\Models\Method;
use App\Models\LedTape;
use App\Models\ObjType;
use App\Models\DaliDevice;
use App\Models\HomeObject;
use App\Models\ModbusSlaver;
use App\Models\ModbusRegister;
use App\Models\ConditionerType;
use Illuminate\Support\Facades\DB;
use App\Services\ConditionerService;
use Database\Seeders\ScriptsTableSeeder;

class SlaverService
{
    public function __construct(
        private ConditionerService $conditionerService
    ) {
    }

    /**
     * Создание устройства
     */
    public function store(array $data): int
    {
        $slaver = new ModbusSlaver();
        $slaver->name = $data['name'];
        $slaver->type = $data['type'];
        $slaver->bus = $data['bus'];
        $slaver->address = $data['address'];

        $dbWriting = DB::transaction(function () use ($slaver) {
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

            return true;
        });

        if ($dbWriting === true) {
            switch ($slaver->relatedType->type) {
                case 'ecodim-dali-gw2':
                    $this->addAdditionalRegistersForEcodimDali($slaver->id);
                    break;
                case 'wb-led':
                    $wbLedOperMode = $data['wb_led_oper_mode'];
                    $wbLedModeRegister = $slaver->registers()->where('alias', 'wb_led_mode')->first();

                    if ($wbLedModeRegister) {
                        $result = $this->writeToRegister($wbLedModeRegister->id, $wbLedOperMode);
                        if ($result['code'] === 0) {
                            $this->addLedTapesWithObjectsAndMethods($slaver, $wbLedOperMode);
                        }
                    }
                    break;
            }
        }

        $this->checkAvailability($slaver->id);

        if ($slaver->relatedType->purpose == 'ac' && ConditionerType::where('device', $slaver->relatedType->type)->exists()) {
            $this->conditionerService->store([
                'name' => 'Кондиционер устройства - ' . $slaver->name,
                'modbus_slaver_id' => $slaver->id,
            ]);
        }

        return $slaver->id;
    }

    /**
     * Изменение устройства
     */
    public function update(ModbusSlaver $slaver, array $data): int
    {
        if ($slaver->relatedType->type == 'wb-led' && $data['wb_led_oper_mode'] != $data['old_wb_led_oper_mode']) {
            $wbLedOperMode = $data['wb_led_oper_mode'];
            $wbLedModeRegister = $slaver->registers()->where('alias', 'wb_led_mode')->first();

            if ($wbLedModeRegister) {
                $result = $this->writeToRegister($wbLedModeRegister->id, $wbLedOperMode);
                if ($result['code'] === 0) {
                    if ($slaver->ledTapes->isNotEmpty()) {
                        foreach ($slaver->ledTapes as $ledTape) {
                            $ledTape->object->delete();
                        }
                    }

                    $this->addLedTapesWithObjectsAndMethods($slaver, $wbLedOperMode);
                }
            }
        }

        $slaver->name = $data['name'];
        $slaver->bus = $data['bus'];
        $slaver->address = $data['address'];
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
            switch ($modbusSlaver->relatedType->type) {
                case 'ecodim-dali-gw2':
                    if ($modbusSlaver->daliDevices->isNotEmpty()) {
                        foreach ($modbusSlaver->daliDevices as $daliDevice) {
                            if ($daliDevice->object) {
                                $daliDevice->object->delete();
                            } else {
                                $daliDevice->delete();
                            }
                        }
                    }
                    break;
                case 'wb-led':
                    if ($modbusSlaver->ledTapes->isNotEmpty()) {
                        foreach ($modbusSlaver->ledTapes as $ledTape) {
                            $ledTape->object->delete();
                        }
                    }
                    break;
            }

            if ($modbusSlaver->relatedType->purpose == 'ac') {
                foreach ($modbusSlaver->conditioners as $conditioner) {
                    $conditioner->object->delete();
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
        DB::transaction(function () use (&$daliDevice, $data) {
            $newName = trim($data['name']);

            if ($daliDevice->id_object && $daliDevice->name != $newName) {
                $daliDevice->object->name = HomeObject::getUniqueObjectName($daliDevice->id_object, $newName);
                $daliDevice->object->save();
            }

            $daliDevice->name = $newName;
            $daliDevice->room = $data['room'];

            $daliDevice->save();
        });

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
     * Запуск скриптов включения и отключения устройства DALI
     *
     * @param int $objectId
     * @return array
     */
    public function switchDaliStatus(int $objectId): array
    {
        $output = [];
        $resultCode = null;

        $object = HomeObject::find($objectId);

        if ($object) {
            chdir(env('SERVER_FOLDER').'/scripts');

            if ($object->status == 'off') {
                exec('php dali_on.php ' . $objectId, $output, $resultCode);
                $newStatus = 'on';
            } else {
                exec('php dali_off.php ' . $objectId, $output, $resultCode);
                $newStatus = 'off';
            }

            if ($resultCode === 0) {
                $object->update(['status' => $newStatus]);
            }
        }

        return [
            'code' => $resultCode,
            'output' => $output,
        ];
    }

    /**
     * Запуск скрипта установки яркости устройства DALI
     *
     * @param int $daliId
     * @param null|int $brightness
     * @return array
     */
    public function setDaliBrightness(int $daliId, ?int $brightness): array
    {
        $output = [];
        $resultCode = null;

        $daliDevice = DaliDevice::find($daliId);

        if ($daliDevice && $daliDevice->id_object && $brightness) {
            chdir(env('SERVER_FOLDER').'/scripts');
            exec('php dali_set_brightness.php ' . $daliDevice->id_object . ' ' . $brightness, $output, $resultCode);

            if ($resultCode === 0) {
                $daliDevice->update(['brightness' => $brightness]);
            }
        }

        return [
            'code' => $resultCode,
            'output' => $output,
        ];
    }

    /**
     * Запуск скрипта установки цветовой температуры устройства DALI
     *
     * @param int $daliId
     * @param null|int $cct
     * @return array
     */
    public function setDaliCct(int $daliId, ?int $cct): array
    {
        $output = [];
        $resultCode = null;

        $daliDevice = DaliDevice::find($daliId);

        if ($daliDevice && $daliDevice->id_object && $cct) {
            chdir(env('SERVER_FOLDER').'/scripts');
            exec('php dali_set_cct.php ' . $daliDevice->id_object . ' ' . $cct, $output, $resultCode);

            if ($resultCode === 0) {
                $daliDevice->update(['cct' => $cct]);
            }
        }

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
                        'status' => 'on',
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
                    'is_system' => 1,
                ],
            ];

            $registersData = array_merge($registersData, $registers);
        }

        ModbusRegister::insert($registersData);
    }

    /**
     * Создание объектов и методов лед лент для устройства типа wb-led
     *
     * @return void
     */
    private function addLedTapesWithObjectsAndMethods(ModbusSlaver $slaver, int $operationMode): void
    {
        $ledTapesData = $slaver->getLedTapesDataByCode($operationMode);

        if (!empty($ledTapesData)) {
            $scripts = ScriptsTableSeeder::getLedTapeScripts();
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

            foreach ($ledTapesData as $ledTapeData) {
                $uniqueName = HomeObject::getUniqueObjectName(0, $ledTapeData['name']);
                $object = HomeObject::create([
                    'type' => ObjType::TYPE_TAPE,
                    'name' => $uniqueName,
                    'status' => 'off',
                    'is_system' => 1,
                ]);
                $ledTapeData['id_object'] = $object->id;

                foreach ($methods as $method) {
                    $method['id_object'] = $object->id;
                    Method::create($method);
                }

                LedTape::create($ledTapeData);
            }
        }
    }

    /**
     * Запуск скрипта записи значения регистру устройства
     *
     * @param int $registerId
     * @param int $value
     * @return array
     */
    public function writeToRegister(int $registerId, string $value): array
    {
        $output = [];
        $resultCode = null;

        chdir(env('SERVER_FOLDER').'/scripts');
        exec('php modbus_write.php ' . $registerId . ' ' . $value, $output, $resultCode);

        return [
            'code' => $resultCode,
            'output' => $output,
        ];
    }

    /**
     * Запуск скрипта проверки доступности устройства
     *
     * @param int $slaverId
     * @return array
     */
    public function checkAvailability(int $slaverId): array
    {
        $output = [];
        $resultCode = null;

        chdir(env('SERVER_FOLDER').'/scripts');
        exec('php modbus_check_availability.php ' . $slaverId, $output, $resultCode);

        return [
            'code' => $resultCode,
            'output' => $output,
        ];
    }
}

<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Port;
use App\Models\ObjType;
use App\Models\Regulator;
use App\Models\HomeObject;
use App\Models\ModbusSlaver;
use App\Models\SensorsParam;
use Illuminate\Support\Facades\DB;

class RegulatorService
{
    public function __construct(
        private SensorService $sensorService,
    ) {
    }

    /**
     * Создание регулятора
     *
     * @throws \Throwable
     */
    public function store(array $data): array
    {
        $regulator = DB::transaction(function () use ($data) {
            $uniqueName = HomeObject::getUniqueObjectName(0, $data['name']);
            $object = new HomeObject();
            $object->type = ObjType::TYPE_REGULATOR;
            $object->name = $uniqueName;
            $object->status = 'on';
            $object->is_system = 1;
            $object->save();

            $regulator = new Regulator();
            $regulator->object_id = $object->id;
            $regulator->room = $data['room'];
            $regulator->min_setpoint = $data['min_setpoint'];
            $regulator->max_setpoint = $data['max_setpoint'];

            if (array_key_exists('independent_device', $data)) {
                switch ($data['source']) {
                    case 'modbus':
                        $slaver = ModbusSlaver::find($data['modbus_slaver']);

                        $regulator->source_id = $data['modbus_slaver'];
                        $regulator->type = $slaver->relatedType->purpose;

                        switch ($regulator->type) {
                            case 'thermostat':
                                $sensorObjectId = $this->sensorService->store([
                                    'name' => 'Датчик температуры '.$uniqueName,
                                    'room' => $data['room'],
                                    'type' => 'custom',
                                    'source' => $data['source'],
                                    'source_id' => $data['modbus_slaver'],
                                    'parent_id' => $object->id,
                                ]);

                                $sensorsParam = new SensorsParam();
                                $sensorsParam->object_id = $sensorObjectId;
                                $sensorsParam->param = 'temperature';
                                $sensorsParam->name = 'Температура';
                                $sensorsParam->get_param = $data['modbus_register'];
                                $sensorsParam->units = 'celsius';
                                $sensorsParam->accuracy = 1;
                                $sensorsParam->graph = 1;
                                $sensorsParam->min_range = -50;
                                $sensorsParam->max_range = 300;
                                $sensorsParam->timestamp = Carbon::now();
                                $sensorsParam->save();

                                $regulator->sensors_param_id = $sensorsParam->id;
                                break;
                            case 'hygrostat':
                                $sensorObjectId = $this->sensorService->store([
                                    'name' => 'Датчик влажности '.$uniqueName,
                                    'room' => $data['room'],
                                    'type' => 'custom',
                                    'source' => $data['source'],
                                    'source_id' => $data['modbus_slaver'],
                                    'parent_id' => $object->id,
                                ]);

                                $sensorsParam = new SensorsParam();
                                $sensorsParam->object_id = $sensorObjectId;
                                $sensorsParam->param = 'humidity';
                                $sensorsParam->name = 'Влажность';
                                $sensorsParam->get_param = $data['modbus_register'];
                                $sensorsParam->units = 'percent';
                                $sensorsParam->accuracy = 0;
                                $sensorsParam->graph = 1;
                                $sensorsParam->min_range = 0;
                                $sensorsParam->max_range = 100;
                                $sensorsParam->timestamp = Carbon::now();
                                $sensorsParam->save();

                                $regulator->sensors_param_id = $sensorsParam->id;
                                break;
                        }
                        break;
                    case 'megad':
                        $regulator->source_id = $data['port'];
                        $regulator->type = 'thermostat';

                        $sensorObjectId = $this->sensorService->store([
                            'name' => 'Датчик температуры '.$uniqueName,
                            'room' => $data['room'],
                            'type' => 'ds18b20',
                            'input_source' => $data['source'],
                            'source_id' => $data['device'],
                            'port' => $data['port'],
                            'connection' => '1w',
                            'parent_id' => $object->id,
                        ]);

                        $sensorsParam = SensorsParam::where('object_id', $sensorObjectId)
                            ->where('param', 'temperature')
                            ->first();

                        $regulator->sensors_param_id = $sensorsParam->id;
                        break;
                }

                $regulator->setpoint = 0;
                $regulator->source = $data['source'];
            } else {
                $sensorsParam = SensorsParam::find($data['sensor_param']);
                switch ($sensorsParam->param) {
                    case 'temperature':
                        $regulator->type = 'thermostat';
                        break;
                    case 'humidity':
                        $regulator->type = 'hygrostat';
                        break;
                    default:
                        $regulator->type = 'other';
                        break;
                }

                $regulator->sensors_param_id = $data['sensor_param'];
                $regulator->setpoint = $data['setpoint'];
                $regulator->hysteresis = $data['hysteresis'];
                $regulator->higher_method = $data['higher_method'];
                $regulator->lower_method = $data['lower_method'];
                $regulator->fallback_method = $data['fallback_method'];
                $regulator->higher_method_params = $data['higher_method_params'];
                $regulator->lower_method_params = $data['lower_method_params'];
                $regulator->fallback_method_params = $data['fallback_method_params'];
            }

            $regulator->save();

            return $regulator;
        });

        $getScriptResult = $this->regulatorGetScript($regulator->object_id);
        $redirectToEdit = $getScriptResult['code'] === 0;

        return [
            'redirect_to_edit' => $redirectToEdit,
            'regulator' => $regulator,
        ];
    }

    /**
     * Изменение регулятора
     *
     * @throws \Throwable
     */
    public function update(Regulator $regulator, array $data): array
    {
        DB::transaction(function () use (&$regulator, $data) {
            $newName = trim($data['name']);
            if ($regulator->object->name != $newName) {
                $regulator->object->name = HomeObject::getUniqueObjectName($regulator->object_id, $newName);
            }

            $regulator->object->status = $data['status'];

            $regulator->room = $data['room'];
            $regulator->min_setpoint = $data['min_setpoint'];
            $regulator->max_setpoint = $data['max_setpoint'];
            $regulator->setpoint = $data['setpoint'];

            if (!$regulator->source) {
                $sensorsParam = SensorsParam::find($data['sensor_param']);
                switch ($sensorsParam->param) {
                    case 'temperature':
                        $regulator->type = 'thermostat';
                        break;
                    case 'humidity':
                        $regulator->type = 'hygrostat';
                        break;
                    default:
                        $regulator->type = 'other';
                        break;
                }

                $regulator->sensors_param_id = $data['sensor_param'];
                $regulator->hysteresis = $data['hysteresis'];

                $regulator->higher_method = $data['higher_method'];
                $regulator->lower_method = $data['lower_method'];
                $regulator->fallback_method = $data['fallback_method'];
                $regulator->higher_method_params = $data['higher_method_params'];
                $regulator->lower_method_params = $data['lower_method_params'];
                $regulator->fallback_method_params = $data['fallback_method_params'];
            } else {
                $sensorObject = HomeObject::find($regulator->sensorsParam->object_id);
                $sensorSettings = $sensorObject->sensors;

                $sensorSettings->where('name', 'room')->first()->update([
                    'value' => $data['room'],
                ]);

                switch ($regulator->source) {
                    case 'modbus':
                        $slaver = ModbusSlaver::find($data['modbus_slaver']);

                        $regulator->source_id = $data['modbus_slaver'];
                        $regulator->type = $slaver->relatedType->purpose;

                        $sensorSettings->where('name', 'source_id')->first()->update([
                            'value' => $data['modbus_slaver'],
                        ]);

                        switch ($regulator->type) {
                            case 'thermostat':
                                $uniqueName = HomeObject::getUniqueObjectName($sensorObject->id, 'Датчик температуры '.$newName);

                                $regulator->sensorsParam->update([
                                    'param' => 'temperature',
                                    'name' => 'Температура',
                                    'get_param' => $data['modbus_register'],
                                    'units' => 'celsius',
                                    'accuracy' => 1,
                                    'graph' => 1,
                                    'min_range' => -50,
                                    'max_range' => 300,
                                ]);
                                break;
                            case 'hygrostat':
                                $uniqueName = HomeObject::getUniqueObjectName($sensorObject->id, 'Датчик влажности '.$newName);

                                $regulator->sensorsParam->update([
                                    'param' => 'humidity',
                                    'name' => 'Влажность',
                                    'get_param' => $data['modbus_register'],
                                    'units' => 'percent',
                                    'accuracy' => 0,
                                    'graph' => 1,
                                    'min_range' => 0,
                                    'max_range' => 100,
                                ]);
                                break;
                        }
                        break;
                    case 'megad':
                        $regulator->source_id = $data['port'];

                        $uniqueName = HomeObject::getUniqueObjectName($sensorObject->id, 'Датчик температуры '.$newName);

                        $sensorSettings->where('name', 'source_id')->first()->update([
                            'value' => $data['device'],
                        ]);

                        $sensorSettings->where('name', 'port')->first()->update([
                            'value' => $data['port'],
                        ]);

                        Port::where('id', $data['port'])->update([
                            'object' => $sensorObject->id,
                            'comment' => $uniqueName
                        ]);
                        break;
                }

                $sensorObject->name = $uniqueName;
                $sensorObject->save();
            }

            $regulator->object->save();
            $regulator->save();

            //Сохраняем данные в таблицу Алисы или включаем запись если она есть уже
            if (isset($data['alice_checkbox'])) {
                AliceDevicesService::addOrReplaceDevice(
                    $regulator->object_id,
                    $data['alice_command'],
                    $data['alice_room'],
                );
            } else {
                AliceDevicesService::setActive($regulator->object_id, 0);
            }
        });

        $setScriptResult = $this->regulatorSetScript($regulator->object_id);
        $redirectToEdit = $setScriptResult['code'] === 0;

        return [
            'redirect_to_edit' => $redirectToEdit,
            'regulator' => $regulator,
        ];
    }

    /**
     * Удаление регулятора и связанных объектов.
     *
     * @throws \Throwable
     */
    public function delete(int $id): bool
    {
        $regulator = Regulator::findOrFail($id);

        $regulator->object->delete();

        return true;
    }

    /**
     * Запуск скрипта regulator_get
     */
    public function regulatorGetScript(int $objectId): array
    {
        $output = [];
        $resultCode = null;

        chdir(env('SERVER_FOLDER').'/scripts');
        exec('php regulator_get.php '.$objectId, $output, $resultCode);

        return [
            'code' => $resultCode,
            'output' => $output,
        ];
    }

    /**
     * Запуск скрипта regulator_set
     */
    public function regulatorSetScript(int $objectId): array
    {
        $output = [];
        $resultCode = null;

        chdir(env('SERVER_FOLDER').'/scripts');
        exec('php regulator_set.php '.$objectId, $output, $resultCode);

        return [
            'code' => $resultCode,
            'output' => $output,
        ];
    }
}

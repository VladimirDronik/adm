<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Device;
use App\Models\ObjType;
use App\Models\Regulator;
use App\Models\HomeObject;
use App\Models\ModbusSlaver;
use App\Models\SensorsParam;
use Illuminate\Support\Facades\DB;
use App\Services\Modbus\RegisterService;

class RegulatorService
{
    public function __construct(
        private SensorService $sensorService,
        private RegisterService $registerService,
    ) {
    }

    /**
     * Создание регулятора
     *
     * @throws \Throwable
     */
    public function store(array $data): int
    {
        return DB::transaction(function () use ($data) {
            $object = new HomeObject();
            $object->type = ObjType::TYPE_REGULATOR;
            $object->name = HomeObject::getUniqueObjectName(0, $data['name']);
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
                        $slaver = ModbusSlaver::with('registers')->find($data['modbus_slaver']);

                        $regulator->source_id = $data['modbus_slaver'];
                        $regulator->type = $slaver->relatedType->purpose;

                        switch ($regulator->type) {
                            case 'thermostat':
                                $sensorObjectId = $this->sensorService->store([
                                    'name' => 'Датчик температуры '.$slaver->name,
                                    'room' => $data['room'],
                                    'type' => 'custom',
                                    'source' => $data['source'],
                                    'source_id' => $data['modbus_slaver'],
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
                                    'name' => 'Датчик влажности '.$slaver->name,
                                    'room' => $data['room'],
                                    'type' => 'custom',
                                    'source' => $data['source'],
                                    'source_id' => $data['modbus_slaver'],
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

                        $setpoint = 0;
                        $setpointRegister = $slaver->registers()->where('alias', 'setpoint')->first();
                        $stateRegister = $slaver->registers()->where('alias', 'state')->first();

                        if ($setpointRegister) {
                            $setpointRegisterData = $this->registerService->read($setpointRegister->id);

                            if ($setpointRegisterData['code'] === 0) {
                                $setpoint = $setpointRegisterData['output'];
                            }
                        }

                        if ($stateRegister) {
                            $stateRegisterData = $this->registerService->read($stateRegister->id);

                            if ($setpointRegisterData['code'] === 0) {
                                // TODO Что делать с полученными данными из state регистра?
                            }
                        }

                        $regulator->setpoint = $setpoint;
                        break;
                    case 'megad':
                        $device = Device::find($data['device']);
                        $regulator->source_id = $data['device'];
                        $regulator->type = 'thermostat';
                        $regulator->setpoint = $data['megad_setpoint'];

                        $sensorObjectId = $this->sensorService->store([
                            'name' => 'Датчик температуры '.$device->description,
                            'room' => $data['room'],
                            'type' => 'ds18b20',
                            'input_source' => $data['source'],
                            'source_id' => $data['device'],
                            'port' => $data['port'],
                            'connection' => '1w',
                        ]);

                        $sensorsParam = SensorsParam::where('object_id', $sensorObjectId)
                            ->where('param', 'temperature')
                            ->first();

                        $regulator->sensors_param_id = $sensorsParam->id;
                        break;
                }

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

                // TODO Куда записывать значения параметров, если выбран метод с параметрами?
                // $data['higher_method_params'];
                // $data['lower_method_params'];
                // $data['fallback_method_params'];
            }

            $regulator->save();

            return $regulator->id;
        });
    }

    /**
     * Изменение регулятора
     *
     * @throws \Throwable
     */
    public function update(Regulator $regulator, array $data): int
    {
        DB::transaction(function () use (&$regulator, $data) {
            $newName = trim($data['name']);
            if ($regulator->object->name != $newName) {
                $regulator->object->name = HomeObject::getUniqueObjectName($regulator->object_id, $newName);
                $regulator->object->save();
            }

            $regulator->room = $data['room'];
            $regulator->min_setpoint = $data['min_setpoint'];
            $regulator->max_setpoint = $data['max_setpoint'];
            $regulator->save();
        });

        return $regulator->id;
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
}

<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Port;
use App\Models\Method;
use App\Models\Script;
use App\Models\Sensor;
use App\Models\ObjType;
use App\Models\HomeObject;
use App\Models\SchedulerPoint;
use App\Models\SchedulerTask;
use App\Models\SensorsParam;
use Illuminate\Support\Facades\DB;
use Database\Seeders\ScriptsTableSeeder;

class SensorService
{
    /**
     * Создание датчика
     */
    public function store(array $data): int
    {
        return DB::transaction(function () use ($data) {
            $sensorObject = new HomeObject();
            $uniqueName = HomeObject::getUniqueObjectName(0, $data['name']);
            $sensorObject->name = $uniqueName;
            $sensorObject->type = ObjType::TYPE_SENSOR;
            $sensorObject->status = 'ok';
            $sensorObject->is_system = 1;
            $sensorObject->save();

            $room = $data['room'] ?? null;

            if ($room) {
                Sensor::create([
                    'object_id' => $sensorObject->id,
                    'name' => 'room',
                    'value' => $room,
                ]);
            }

            Sensor::create([
                'object_id' => $sensorObject->id,
                'name' => 'type',
                'value' => $data['type'],
            ]);

            switch ($data['type']) {
                case 'custom':
                    Sensor::create([
                        'object_id' => $sensorObject->id,
                        'name' => 'source',
                        'value' => $data['source'],
                    ]);

                    switch ($data['source']) {
                        case 'megad':
                            Sensor::create([
                                'object_id' => $sensorObject->id,
                                'name' => 'source_id',
                                'value' => $data['source_id'],
                            ]);

                            Sensor::create([
                                'object_id' => $sensorObject->id,
                                'name' => 'connection',
                                'value' => $data['connection'],
                            ]);

                            switch ($data['connection']) {
                                case 'i2c':
                                    Sensor::create([
                                        'object_id' => $sensorObject->id,
                                        'name' => 'sda',
                                        'value' => $data['sda'],
                                    ]);

                                    Sensor::create([
                                        'object_id' => $sensorObject->id,
                                        'name' => 'scl',
                                        'value' => $data['scl'],
                                    ]);

                                    Port::where('id', $data['sda'])->update([
                                        'object' => $sensorObject->id,
                                        'comment' => $uniqueName
                                    ]);

                                    Port::where('id', $data['scl'])->update([
                                        'object' => $sensorObject->id,
                                        'comment' => $uniqueName
                                    ]);
                                    break;
                                case '1wbus':
                                    Sensor::create([
                                        'object_id' => $sensorObject->id,
                                        'name' => 'port',
                                        'value' => $data['port'],
                                    ]);

                                    Port::where('id', $data['port'])->update([
                                        'object' => $sensorObject->id,
                                        'comment' => $uniqueName
                                    ]);
                                    break;
                                default:
                                    Sensor::create([
                                        'object_id' => $sensorObject->id,
                                        'name' => 'port',
                                        'value' => $data['port'],
                                    ]);

                                    Port::where('id', $data['port'])->update([
                                        'object' => $sensorObject->id,
                                        'comment' => $uniqueName
                                    ]);
                                    break;
                            }
                            break;
                        case 'modbus':
                            Sensor::create([
                                'object_id' => $sensorObject->id,
                                'name' => 'source_id',
                                'value' => $data['source_id'],
                            ]);
                            break;
                    }
                    break;
                case 'ds18b20':
                    Sensor::create([
                        'object_id' => $sensorObject->id,
                        'name' => 'source',
                        'value' => $data['input_source'],
                    ]);

                    Sensor::create([
                        'object_id' => $sensorObject->id,
                        'name' => 'source_id',
                        'value' => $data['source_id'],
                    ]);

                    Sensor::create([
                        'object_id' => $sensorObject->id,
                        'name' => 'connection',
                        'value' => $data['connection'],
                    ]);

                    switch ($data['connection']) {
                        case '1w':
                            $this->createSensorsParams('ds18b20_1w', $sensorObject->id);

                            Sensor::create([
                                'object_id' => $sensorObject->id,
                                'name' => 'port',
                                'value' => $data['port'],
                            ]);

                            Port::where('id', $data['port'])->update([
                                'object' => $sensorObject->id,
                                'comment' => $uniqueName
                            ]);
                            break;
                        case '1wbus':
                            Sensor::create([
                                'object_id' => $sensorObject->id,
                                'name' => 'port',
                                'value' => $data['port'],
                            ]);

                            Port::where('id', $data['port'])->update([
                                'object' => $sensorObject->id,
                                'comment' => $uniqueName
                            ]);
                            break;
                    }
                    break;
                default:
                    $this->createSensorsParams($data['type'], $sensorObject->id);

                    Sensor::create([
                        'object_id' => $sensorObject->id,
                        'name' => 'source',
                        'value' => $data['input_source'],
                    ]);

                    Sensor::create([
                        'object_id' => $sensorObject->id,
                        'name' => 'source_id',
                        'value' => $data['source_id'],
                    ]);

                    Sensor::create([
                        'object_id' => $sensorObject->id,
                        'name' => 'connection',
                        'value' => $data['input_connection'],
                    ]);

                    Sensor::create([
                        'object_id' => $sensorObject->id,
                        'name' => 'sda',
                        'value' => $data['sda'],
                    ]);

                    Port::where('id', $data['sda'])->update([
                        'object' => $sensorObject->id,
                        'comment' => $uniqueName
                    ]);

                    Sensor::create([
                        'object_id' => $sensorObject->id,
                        'name' => 'scl',
                        'value' => $data['scl'],
                    ]);

                    Port::where('id', $data['scl'])->update([
                        'object' => $sensorObject->id,
                        'comment' => $uniqueName
                    ]);
                    break;
            }

            $this->createCheckMethodWithEvent($sensorObject->id);

            chdir(env('SERVER_FOLDER').'/scripts');
            exec('php check_sensor.php '.$sensorObject->id);

            return $sensorObject->id;
        });
    }

    /**
     * Изменение датчика
     */
    public function update(HomeObject $sensorObject, array $data): int
    {
        return DB::transaction(function () use ($sensorObject, $data) {
            $uniqueName = HomeObject::getUniqueObjectName($sensorObject->id, $data['name']);
            $sensorObject->name = $uniqueName;
            $sensorObject->save();

            $room = $data['room'] ?? null;
            $sensorSettings = $sensorObject->sensors;

            if ($room) {
                Sensor::updateOrCreate([
                        'object_id' => $sensorObject->id,
                        'name' => 'room',
                ], ['value' => $room]);
            } else {
                Sensor::where('object_id', $sensorObject->id)
                    ->where('name', 'room')
                    ->delete();
            }

            Sensor::updateOrCreate([
                'object_id' => $sensorObject->id,
                'name' => 'source_id',
            ], ['value' => $data['source_id']]);

            if ($sensorSettings->where('name', 'source')->first()?->value == 'megad') {
                if ($sensorSettings->where('name', 'connection')->first()?->value != 'i2c') {
                    Port::where('id', $sensorSettings->where('name', 'port')->first()?->value)->update([
                        'object' => null,
                        'comment' => ''
                    ]);

                    Port::where('id', $data['port'])->update([
                        'object' => $sensorObject->id,
                        'comment' => $uniqueName
                    ]);

                    Sensor::updateOrCreate([
                        'object_id' => $sensorObject->id,
                        'name' => 'port',
                    ], ['value' => $data['port']]);
                } else {
                    Port::where('id', $sensorSettings->where('name', 'sda')->first()?->value)->update([
                        'object' => null,
                        'comment' => ''
                    ]);

                    Port::where('id', $data['sda'])->update([
                        'object' => $sensorObject->id,
                        'comment' => $uniqueName
                    ]);

                    Port::where('id', $sensorSettings->where('name', 'scl')->first()?->value)->update([
                        'object' => null,
                        'comment' => ''
                    ]);

                    Port::where('id', $data['scl'])->update([
                        'object' => $sensorObject->id,
                        'comment' => $uniqueName
                    ]);

                    Sensor::updateOrCreate([
                        'object_id' => $sensorObject->id,
                        'name' => 'sda',
                    ], ['value' => $data['sda']]);

                    Sensor::updateOrCreate([
                        'object_id' => $sensorObject->id,
                        'name' => 'scl',
                    ], ['value' => $data['scl']]);
                }
            }

            return $sensorObject->id;
        });
    }

    /**
     * Удалить устройство
     *
     * @return bool
     */
    public function delete(int $id)
    {
        $sensor = HomeObject::findOrFail($id);

        DB::transaction(function () use ($sensor) {
            $sensor->delete();
        });

        return true;
    }

    private function createSensorsParams(string $jsonName, int $sensorObjectId)
    {
        $pathToJson = storage_path('app/sensors/'.$jsonName.'.json');
        $sensorsParamsData = [];

        if (file_exists($pathToJson)) {
            $sensorsParamsData = json_decode(file_get_contents($pathToJson), true);
        }

        if (! empty($sensorsParamsData)) {
            $sensorsParamsData = array_map(function ($sensorsParamData) use ($sensorObjectId) {
                $sensorsParamData['object_id'] = $sensorObjectId;
                $sensorsParamData['timestamp'] = Carbon::now();

                return $sensorsParamData;
            }, $sensorsParamsData);

            SensorsParam::insert($sensorsParamsData);
        }
    }

    public function sensorDelete(int $id)
    {
        return HomeObject::where('type', ObjType::TYPE_SENSOR)
            ->where('id', $id)
            ->delete();
    }

    public function updateOrCreateParam(array $data)
    {
        if ($data['id']) {
            $sensorsParam = SensorsParam::find($data['id']);
        } else {
            $sensorsParam = new SensorsParam();
        }

        $sensorsParam->object_id = $data['object_id'];
        $sensorsParam->name = $data['name'];
        $sensorsParam->param = $data['param'];
        $sensorsParam->get_param = $data['get_param'];
        $sensorsParam->value = $data['value'];
        $sensorsParam->units = $data['units'];
        $sensorsParam->accuracy = $data['accuracy'];
        $sensorsParam->graph = array_key_exists('graph', $data);
        $sensorsParam->min_range = $data['min_range'];
        $sensorsParam->max_range = $data['max_range'];
        $sensorsParam->min_alarm = $data['min_alarm'];
        $sensorsParam->max_alarm = $data['max_alarm'];
        $sensorsParam->timestamp = $data['timestamp'] ?? Carbon::now();

        $sensorsParam->save();

        return true;
    }

    public function createAddressParam(array $data)
    {
        $sensorsParam = new SensorsParam();
        $sensorsParam->object_id = $data['object_id'];
        $sensorsParam->name = 'Температура';
        $sensorsParam->param = 'temperature';
        $sensorsParam->get_param = 'cmd=get&addr=' . $data['address'];
        $sensorsParam->units = 'celsius';
        $sensorsParam->accuracy = 1;
        $sensorsParam->graph = 1;
        $sensorsParam->min_range = -55;
        $sensorsParam->max_range = 125;
        $sensorsParam->timestamp = Carbon::now();

        $sensorsParam->save();

        return true;
    }

    public function sensorsParamDelete(int $id)
    {
        return SensorsParam::where('id', $id)->delete();
    }

    private function getOrCreateCheckSensorScript(): Script
    {
        $script = Script::where('link', 'check_sensor.php')
            ->where('system', 1)
            ->first();

        if (!$script) {
            $script = Script::create(
                ScriptsTableSeeder::getCheckSensorScript()
            );
        }

        return $script;
    }

    /**
     * Создание метода 'Проверка датчика' и элемента планировщика 'Проверка датчика' (каждую минуту)
     */
    private function createCheckMethodWithEvent(int $objectId)
    {
        $script = $this->getOrCreateCheckSensorScript();

        $method = Method::create([
            'name' => 'Проверка датчика',
            'id_object' => $objectId,
            'comment' => 'Периодическая проверка текущих значений датчика',
            'is_system' => 1,
            'script' => $script->id,
        ]);

        $schedulerTask = SchedulerTask::create([
            'name' => 'Проверка датчика',
            'is_system' => 1,
            'is_hidden' => 1,
            'object' => $objectId,
            'method' => $method->id,
        ]);

        // каждую минуту
        SchedulerPoint::create([
            'id_task' => $schedulerTask->id,
            'type' => 'c',
            'time' => '1',
            'days' => '',
            'close' => 1,
            'system' => 1,
        ]);
    }
}

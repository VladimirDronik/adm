<?php
/**
 * Created by PhpStorm.
 * User: kinord
 * Date: 13.04.21
 * Time: 14:58
 */

namespace App\Services;

use App\Models\HomeObject;
use App\Models\Method;
use App\Models\ModbusRegister;
use App\Models\ObjType;
use App\Models\SchedulerPoint;
use App\Models\SchedulerTask;
use App\Models\Script;
use Database\Seeders\ScriptsTableSeeder;

class BoilerObjectService
{
    /**
     * Автосоздание объекта для котла
     */
    public function createBoilerObject(string $name): HomeObject
    {
        $object = new HomeObject();

        $object->type = ObjType::TYPE_BOILER;
        $object->name = $name;
        $object->status = '1';
        $object->is_system = 1;

        $object->save();

        return $object;
    }

    /**
     * Автосоздание объекта для бойлера ГВС
     */
    public function createBoilerGVSObject(string $name): HomeObject
    {
        $object = new HomeObject();

        $object->type = ObjType::TYPE_BOILER_GVS;
        $object->name = $name;
        $object->status = '1';
        $object->is_system = 1;

        $object->save();

        return $object;
    }

    /**
     * Создание системных методов и элементов планировщика для котла
     */
    public function createMethodsAndEvents(int $objectId, ?int $modbusSlaverId = null)
    {
        $checkBoilerScript = $this->getOrCreateCheckBoilerScript();
        $slaverRegisters = collect();
        $checkBoilerRegister = null;

        if ($modbusSlaverId) {
            $slaverRegisters = ModbusRegister::where('slaver_id', $modbusSlaverId)->get();
            $checkBoilerRegister = $slaverRegisters->where('alias', 'check_boiler')->first();
        }

        $method = Method::create([
            'name' => 'Проверка котла отопления',
            'alias' => 'check_boiler',
            'id_object' => $objectId,
            'comment' => 'Периодическая проверка текущих значений котла отопления',
            'is_system' => 1,
            'script' => $checkBoilerScript->id,
            'easy' => $checkBoilerRegister ? 'm;' . $checkBoilerRegister->id : null,
        ]);

        $schedulerTask = SchedulerTask::create([
            'name' => 'Проверка котла отопления',
            'is_system' => 1,
            'is_hidden' => 1,
            'object' => $objectId,
            'method' => $method->id,
        ]);

        // каждую 1 мин
        SchedulerPoint::create([
            'id_task' => $schedulerTask->id,
            'type' => 'c',
            'time' => '1',
            'days' => '',
            'close' => 1,
            'system' => 1,
        ]);

        $systemMethods = $this->getBoilerSystemMethodsData();

        if ($slaverRegisters->isNotEmpty()) {
            foreach ($systemMethods as $systemMethod) {
                $suitableRegister = $slaverRegisters->where('alias', $systemMethod['alias'])->first();

                Method::create([
                    'name' => $systemMethod['name'],
                    'alias' => $systemMethod['alias'],
                    'id_object' => $objectId,
                    'comment' => $systemMethod['comment'],
                    'is_system' => 1,
                    'easy' => $suitableRegister ? 'm;' . $suitableRegister->id : null,
                ]);
            }
        } else {
            foreach ($systemMethods as $systemMethod) {
                Method::create([
                    'name' => $systemMethod['name'],
                    'alias' => $systemMethod['alias'],
                    'id_object' => $objectId,
                    'comment' => $systemMethod['comment'],
                    'is_system' => 1,
                ]);
            }
        }
    }

    /**
     * Запись id выбранных для методово регистров, если тип подключения modbus
     */
    public function updateMethodsEasyFieldsMethodsForModbus(HomeObject $boilerObject, array $data)
    {
        if ($boilerObject->methods->isNotEmpty()) {
            foreach ($boilerObject->methods as $method) {
                $method->update([
                    'easy' => array_key_exists('register_id_' . $method->id, $data) && $data['register_id_' . $method->id]
                        ? 'm;' . $data['register_id_' . $method->id]
                        : null,
                ]);
            }
        }
    }

    public function getOrCreateCheckBoilerScript(): Script
    {
        $script = Script::where('link', 'check_boiler.php')->where('system', 1)->first();

        if (!$script) {
            $script = Script::create(ScriptsTableSeeder::getCheckBoilerScript());
        }

        return $script;
    }

    /**
     * Данные системных методов для котла
     */
    private function getBoilerSystemMethodsData(): array
    {
        return [
            ['name' => 'Значение подачи', 'alias' => 'feed_heat_temp', 'comment' => 'Получить значение подачи отопления'],
            ['name' => 'Значение обратки', 'alias' => 'return_heat_temp', 'comment' => 'Получить значение обратки отопления'],
            ['name' => 'Значение контура ГВС', 'alias' => 'water_temp', 'comment' => 'Получить значение контура ГВС'],
            ['name' => 'Значение горелки', 'alias' => 'flame', 'comment' => 'Получить значение модуляции горелки'],
            ['name' => 'Значение давления', 'alias' => 'pressure', 'comment' => 'Получить давление котла'],
            ['name' => 'Скорость потока ГВС', 'alias' => 'flow_rate', 'comment' => 'Получить скорость потока ГВС'],
            ['name' => 'Внешняя температура', 'alias' => 'outdoor_temp', 'comment' => 'Получить внешнюю температуру'],
            ['name' => 'Внутренняя температура', 'alias' => 'indoor_temp', 'comment' => 'Получить внутреннюю температуру'],
            ['name' => 'Признак ошибки', 'alias' => 'error_flag', 'comment' => 'Считывание признака ошибки'],
            ['name' => 'Код ошибки', 'alias' => 'error_code', 'comment' => 'Считывание кода ошибки'],
            ['name' => 'Признак описания ошибки', 'alias' => 'ext_err_flag', 'comment' => 'Считывание признака расширенного описания ошибки'],
            ['name' => 'Ошибка воздушного давления', 'alias' => 'error_flow_press', 'comment' => 'Ошибка воздушного давления'],
            ['name' => 'Ошибка по газу/пламени', 'alias' => 'error_flame', 'comment' => 'Ошибка по газу/пламени'],
            ['name' => 'Ошибка внешнего управления', 'alias' => 'error_lock_control', 'comment' => 'Блокировка внешнего управления'],
            ['name' => 'Ошибка давления теплоносителя', 'alias' => 'error_low_water', 'comment' => 'Низкое давления теплоносителя'],
            ['name' => 'Ошибка по обслуживанию', 'alias' => 'error_need_service', 'comment' => 'Необходимо внешнее обслуживание'],
            ['name' => 'Ошибка по температуре', 'alias' => 'error_max_temp', 'comment' => 'Превышение максимальной температуры теплоносителя'],
        ];
    }
}

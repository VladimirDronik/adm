<?php

namespace App\Services;

use App\Models\HomeObject;
use App\Models\Method;
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
    public function createMethodsAndEvents(int $objectId)
    {
        $checkBoilerScript = $this->getOrCreateCheckBoilerScript();

        $method = Method::create([
            'name' => 'Проверка котла отопления',
            'alias' => 'check_boiler',
            'id_object' => $objectId,
            'comment' => 'Периодическая проверка текущих значений котла отопления',
            'is_system' => 1,
            'script' => $checkBoilerScript->id,
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
    }

    public function getOrCreateCheckBoilerScript(): Script
    {
        $script = Script::where('link', 'check_boiler.php')->where('system', 1)->first();

        if (! $script) {
            $script = Script::create(ScriptsTableSeeder::getCheckBoilerScript());
        }

        return $script;
    }
}

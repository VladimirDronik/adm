<?php

namespace App\Services;

use App\Models\HomeObject;
use App\Models\Method;
use App\Models\ObjType;
use App\Models\SchedulerPoint;
use App\Models\SchedulerTask;
use App\Models\Script;
use Database\Seeders\ScriptsTableSeeder;

class CarbdioxideObjectService
{
    /**
     * Автосоздание объекта для датчика углексилого газа
     */
    public function createObject(string $name): HomeObject
    {
        $object = new HomeObject();

        $object->type = ObjType::TYPE_CARBDIOXIDE;
        $object->name = $name;
        $object->status = 'off';
        $object->is_system = 1;

        $object->save();

        return $object;
    }

    public function getOrCreateCheckScriptId(): int
    {
        $scriptId = Script::where('link', 'check_carbdioxide.php')
            ->where('system', 1)
            ->value('id');

        if ($scriptId) {
            return $scriptId;
        }

        return Script::create(ScriptsTableSeeder::getCheckCarbdioxideScript())->id;
    }

    /**
     * Создание метода 'Проверка датчика углексилого газа' и задачи в расписании 'Проверка датчика углексилого газа' (каждые 5 мин)
     */
    public function createCheckMethodWithEvent(int $objectId)
    {
        $scriptId = $this->getOrCreateCheckScriptId();

        $methodId = Method::create([
            'name' => 'Проверка датчика углексилого газа',
            'id_object' => $objectId,
            'comment' => 'Периодическая проверка текущих значений датчика углексилого газа',
            'is_system' => 1,
            'script' => $scriptId,
        ])->id;

        $schedulerTaskId = SchedulerTask::create([
            'name' => 'Проверка датчика углексилого газа',
            'is_system' => 1,
            'is_hidden' => 1,
            'object' => $objectId,
            'method' => $methodId,
        ])->id;

        // каждые 5 мин
        SchedulerPoint::create([
            'id_task' => $schedulerTaskId,
            'type' => 'c',
            'time' => '5',
            'days' => '',
            'close' => 1,
            'system' => 1,
        ]);
    }
}

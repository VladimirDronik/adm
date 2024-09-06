<?php

namespace App\Services;

use App\Models\Method;
use App\Models\Script;
use App\Models\ObjType;
use App\Models\HomeObject;
use App\Models\SchedulerTask;
use App\Models\SchedulerPoint;
use Database\Seeders\ScriptsTableSeeder;

class LightstatObjectService
{
    /**
     * Автосоздание объекта для датчика температуры
     */
    public function createLightstatObject(string $name): HomeObject
    {
        $object = new HomeObject();

        $object->type = ObjType::TYPE_LIGHTSTAT;
        $object->name = $name;
        $object->status = 'off';
        $object->is_system = 1;

        $object->save();

        return $object;
    }

    public function getOrCreateCheckLightstatScriptId(): int
    {
        $scriptId = Script::where('link', 'check_lightstat.php')
            ->where('system', 1)
            ->value('id');

        if ($scriptId) {
            return $scriptId;
        }

        return Script::forceCreate(
            ScriptsTableSeeder::getCheckLightstatScript()
        )->id;
    }

    /**
     * Создание метода 'Проверка датчика освещенности' и задачи в расписании 'Проверка датчика освещенности' (каждые 5 мин)
     */
    public function createCheckMethodWithEvent(int $objectId)
    {
        $scriptId = $this->getOrCreateCheckLightstatScriptId();

        $methodId = Method::forceCreate([
            'name' => 'Проверка датчика освещенности',
            'id_object' => $objectId,
            'comment' => 'Периодическая проверка текущих значений датчика освещенности',
            'is_system' => 1,
            'script' => $scriptId,
        ])->id;

        $schedulerTaskId = SchedulerTask::forceCreate([
            'name' => 'Проверка датчика освещенности',
            'is_system' => 1,
            'is_hidden' => 1,
            'object' => $objectId,
            'method' => $methodId,
        ])->id;

        // каждые 5 мин
        SchedulerPoint::forceCreate([
            'id_task' => $schedulerTaskId,
            'type' => 'c',
            'time' => '5',
            'days' => '',
            'close' => 1,
            'system' => 1,
        ]);
    }

    /**
     * Автосоздание методов и их событий для объекта, который был
     * создан автоматически для датчика освещенности
     *
     * @return void
     */
    public function createLightstatObjectMethodsWithEvents(int $objectId)
    {
        $this->createCheckMethodWithEvent($objectId);
    }
}

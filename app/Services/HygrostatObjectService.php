<?php

namespace App\Services;

use App\Models\Method;
use App\Models\Script;
use App\Models\ObjType;
use App\Models\HomeObject;
use App\Models\SchedulerTask;
use App\Models\SchedulerPoint;
use Database\Seeders\ScriptsTableSeeder;

class HygrostatObjectService
{
    /**
     * Автосоздание объекта для датчика влажности
     */
    public function createHygrostatObject(string $name): HomeObject
    {
        $object = new HomeObject();

        $object->type = ObjType::TYPE_HYGROSTAT;
        $object->name = $name;
        $object->status = 'off';
        $object->is_system = 1;

        $object->save();

        return $object;
    }

    public function getOrCreateCheckHygrostatScriptId(): int
    {
        $scriptId = Script::where('link', 'check_hygrostat.php')
            ->where('system', 1)
            ->value('id');

        if ($scriptId) {
            return $scriptId;
        }

        return Script::forceCreate(
            ScriptsTableSeeder::getCheckHygrostatScript()
        )->id;
    }

    /**
     * Создание метода 'Проверка датчика влажности' и элемента планировщика 'Проверка датчика влажности' (каждые 5 мин)
     */
    public function createCheckMethodWithEvent(int $objectId)
    {
        $scriptId = $this->getOrCreateCheckHygrostatScriptId();

        $methodId = Method::forceCreate([
            'name' => 'Проверка датчика влажности',
            'id_object' => $objectId,
            'comment' => 'Периодическая проверка текущих значений датчика влажности',
            'is_system' => 1,
            'script' => $scriptId,
        ])->id;

        $schedulerTaskId = SchedulerTask::forceCreate([
            'name' => 'Проверка датчика влажности',
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
     * создан автоматически для датчика влажности
     *
     * @return void
     */
    public function createHygrostatObjectMethodsWithEvents(int $objectId)
    {
        $this->createCheckMethodWithEvent($objectId);
    }
}

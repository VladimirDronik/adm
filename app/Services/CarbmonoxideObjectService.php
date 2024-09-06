<?php

namespace App\Services;

use App\Models\Method;
use App\Models\Script;
use App\Models\ObjType;
use App\Models\HomeObject;
use App\Models\SchedulerTask;
use App\Models\SchedulerPoint;
use Database\Seeders\ScriptsTableSeeder;

class CarbmonoxideObjectService
{
    /**
     * Автосоздание объекта для датчика CO
     */
    public function createCarbmonoxideObject(string $name): HomeObject
    {
        $object = new HomeObject();

        $object->type = ObjType::TYPE_CARBMONOXIDE;
        $object->name = $name;
        $object->status = '';
        $object->is_system = 1;

        $object->save();

        return $object;
    }

    public function getOrCreateChecScriptId(): int
    {
        $scriptId = Script::where('link', 'check_carbmonoxide.php')
            ->where('system', 1)
            ->value('id');

        if ($scriptId) {
            return $scriptId;
        }

        return Script::forceCreate(
            ScriptsTableSeeder::getCheckCarbmonoxideScript()
        )->id;
    }

    /**
     * Создание метода 'Проверка датчика угарного газа' и элемента планировщика 'Проверка датчика угарного газа' (каждые 5 мин)
     */
    public function createCheckMethodWithEvent(int $objectId)
    {
        $scriptId = $this->getOrCreateChecScriptId();

        $methodId = Method::forceCreate([
            'name' => 'Проверка датчика угарного газа',
            'id_object' => $objectId,
            'comment' => 'Периодическая проверка текущих значений датчика угарного газа',
            'is_system' => 1,
            'script' => $scriptId,
        ])->id;

        $schedulerTaskId = SchedulerTask::forceCreate([
            'name' => 'Проверка датчика угарного газа',
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
     * создан автоматически
     *
     * @return void
     */
    public function createCarbmonoxideObjectMethodsWithEvents(int $objectId)
    {
        $this->createCheckMethodWithEvent($objectId);
    }
}

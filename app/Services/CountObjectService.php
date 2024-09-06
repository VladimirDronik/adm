<?php

namespace App\Services;

use App\Models\Method;
use App\Models\Script;
use App\Models\ObjType;
use App\Models\HomeObject;
use App\Models\SchedulerTask;
use App\Models\SchedulerPoint;
use Database\Seeders\ScriptsTableSeeder;

class CountObjectService
{
    /**
     * Автосоздание объекта для счетчика
     */
    public function createCountObject(string $name): HomeObject
    {
        $object = new HomeObject();

        $object->type = ObjType::TYPE_COUNT;
        $object->name = $name;
        $object->status = 'off';
        $object->is_system = 1;

        $object->save();

        return $object;
    }

    private function getScriptIdForCheckMethod(): ?int
    {
        $scriptName = ScriptsTableSeeder::getCheckCountScript()['name'];

        return Script::where('name', $scriptName)
            ->where('system', 1)
            ->value('id');
    }

    private function getScriptIdForResetMethod(): ?int
    {
        $scriptName = ScriptsTableSeeder::getResetCountScript()['name'];

        return Script::where('name', $scriptName)
            ->where('system', 1)
            ->value('id');
    }

    /**
     * Создание метода 'Проверка счетчика' и задачи планировщика 'Проверка счетчика' (каждый час)
     */
    public function createCheckMethodWithEvent(int $objectId)
    {
        $scriptId = $this->getScriptIdForCheckMethod();
        $methodId = Method::forceCreate([
            'name' => 'Проверка счетчика',
            'id_object' => $objectId,
            'script' => $scriptId,
            'comment' => 'Периодическая проверка текущих значений счетчика',
            'is_system' => 1,
        ])->id;

        $schedulerTaskId = SchedulerTask::forceCreate([
            'name' => 'Проверка счетчика',
            'is_system' => 1,
            'is_hidden' => 1,
            'object' => $objectId,
            'method' => $methodId,
        ])->id;

        // каждый час
        SchedulerPoint::forceCreate([
            'id_task' => $schedulerTaskId,
            'type' => 'c',
            'time' => '60',
            'days' => '',
            'close' => 1,
            'system' => 1,
        ]);
    }

    /**
     * Создание метода 'Обнуление счетчика' и задачи планировщика 'Обнуление счетчика' (каждый день в 23:55)
     */
    public function createResetMethodWithEvent(int $objectId)
    {
        $scriptId = $this->getScriptIdForResetMethod();

        $methodId = Method::forceCreate([
            'name' => 'Обнуление счетчика',
            'id_object' => $objectId,
            'script' => $scriptId,
            'comment' => 'Обнуление значений счетчика за текущий день',
            'is_system' => 1,
        ])->id;

        $schedulerTaskId = SchedulerTask::forceCreate([
            'name' => 'Обнуление счетчика',
            'is_system' => 1,
            'is_hidden' => 1,
            'object' => $objectId,
            'method' => $methodId,
        ])->id;

        // каждый день в 23:55
        SchedulerPoint::forceCreate([
            'id_task' => $schedulerTaskId,
            'type' => 'w',
            'time' => '23:55',
            'days' => '0,1,2,3,4,5,6',
            'close' => 1,
            'system' => 1,
        ]);
    }

    /**
     * Автосоздание методов и их событий для объекта, который был
     * создан автоматически для счетчика
     *
     * @return void
     */
    public function createCountObjectMethodsWithEvents(int $objectId)
    {
        $this->createCheckMethodWithEvent($objectId);
        $this->createResetMethodWithEvent($objectId);
    }
}

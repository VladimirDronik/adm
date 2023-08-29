<?php

namespace App\Services;

use App\Models\HomeObject;
use App\Models\Method;
use App\Models\ObjType;
use App\Models\SchedulerPoint;
use App\Models\SchedulerTask;
use App\Models\Script;
use Database\Seeders\ScriptsTableSeeder;

class CountObjectService {

    /**
     * Автосоздание объекта для счетчика
     *
     * @param string $name
     * @return HomeObject
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

    private function getScriptIdForCheckMethod(): int
    {
        $script_name = ScriptsTableSeeder::getCheckCountScript()['name'];

        return Script::where('name', $script_name)
            ->where('system', 1)->value('id');
    }

    private function getScriptIdForResetMethod(): int
    {
        $script_name = ScriptsTableSeeder::getResetCountScript()['name'];

        return Script::where('name', $script_name)
            ->where('system', 1)->value('id');
    }

    /**
     * Создание метода 'Проверка счетчика' и задачи планировщика 'Проверка счетчика' (каждый час)
     *
     * @param int $object_id
     */
    public function createCheckMethodWithEvent(int $object_id)
    {
        $script_id = $this->getScriptIdForCheckMethod();
        $method_id = Method::forceCreate([
            'name' => 'Проверка счетчика',
            'id_object' => $object_id,
            'script' => $script_id,
            'comment' => 'Периодическая проверка текущих значений счетчика',
            'is_system' => 1
        ])->id;

        $scheduler_task_id = SchedulerTask::forceCreate([
            'name' => 'Проверка счетчика',
            'is_system' => 1,
            'is_hidden' => 1,
            'object' => $object_id,
            'method' => $method_id
        ])->id;

        // каждый час
        SchedulerPoint::forceCreate([
            'id_task' => $scheduler_task_id,
            'type' => 'c',
            'time' => '60',
            'days' => '',
            'close' => 1,
            'system' => 1
        ]);
    }

    /**
     * Создание метода 'Обнуление счетчика' и задачи планировщика 'Обнуление счетчика' (каждый день в 23:55)
     *
     * @param int $object_id
     */
    public function createResetMethodWithEvent(int $object_id)
    {
        $script_id = $this->getScriptIdForResetMethod();

        $method_id = Method::forceCreate([
            'name' => 'Обнуление счетчика',
            'id_object' => $object_id,
            'script' => $script_id,
            'comment' => 'Обнуление значений счетчика за текущий день',
            'is_system' => 1
        ])->id;

        $scheduler_task_id = SchedulerTask::forceCreate([
            'name' => 'Обнуление счетчика',
            'is_system' => 1,
            'is_hidden' => 1,
            'object' => $object_id,
            'method' => $method_id
        ])->id;

        // каждый день в 23:55
        SchedulerPoint::forceCreate([
            'id_task' => $scheduler_task_id,
            'type' => 'w',
            'time' => '23:55',
            'days' => '0,1,2,3,4,5,6',
            'close' => 1,
            'system' => 1
        ]);
    }

    /**
     * Автосоздание методов и их событий для объекта, который был
     * создан автоматически для счетчика
     *
     * @param int $object_id
     * @return void
     */
    public function createCountObjectMethodsWithEvents(int $object_id)
    {
        $this->createCheckMethodWithEvent($object_id);
        $this->createResetMethodWithEvent($object_id);
    }
}
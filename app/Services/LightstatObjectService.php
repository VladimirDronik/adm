<?php

namespace App\Services;

use App\Models\HomeObject;
use App\Models\Method;
use App\Models\ObjType;
use App\Models\SchedulerPoint;
use App\Models\SchedulerTask;
use App\Models\Script;
use ScriptsTableSeeder;

class LightstatObjectService {

    /**
     * Автосоздание объекта для термостата
     *
     * @param string $name
     * @return HomeObject
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
        $script_id = Script::where('link', 'check_lightstat.php')
            ->where('system', 1)->value('id');

        if ($script_id) {
            return $script_id;
        }

        return Script::forceCreate(ScriptsTableSeeder::getCheckLightstatScript())->id;
    }

    /**
     * Создание метода 'Проверка термостата' и события 'Проверка термостата' (каждые 5 мин)
     *
     * @param int $object_id
     */
    public function createCheckMethodWithEvent(int $object_id)
    {
        $script_id = $this->getOrCreateCheckLightstatScriptId();

        $method_id = Method::forceCreate([
            'name' => 'Проверка светостата',
            'id_object' => $object_id,
            'comment' => 'Периодическая проверка текущих значений светостата',
            'is_system' => 1,
            'script' => $script_id
        ])->id;

        $scheduler_task_id = SchedulerTask::forceCreate([
            'name' => 'Проверка светостата',
            'is_system' => 1,
            'is_hidden' => 1,
            'object' => $object_id,
            'method' => $method_id
        ])->id;

        // каждые 5 мин
        SchedulerPoint::forceCreate([
            'id_task' => $scheduler_task_id,
            'type' => 'c',
            'time' => '5',
            'days' => '',
            'close' => 1,
            'system' => 1
        ]);
    }

    /**
     * Автосоздание методов и их событий для объекта, который был
     * создан автоматически для термостата
     *
     * @param int $object_id
     * @return void
     */
    public function createLightstatObjectMethodsWithEvents(int $object_id)
    {
        $this->createCheckMethodWithEvent($object_id);
    }
}
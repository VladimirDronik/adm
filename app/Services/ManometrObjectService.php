<?php

namespace App\Services;

use App\Models\HomeObject;
use App\Models\Method;
use App\Models\ObjType;
use App\Models\SchedulerPoint;
use App\Models\SchedulerTask;
use App\Models\Script;
use ScriptsTableSeeder;

class ManometrObjectService {

    /**
     * Автосоздание объекта для манометра
     *
     * @param string $name
     * @return HomeObject
     */
    public function createManometrObject(string $name): HomeObject
    {
        $object = new HomeObject();

        $object->type = ObjType::TYPE_MANOMETR;
        $object->name = $name;
        $object->status = '';
        $object->is_system = 1;

        $object->save();

        return $object;
    }

    public function getOrCreateCheckScriptId(): int
    {
        $script_id = Script::where('link', 'check_manometr.php')
            ->where('system', 1)->value('id');

        if ($script_id) {
            return $script_id;
        }

        return Script::forceCreate(ScriptsTableSeeder::getCheckManometrScript())->id;
    }

    /**
     * Создание метода 'Проверка манометра' и задачи 'Проверка манометра' (каждую 1 мин)
     *
     * @param int $object_id
     */
    public function createCheckMethodWithEvent(int $object_id)
    {
        $script_id = $this->getOrCreateChecKScriptId();

        $method_id = Method::forceCreate([
            'name' => 'Проверка манометра',
            'id_object' => $object_id,
            'comment' => 'Периодическая проверка текущих значений манометра',
            'is_system' => 1,
            'script' => $script_id
        ])->id;

        $scheduler_task_id = SchedulerTask::forceCreate([
            'name' => 'Проверка манометра',
            'is_system' => 1,
            'is_hidden' => 1,
            'object' => $object_id,
            'method' => $method_id
        ])->id;

        // каждые 1 мин
        SchedulerPoint::forceCreate([
            'id_task' => $scheduler_task_id,
            'type' => 'c',
            'time' => '1',
            'days' => '',
            'close' => 1,
            'system' => 1
        ]);
    }

    /**
     * Автосоздание методов и их событий для объекта, который был
     * создан автоматически
     *
     * @param int $object_id
     * @return void
     */
    public function createManometrObjectMethodsWithEvents(int $object_id)
    {
        $this->createCheckMethodWithEvent($object_id);
    }
}
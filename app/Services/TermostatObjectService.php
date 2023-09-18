<?php

namespace App\Services;

use App\Models\HomeObject;
use App\Models\Method;
use App\Models\ObjType;
use App\Models\SchedulerPoint;
use App\Models\SchedulerTask;
use App\Models\Script;
use Database\Seeders\ScriptsTableSeeder;

class TermostatObjectService
{
    /**
     * Автосоздание объекта для термостата
     */
    public function createTermostatObject(string $name): HomeObject
    {
        $object = new HomeObject();

        $object->type = ObjType::TYPE_TERMOSTAT;
        $object->name = $name;
        $object->status = 'off';
        $object->is_system = 1;

        $object->save();

        return $object;
    }

    public function getOrCreateCheckTermostatScriptId(): int
    {
        $script_id = Script::where('link', 'check_termostat.php')
            ->where('system', 1)->value('id');

        if ($script_id) {
            return $script_id;
        }

        return Script::forceCreate(ScriptsTableSeeder::getCheckTermostatScript())->id;
    }

    /**
     * Создание метода 'Проверка термостата' и элемента планировщика 'Проверка термостата' (каждые 5 мин)
     */
    public function createCheckMethodWithEvent(int $object_id)
    {
        $script_id = $this->getOrCreateCheckTermostatScriptId();

        $method_id = Method::forceCreate([
            'name' => 'Проверка термостата',
            'id_object' => $object_id,
            'comment' => 'Периодическая проверка текущих значений термостата',
            'is_system' => 1,
            'script' => $script_id,
        ])->id;

        $scheduler_task_id = SchedulerTask::forceCreate([
            'name' => 'Проверка термостата',
            'is_system' => 1,
            'is_hidden' => 1,
            'object' => $object_id,
            'method' => $method_id,
        ])->id;

        // каждые 5 мин
        SchedulerPoint::forceCreate([
            'id_task' => $scheduler_task_id,
            'type' => 'c',
            'time' => '5',
            'days' => '',
            'close' => 1,
            'system' => 1,
        ]);
    }

    /**
     * Автосоздание методов и их событий для объекта, который был
     * создан автоматически для термостата
     *
     * @return void
     */
    public function createTermostatObjectMethodsWithEvents(int $object_id)
    {
        $this->createCheckMethodWithEvent($object_id);
    }
}

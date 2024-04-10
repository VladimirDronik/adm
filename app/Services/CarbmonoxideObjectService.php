<?php

namespace App\Services;

use App\Models\HomeObject;
use App\Models\Method;
use App\Models\ObjType;
use App\Models\SchedulerPoint;
use App\Models\SchedulerTask;
use App\Models\Script;
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
        $script_id = Script::where('link', 'check_carbmonoxide.php')
            ->where('system', 1)
            ->value('id');

        if ($script_id) {
            return $script_id;
        }

        return Script::forceCreate(
            ScriptsTableSeeder::getCheckCarbmonoxideScript()
        )->id;
    }

    /**
     * Создание метода 'Проверка датчика УГ' и элемента планировщика 'Проверка датчика УГ' (каждые 5 мин)
     */
    public function createCheckMethodWithEvent(int $object_id)
    {
        $script_id = $this->getOrCreateChecScriptId();

        $method_id = Method::forceCreate([
            'name' => 'Проверка датчика УГ',
            'id_object' => $object_id,
            'comment' => 'Периодическая проверка текущих значений датчика УГ',
            'is_system' => 1,
            'script' => $script_id,
        ])->id;

        $scheduler_task_id = SchedulerTask::forceCreate([
            'name' => 'Проверка датчика УГ',
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
     * создан автоматически
     *
     * @return void
     */
    public function createCarbmonoxideObjectMethodsWithEvents(int $object_id)
    {
        $this->createCheckMethodWithEvent($object_id);
    }
}

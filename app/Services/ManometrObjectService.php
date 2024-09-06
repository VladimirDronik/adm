<?php

namespace App\Services;

use App\Models\Method;
use App\Models\Script;
use App\Models\ObjType;
use App\Models\HomeObject;
use App\Models\SchedulerTask;
use App\Models\SchedulerPoint;
use Database\Seeders\ScriptsTableSeeder;

class ManometrObjectService
{
    /**
     * Автосоздание объекта для манометра
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
        $scriptId = Script::where('link', 'check_manometr.php')
            ->where('system', 1)
            ->value('id');

        if ($scriptId) {
            return $scriptId;
        }

        return Script::forceCreate(
            ScriptsTableSeeder::getCheckManometrScript()
        )->id;
    }

    /**
     * Создание метода 'Проверка манометра' и задачи 'Проверка манометра' (каждую 1 мин)
     */
    public function createCheckMethodWithEvent(int $objectId)
    {
        $scriptId = $this->getOrCreateChecKScriptId();

        $methodId = Method::forceCreate([
            'name' => 'Проверка манометра',
            'id_object' => $objectId,
            'comment' => 'Периодическая проверка текущих значений манометра',
            'is_system' => 1,
            'script' => $scriptId,
        ])->id;

        $schedulerTaskId = SchedulerTask::forceCreate([
            'name' => 'Проверка манометра',
            'is_system' => 1,
            'is_hidden' => 1,
            'object' => $objectId,
            'method' => $methodId,
        ])->id;

        // каждые 1 мин
        SchedulerPoint::forceCreate([
            'id_task' => $schedulerTaskId,
            'type' => 'c',
            'time' => '1',
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
    public function createManometrObjectMethodsWithEvents(int $objectId)
    {
        $this->createCheckMethodWithEvent($objectId);
    }
}

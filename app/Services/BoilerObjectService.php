<?php
/**
 * Created by PhpStorm.
 * User: kinord
 * Date: 13.04.21
 * Time: 14:58
 */

namespace App\Services;

use App\Models\HomeObject;
use App\Models\Method;
use App\Models\ObjType;
use App\Models\SchedulerPoint;
use App\Models\SchedulerTask;
use App\Models\Script;
use Database\Seeders\ScriptsTableSeeder;

class BoilerObjectService
{
    /**
     * Автосоздание объекта для котла
     */
    public function createBoilerObject(string $name): HomeObject
    {
        $object = new HomeObject();

        $object->type = ObjType::TYPE_BOILER;
        $object->name = $name;
        $object->status = '1';
        $object->is_system = 1;

        $object->save();

        return $object;
    }

    /**
     * Автосоздание объекта для бойлера ГВС
     */
    public function createBoilerGVSObject(string $name): HomeObject
    {
        $object = new HomeObject();

        $object->type = ObjType::TYPE_BOILER_GVS;
        $object->name = $name;
        $object->status = '1';
        $object->is_system = 1;

        $object->save();

        return $object;
    }

    public function getOrCreateCheckBoilerScriptId(): int
    {
        $script_id = Script::where('link', 'check_boiler.php')
            ->where('system', 1)->value('id');

        if ($script_id) {
            return $script_id;
        }

        return Script::forceCreate(ScriptsTableSeeder::getCheckBoilerScript())->id;
    }

    /**
     * Создание метода 'Проверка термостата' и элемента планировщика 'Проверка котла' (каждую 1 мин)
     */
    public function createCheckMethodWithEvent(int $object_id)
    {
        $script_id = $this->getOrCreateCheckBoilerScriptId();

        $method_id = Method::forceCreate([
            'name' => 'Проверка котла отопления',
            'id_object' => $object_id,
            'comment' => 'Периодическая проверка текущих значений котла отопления',
            'is_system' => 1,
            'script' => $script_id,
        ])->id;

        $scheduler_task_id = SchedulerTask::forceCreate([
            'name' => 'Проверка котла отопления',
            'is_system' => 1,
            'is_hidden' => 1,
            'object' => $object_id,
            'method' => $method_id,
        ])->id;

        // каждую 1 мин
        SchedulerPoint::forceCreate([
            'id_task' => $scheduler_task_id,
            'type' => 'c',
            'time' => '1',
            'days' => '',
            'close' => 1,
            'system' => 1,
        ]);
    }

    /**
     * Автосоздание методов и их событий для объекта, который был
     * создан автоматически для котла
     *
     * @return void
     */
    public function createBoilerObjectMethodsWithEvents(int $object_id)
    {
        $this->createCheckMethodWithEvent($object_id);
    }
}

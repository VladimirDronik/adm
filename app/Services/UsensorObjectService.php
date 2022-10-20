<?php

namespace App\Services;

use App\Models\HomeObject;
use App\Models\Method;
use App\Models\ObjType;
use App\Models\Script;
use ScriptsTableSeeder;

class UsensorObjectService {

    /**
     * Автосоздание объекта для термостата
     *
     * @param string $name
     * @return HomeObject
     */
    public function createUsensorObject(string $name): HomeObject
    {
        $object = new HomeObject();

        $object->type = ObjType::TYPE_USENSOR;
        $object->name = $name;
        $object->status = 'off';
        $object->is_system = 1;

        $object->save();

        return $object;
    }

    public function getOrCreateCheckUsensorScriptId(): int
    {
        $script_id = Script::where('link', 'check_usensor.php')
            ->where('system', 1)->value('id');

        if ($script_id) {
            return $script_id;
        }

        return Script::forceCreate(ScriptsTableSeeder::getCheckUsensorScript())->id;
    }

    /**
     * Создание метода 'Проверка универсального датчика'
     *
     * @param int $object_id
     */
    public function createCheckMethodWithEvent(int $object_id)
    {
        $script_id = $this->getOrCreateCheckUsensorScriptId();

        $method_id = Method::forceCreate([
            'name' => 'Проверка универсального датчика',
            'id_object' => $object_id,
            'comment' => 'Периодическая проверка текущих значений универсального дачика',
            'is_system' => 1,
            'script' => $script_id
        ])->id;

    }

    /**
     * Автосоздание методов и их событий для объекта, который был
     * создан автоматически для универсального датчика
     *
     * @param int $object_id
     * @return void
     */
    public function createUsensorObjectMethodsWithEvents(int $object_id)
    {
        $this->createCheckMethodWithEvent($object_id);
    }
}
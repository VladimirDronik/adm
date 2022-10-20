<?php

namespace App\Services;

use App\Models\HomeObject;
use App\Models\Method;
use App\Models\ObjType;
use App\Models\Script;
use ScriptsTableSeeder;

class DimmerObjectService {

    /**
     * Автосоздание объекта для диммера
     *
     * @param string $name
     * @return HomeObject
     */
    public function createDimmerObject(string $name): HomeObject
    {
        $object = new HomeObject();

        $object->type = ObjType::TYPE_DIMMER;
        $object->name = $name;
        $object->status = 'off';
        $object->is_system = 1;

        $object->save();

        return $object;
    }

    /**
     * Если скрипт не найден, то создаем
     *
     * @return int
     */
    private function getScriptId(array $scriptArray): int
    {
        $script = Script::where('name', $scriptArray['name'])
            ->where('system', 1)->first();

        if (!$script) {
            $script = Script::forceCreate($scriptArray);
        }

        return $script->id;
    }

    /**
     * Автосоздание методов для объекта, который был
     * создан автоматически для диммера
     *
     * @param int $object_id
     * @return void
     */
    public function createDimmerObjectMethods(int $object_id)
    {
        $scripts = ScriptsTableSeeder::getDimmerScripts();

        $methods = [];

        foreach ($scripts as $script) {
            $script_id = $this->getScriptId($script);
            $methods[] = [
                'name' => $script['name'],
                'id_object' => $object_id,
                'script' => $script_id,
                'comment' => $script['name'],
                'params' => mb_strpos($script['name'], 'Установить', 0, 'UTF-8') !== false ? 'Яркость (целое, 0-100)' : null,
                'is_system' => 1
            ];
        }

        Method::insert($methods);
    }
}
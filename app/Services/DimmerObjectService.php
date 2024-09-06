<?php

namespace App\Services;

use App\Models\Method;
use App\Models\Script;
use App\Models\ObjType;
use App\Models\HomeObject;
use Database\Seeders\ScriptsTableSeeder;

class DimmerObjectService
{
    /**
     * Автосоздание объекта для диммера
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
     */
    private function getScriptId(array $scriptArray): int
    {
        $script = Script::where('name', $scriptArray['name'])
            ->where('system', 1)
            ->first();

        if (! $script) {
            $script = Script::forceCreate($scriptArray);
        }

        return $script->id;
    }

    /**
     * Автосоздание методов для объекта, который был
     * создан автоматически для диммера
     *
     * @return void
     */
    public function createDimmerObjectMethods(int $objectId)
    {
        $scripts = ScriptsTableSeeder::getDimmerScripts();

        $methods = [];

        foreach ($scripts as $script) {
            $scriptId = $this->getScriptId($script);
            $methods[] = [
                'name' => $script['name'],
                'id_object' => $objectId,
                'script' => $scriptId,
                'comment' => $script['name'],
                'params' => mb_strpos($script['name'], 'Установить', 0, 'UTF-8') !== false ? 'Яркость (целое, 0-100)' : null,
                'is_system' => 1,
            ];
        }

        Method::insert($methods);
    }
}

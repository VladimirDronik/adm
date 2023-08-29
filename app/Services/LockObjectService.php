<?php

namespace App\Services;


use App\Models\HomeObject;
use App\Models\Method;
use Database\Seeders\ScriptsTableSeeder;
use App\Models\Script;

class LockObjectService {

    /**
     * Автосоздание объекта для реле
     *
     * @param string $name
     * @return HomeObject
     */
    public function createLockObject(string $name): HomeObject
    {
        $object = new HomeObject();

        $object->type = 'lock';
        $object->name = $name;
        $object->status = 'open';
        $object->is_system = 1;

        $object->save();

        return $object;
    }


    /**
     * Автосоздание методов для шторы
     *
     * @param int $object_id
     * @return void
     */
    public function createLockObjectMethods(int $object_id)
    {
        $scripts = ScriptsTableSeeder::getLockScripts();

        $methods = [];

        foreach ($scripts as $script) {
            $script_id = $this->getScriptId($script);
            $methods[] = [
                'name' => $script['name'],
                'id_object' => $object_id,
                'script' => $script_id,
                'comment' => $script['name'],
                'params' => null,
                'is_system' => 1
            ];
        }

        Method::insert($methods);
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


}
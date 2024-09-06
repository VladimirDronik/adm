<?php

namespace App\Services;

use App\Models\Method;
use App\Models\Script;
use App\Models\HomeObject;
use Database\Seeders\ScriptsTableSeeder;

class LockObjectService
{
    /**
     * Автосоздание объекта для реле
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
     * @return void
     */
    public function createLockObjectMethods(int $objectId)
    {
        $scripts = ScriptsTableSeeder::getLockScripts();

        $methods = [];

        foreach ($scripts as $script) {
            $scriptId = $this->getScriptId($script);
            $methods[] = [
                'name' => $script['name'],
                'id_object' => $objectId,
                'script' => $scriptId,
                'comment' => $script['name'],
                'params' => null,
                'is_system' => 1,
            ];
        }

        Method::insert($methods);
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
}

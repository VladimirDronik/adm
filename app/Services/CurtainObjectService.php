<?php

namespace App\Services;


use App\Models\HomeObject;
use App\Models\Method;
use Database\Seeders\ScriptsTableSeeder;
use App\Models\Script;

class CurtainObjectService {

    /**
     * Автосоздание объекта для реле
     *
     * @param string $name
     * @return HomeObject
     */
    public function createCurtainObject(string $name): HomeObject
    {
        $object = new HomeObject();

        $object->type = 'curtain';
        $object->name = $name;
        $object->status = 'close';
        $object->is_system = 1;

        $object->save();

        return $object;
    }


    /**
     * Автосоздание методов для шторы
     *
     * @param int $object_id
     * @param bool $percentOpenScript = false
     * @return void
     */
    public function createCurtainObjectMethods(int $object_id, bool $percentOpenScript = false)
    {
        $scripts = ScriptsTableSeeder::getCurtainScripts();

        $methods = [];

        foreach ($scripts as $script) {
            $script_id = $this->getScriptId($script);
            if ($script['name'] == 'Открыть штору на %') {
                if ($percentOpenScript) {
                    $methods[] = [
                        'name' => $script['name'],
                        'id_object' => $object_id,
                        'script' => $script_id,
                        'comment' => $script['name'],
                        'params' => mb_strpos($script['name'], '%', 2, 'UTF-8') !== false ? '% открытия (целое, 0-100)' : null,
                        'is_system' => 1
                    ];
                } else {
                    continue;
                }
            } else {
                $methods[] = [
                    'name' => $script['name'],
                    'id_object' => $object_id,
                    'script' => $script_id,
                    'comment' => $script['name'],
                    'params' => mb_strpos($script['name'], '%', 2, 'UTF-8') !== false ? '% открытия (целое, 0-100)' : null,
                    'is_system' => 1
                ];
            }
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
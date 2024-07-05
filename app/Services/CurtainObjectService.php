<?php

namespace App\Services;

use App\Models\Method;
use App\Models\Script;
use App\Models\Curtain;
use App\Models\HomeObject;
use Database\Seeders\ScriptsTableSeeder;

class CurtainObjectService
{
    /**
     * Автосоздание объекта для реле
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
     * @param string $place
     * @return void
     */
    public function createCurtainObjectMethods(int $object_id, string $place)
    {
        $scripts = ScriptsTableSeeder::getCurtainScripts();
        $methods = [];

        foreach ($scripts as $script) {
            $script_id = $this->getScriptId($script);
            if ($script['name'] == 'Открыть штору на %') {
                if ($place == Curtain::PLACE_RS485) {
                    $methods[] = [
                        'name' => $script['name'],
                        'id_object' => $object_id,
                        'script' => $script_id,
                        'comment' => $script['name'],
                        'params' => '% открытия (целое, 0-100)',
                        'is_system' => 1,
                    ];
                } else {
                    continue;
                }
            } elseif ($script['name'] == 'Сменить направление вращения') {
                if ($place == Curtain::PLACE_RS485) {
                    $methods[] = [
                        'name' => $script['name'],
                        'id_object' => $object_id,
                        'script' => $script_id,
                        'comment' => $script['name'],
                        'params' => null,
                        'is_system' => 1,
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
                    'params' => null,
                    'is_system' => 1,
                ];
            }
        }

        Method::insert($methods);
    }

    /**
     * Если скрипт не найден, то создаем
     */
    private function getScriptId(array $scriptArray): int
    {
        $script = Script::where('name', $scriptArray['name'])
            ->where('system', 1)->first();

        if (! $script) {
            $script = Script::forceCreate($scriptArray);
        }

        return $script->id;
    }
}

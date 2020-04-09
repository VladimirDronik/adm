<?php
/**
 * Created by PhpStorm.
 * User: kinord
 * Date: 09.04.20
 * Time: 9:23
 */

namespace App\Services;

use App\Models\HomeObject;
use App\Models\ObjType;
use ScriptsTableSeeder;
use App\Models\Method;
use App\Models\Script;

class DryContactObjectService
{


    /**
     * Автосоздание объекта для сухого контакта
     *
     * @param string $name
     * @return HomeObject
     */
    public function createDrycontactObject(string $name): HomeObject
    {
        $object = new HomeObject();

        $object->type = ObjType::TYPE_DRYCONTACT;
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
        //dd($scriptArray);

        $script = Script::where('name', $scriptArray['name'])
            ->where('system', 1)->first();

        if (!$script) {
            $script = Script::forceCreate($scriptArray);
        }

        return $script->id;
    }

    /**
     * Автосоздание методов для объекта, который был
     * создан автоматически
     *
     * @param int $object_id
     * @return void
     */
    public function createDryContactObjectMethods(int $object_id)
    {
        $script = ScriptsTableSeeder::getDrycontactsScript();

        $methods = [];

            $script_id = $this->getScriptId($script);

            $methods[] = [
                'name' => $script['name'],
                'id_object' => $object_id,
                'script' => $script_id,
                'comment' => $script['name'],
                'params' => null,
                'is_system' => 1
            ];

        Method::insert($methods);
    }
}
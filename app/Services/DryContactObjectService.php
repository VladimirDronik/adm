<?php
/**
 * Created by PhpStorm.
 * User: kinord
 * Date: 09.04.20
 * Time: 9:23
 */

namespace App\Services;

use App\Models\HomeObject;
use App\Models\Method;
use App\Models\ObjType;
use App\Models\Script;
use Database\Seeders\ScriptsTableSeeder;

class DryContactObjectService
{
    /**
     * Автосоздание объекта для сухого контакта
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
     * создан автоматически
     *
     * @return void
     */
    public function createDryContactObjectMethods(int $object_id)
    {
        $script = ScriptsTableSeeder::getDrycontactsScript();

        $script_id = $this->getScriptId($script);

        $method = new Method();

        $method->name = $script['name'];
        $method->id_object = $object_id;
        $method->script = $script_id;
        $method->comment = $script['name'];
        $method->params = null;
        $method->is_system = 1;

        $method->save();

        return $method->id;
    }
}

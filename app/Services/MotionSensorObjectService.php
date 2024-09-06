<?php

namespace App\Services;

use App\Models\Method;
use App\Models\Script;
use App\Models\ObjType;
use App\Models\HomeObject;
use Database\Seeders\ScriptsTableSeeder;

class MotionSensorObjectService
{
    /**
     * Автосоздание объекта для сухого контакта
     */
    public function createMotionsensorObject(string $name): HomeObject
    {
        $object = new HomeObject();

        $object->type = ObjType::TYPE_MOTIONSENSOR;
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
    public function createMotionsensorObjectMethods(int $objectId)
    {
        $script = ScriptsTableSeeder::getMotionsensorScript();

        $scriptId = $this->getScriptId($script);

        $method = new Method();

        $method->name = $script['name'];
        $method->id_object = $objectId;
        $method->script = $scriptId;
        $method->comment = $script['name'];
        $method->params = null;
        $method->is_system = 1;

        $method->save();

        return $method->id;
    }
}

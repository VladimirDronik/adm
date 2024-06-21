<?php

namespace App\Services;

use App\Models\Method;
use App\Models\Script;
use App\Models\Curtain;
use App\Models\HomeObject;
use App\Models\SchedulerTask;
use App\Models\SchedulerPoint;
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
     * Создание метода 'Опрос привода штор' и элемента планировщика 'Опрос привода штор' (каждую 1 мин)
     */
    public function createCheckMethodWithEvent(int $objectId): void
    {
        $script = Script::where('link', 'curtain_polling.php')
            ->where('system', 1)
            ->first();

        if (!$script) {
            $script = Script::create(ScriptsTableSeeder::getCheckCurtainScript());
        }

        $method = Method::create([
            'name' => 'Опрос привода штор',
            'id_object' => $objectId,
            'comment' => 'Периодический опрос привода штор',
            'is_system' => 1,
            'script' => $script->id,
        ]);

        $schedulerTask = SchedulerTask::create([
            'name' => 'Опрос привода штор',
            'is_system' => 1,
            'is_hidden' => 1,
            'object' => $objectId,
            'method' => $method->id,
        ]);

        SchedulerPoint::create([
            'id_task' => $schedulerTask->id,
            'type' => 'c',
            'time' => '1',
            'days' => '',
            'close' => 1,
            'system' => 1,
        ]);
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

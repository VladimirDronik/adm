<?php
/**
 * Created by PhpStorm.
 * User: kinord
 * Date: 11.07.20
 * Time: 10:46
 */

namespace App\Services;

use App\Models\HomeObject;
use App\Models\Lamp;
use App\Models\Method;
use App\Models\Script;

class LampObjectService
{
    /**
     * Автосоздание объекта для лампы
     */
    public function createLampObject(string $name, string $type): HomeObject
    {
        $object = new HomeObject();

        $object->type = Lamp::TYPE_LAMP;
        $object->name = $name;
        $object->status = 'off';
        $object->is_system = 1;

        $object->save();

        return $object;
    }

    /**
     * Создание метода 'Выключить лампу'
     */
    private function createMethodOff(int $object_id, $device_id, $port_id)
    {
        if ($device_id && ! is_null($port_id)) {
            $easyString = $device_id.';'.$port_id.':0';
        } else {
            $easyString = null;
        }

        //Обнуляем все простые действия, которые были назначены для этого порта
        Method::where('easy', $easyString)->update(['easy' => null]);

        Method::forceCreate([
            'name' => 'Выключить лампу',
            'alias' => 'lamp_off',
            'id_object' => $object_id,
            'script' => null,
            'easy' => $easyString,
            'comment' => 'Выключить лампу',
            'is_system' => 1,
        ]);
    }

    /**
     * Создание метода 'Включить лампу'
     */
    private function createMethodOn(int $object_id, $device_id, $port_id)
    {
        if ($device_id && ! is_null($port_id)) {
            $easyString = $device_id.';'.$port_id.':1';
        } else {
            $easyString = null;
        }

        //Обнуляем все простые действия, которые были назначены для этого порта
        Method::where('easy', $easyString)->update(['easy' => null]);

        Method::forceCreate([
            'name' => 'Включить лампу',
            'alias' => 'lamp_on',
            'id_object' => $object_id,
            'script' => null,
            'easy' => $easyString,
            'comment' => 'Включить лампу',
            'is_system' => 1,
        ]);
    }

    /**
     * Создание метода 'Смена состояния лампы'
     */
    private function createMethodOnOff(int $object_id, $device_id, $port_id)
    {
        if ($device_id && ! is_null($port_id)) {
            $easyString = $device_id.';'.$port_id.':2';
        } else {
            $easyString = null;
        }

        //Обнуляем все простые действия, которые были назначены для этого порта
        Method::where('easy', $easyString)->update(['easy' => null]);

        Method::forceCreate([
            'name' => 'Смена состояния лампы',
            'alias' => 'lamp_switch',
            'id_object' => $object_id,
            'script' => null,
            'easy' => $easyString,
            'comment' => 'Смена состояния лампы',
            'is_system' => 1,
        ]);
    }

    private function updateMethodOff(int $object_id, $device_id, $port_id)
    {
        if ($device_id && ! is_null($port_id)) {
            $easyString = $device_id.';'.$port_id.':0';
        } else {
            $easyString = null;
        }

        //Обнуляем все простые действия, которые были назначены для этого порта
        Method::where('easy', $easyString)->update(['easy' => null]);

        Method::where('id_object', $object_id)
            ->where('name', 'Выключить лампу')
            ->where('alias', 'lamp_off')
            ->update(['easy' => $easyString]);
    }

    private function updateMethodOn(int $object_id, $device_id, $port_id)
    {
        if ($device_id && ! is_null($port_id)) {
            $easyString = $device_id.';'.$port_id.':1';
        } else {
            $easyString = null;
        }

        //Обнуляем все простые действия, которые были назначены для этого порта
        Method::where('easy', $easyString)->update(['easy' => null]);

        Method::where('id_object', $object_id)
            ->where('name', 'Включить лампу')
            ->where('alias', 'lamp_on')
            ->update(['easy' => $easyString]);
    }

    private function updateMethodOnOff(int $object_id, $device_id, $port_id)
    {
        if ($device_id && ! is_null($port_id)) {
            $easyString = $device_id.';'.$port_id.':2';
        } else {
            $easyString = null;
        }

        //Обнуляем все простые действия, которые были назначены для этого порта
        Method::where('easy', $easyString)->update(['easy' => null]);

        Method::where('id_object', $object_id)
            ->where('name', 'Смена состояния лампы')
            ->where('alias', 'lamp_switch')
            ->update(['easy' => $easyString]);
    }

    /**
     * Автосоздание методов для объекта, который был
     * создан автоматически для лампы
     *
     * @param  int  $device_id
     * @param  int  $port_id
     * @return void
     */
    public function createLampObjectMethods(int $object_id, $device_id, $port_id)
    {
        $this->createMethodOn($object_id, $device_id, $port_id);
        $this->createMethodOff($object_id, $device_id, $port_id);
        $this->createMethodOnOff($object_id, $device_id, $port_id);
        $this->createLampDimmerMethods($object_id);
    }

    public function updateLampObjectMethods(int $object_id, $device_id, $port_id)
    {
        $this->updateMethodOff($object_id, $device_id, $port_id);
        $this->updateMethodOn($object_id, $device_id, $port_id);
        $this->updateMethodOnOff($object_id, $device_id, $port_id);
    }

    /**
     * Создание методов для лампы, которая диммируется
     *
     * @return void
     */
    private function createLampDimmerMethods(int $object_id)
    {
        $dataArrays = $this->getLampDimmerData();
        $methods = [];

        foreach ($dataArrays as $data) {
            $methods[] = [
                'name' => $data['method']['name'],
                'alias' => $data['method']['alias'],
                'id_object' => $object_id,
                'script' => $this->getScriptId($data['script']),
                'comment' => $data['method']['name'],
                'params' => mb_strpos($data['method']['name'], 'Установить', 0, 'UTF-8') !== false ? 'Яркость (целое, 0-100)' : null,
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
            $script = Script::create($scriptArray);
        }

        return $script->id;
    }

    /**
     * Данные скриптов и методов для ламп, которые диммируются
     */
    private function getLampDimmerData(): array
    {
        return [
            [
                'script' => [
                    'name' => 'Включить диммер',
                    'link' => 'on_dimmer.php',
                    'count' => 0,
                    'system' => 1,
                ],
                'method' => [
                    'name' => 'Включить лампу',
                    'alias' => 'dimmer_on',
                ],
            ],
            [
                'script' => [
                    'name' => 'Выключить диммер',
                    'link' => 'off_dimmer.php',
                    'count' => 0,
                    'system' => 1,
                ],
                'method' => [
                    'name' => 'Выключить лампу',
                    'alias' => 'dimmer_off',
                ],
            ],
            [
                'script' => [
                    'name' => 'Увеличить яркость диммера',
                    'link' => 'up_dimmer.php',
                    'count' => 0,
                    'system' => 1,
                ],
                'method' => [
                    'name' => 'Увеличить яркость лампы',
                    'alias' => 'dimmer_up',
                ],
            ],
            [
                'script' => [
                    'name' => 'Уменьшить яркость диммера',
                    'link' => 'down_dimmer.php',
                    'count' => 0,
                    'system' => 1,
                ],
                'method' => [
                    'name' => 'Уменьшить яркость лампы',
                    'alias' => 'dimmer_down',
                ],
            ],
            [
                'script' => [
                    'name' => 'Установить яркость диммера',
                    'link' => 'set_dimmer.php',
                    'count' => 0,
                    'system' => 1,
                ],
                'method' => [
                    'name' => 'Установить яркость лампы',
                    'alias' => 'dimmer_set',
                ],
            ],
        ];
    }
}

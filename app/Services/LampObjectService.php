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
use App\Models\Port;
use App\Models\Script;

class LampObjectService
{
    /**
     * Автосоздание объекта для лампы
     */
    public function createLampObject(string $name): HomeObject
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
     * Автосоздание методов для лампы
     *
     * @return void
     */
    public function createLampObjectMethods(int $objectId, ?int $deviceId = null, ?Port $port = null, ?int $registerId = null)
    {
        $this->createLampMethods($objectId, $deviceId, $port, $registerId);
        $this->createLampDimmerMethods($objectId);
    }

    /**
     * Обновление методов лампы
     */
    public function updateAllLampMethods(int $objectId, ?int $deviceId = null, ?Port $port = null, ?int $registerId = null)
    {
        $easyString = null;

        if ($registerId) {
            $easyString = 'm;' . $registerId;
        } else {
            if ($port) {
                if ($port->type == 'ext') {
                    $easyString = $deviceId . ';' . $port->extensionModule->sda_port . 'e' . $port->num_port;
                } else {
                    $easyString = $deviceId . ';' . $port->num_port;
                }
            }

            //Обнуляем все простые действия, которые были назначены для этих портов
            Method::where('easy', $easyString . ':0')
                ->orWhere('easy', $easyString . ':1')
                ->orWhere('easy', $easyString . ':2')
                ->update(['easy' => null]);
        }

        $methods = [
            [
                'alias' => 'lamp_off',
                'easy' => $registerId ? $easyString : $easyString . ':0',
            ],
            [
                'alias' => 'lamp_on',
                'easy' => $registerId ? $easyString : $easyString . ':1',
            ],
            [
                'alias' => 'lamp_switch',
                'easy' => $registerId ? $easyString : $easyString . ':2',
            ],
        ];

        foreach ($methods as $method) {
            Method::where('id_object', $objectId)
                ->where('alias', $method['alias'])
                ->update(['easy' => $method['easy']]);
        }
    }

    /**
     * Обновление всех методов лампы, которая диммируется
     *
     * @return void
     */
    public function updateAllLampDimmerMethods(int $objectId, int $registerId)
    {
        $dataArrays = $this->getLampDimmerData();
        $easyString = 'm;' . $registerId;

        foreach ($dataArrays as $data) {
            Method::where('id_object', $objectId)
                ->where('alias', $data['method']['alias'])
                ->update(['easy' => $easyString]);
        }
    }

    /**
     * Обновление методов лампы выбранными регистрами
     *
     * @return void
     */
    public function updateLampMethodsWithCurrentRegisters(HomeObject $lampObject, array $data)
    {
        if ($lampObject->methods->isNotEmpty()) {
            foreach ($lampObject->methods as $method) {
                $method->update([
                    'easy' => array_key_exists('register_id_' . $method->id, $data) && $data['register_id_' . $method->id]
                        ? 'm;' . $data['register_id_' . $method->id]
                        : null,
                ]);
            }
        }
    }

    /**
     * Создание методов лампы
     */
    private function createLampMethods(int $objectId, ?int $deviceId = null, ?Port $port = null, ?int $registerId = null)
    {
        $easyString = null;

        if ($registerId) {
            $easyString = 'm;' . $registerId;
        } else {
            if ($port) {
                if ($port->type == 'ext') {
                    $easyString = $deviceId . ';' . $port->extensionModule->sda_port . 'e' . $port->num_port;
                } else {
                    $easyString = $deviceId . ';' . $port->num_port;
                }
            }

            //Обнуляем все простые действия, которые были назначены для этих портов
            Method::where('easy', $easyString . ':0')
                ->orWhere('easy', $easyString . ':1')
                ->orWhere('easy', $easyString . ':2')
                ->update(['easy' => null]);
        }

        $methods = [
            [
                'name' => 'Выключить лампу',
                'alias' => 'lamp_off',
                'id_object' => $objectId,
                'script' => null,
                'easy' => $registerId ? $easyString : $easyString . ':0',
                'comment' => 'Выключить лампу',
                'is_system' => 1,
            ],
            [
                'name' => 'Включить лампу',
                'alias' => 'lamp_on',
                'id_object' => $objectId,
                'script' => null,
                'easy' => $registerId ? $easyString : $easyString . ':1',
                'comment' => 'Включить лампу',
                'is_system' => 1,
            ],
            [
                'name' => 'Смена состояния лампы',
                'alias' => 'lamp_switch',
                'id_object' => $objectId,
                'script' => null,
                'easy' => $registerId ? $easyString : $easyString . ':2',
                'comment' => 'Смена состояния лампы',
                'is_system' => 1,
            ],
        ];

        foreach ($methods as $method) {
            Method::create($method);
        }
    }

    /**
     * Создание методов для лампы, которая диммируется
     *
     * @return void
     */
    private function createLampDimmerMethods(int $objectId)
    {
        $dataArrays = $this->getLampDimmerData();
        $methods = [];

        foreach ($dataArrays as $data) {
            $methods[] = [
                'name' => $data['method']['name'],
                'alias' => $data['method']['alias'],
                'id_object' => $objectId,
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

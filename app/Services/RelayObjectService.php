<?php

namespace App\Services;

use App\Models\HomeObject;
use App\Models\Method;
use App\Models\ObjType;

class RelayObjectService
{
    /**
     * Автосоздание объекта для реле
     */
    public function createRelayObject(string $name): HomeObject
    {
        $object = new HomeObject();

        $object->type = ObjType::TYPE_RELAY;
        $object->name = $name;
        $object->status = 'off';
        $object->is_system = 1;

        $object->save();

        return $object;
    }

    /**
     * Создание методов реле
     *
     * @return void
     */
    public function createRelayObjectMethods(int $objectId, ?int $deviceId = null, ?int $numPort = null, ?int $registerId = null)
    {
        $easyString = null;

        if ($registerId) {
            $easyString = 'm;' . $registerId;
        } else {
            $easyString = $deviceId.';'.$numPort;

            //Обнуляем все простые действия, которые были назначены для этих портов
            Method::where('easy', $easyString . ':0')
                ->orWhere('easy', $easyString . ':1')
                ->orWhere('easy', $easyString . ':2')
                ->update(['easy' => null]);
        }

        $methods = [
            [
                'name' => 'Выключить реле',
                'id_object' => $objectId,
                'script' => null,
                'easy' => $registerId ? $easyString : $easyString . ':0',
                'comment' => 'Выключить реле',
                'is_system' => 1,
            ],
            [
                'name' => 'Включить реле',
                'id_object' => $objectId,
                'script' => null,
                'easy' => $registerId ? $easyString : $easyString . ':1',
                'comment' => 'Включить реле',
                'is_system' => 1,
            ],
            [
                'name' => 'Переключить реле',
                'id_object' => $objectId,
                'script' => null,
                'easy' => $registerId ? $easyString : $easyString . ':2',
                'comment' => 'Переключить реле',
                'is_system' => 1,
            ],
        ];

        foreach ($methods as $method) {
            Method::create($method);
        }
    }

    /**
     * Обновление методов реле
     */
    public function updateRelayObjectMethods(int $objectId, ?int $deviceId = null, ?int $numPort = null, ?int $registerId = null)
    {
        $easyString = null;

        if ($registerId) {
            $easyString = 'm;' . $registerId;
        } else {
            $easyString = $deviceId.';'.$numPort;

            //Обнуляем все простые действия, которые были назначены для этих портов
            Method::where('easy', $easyString . ':0')
                ->orWhere('easy', $easyString . ':1')
                ->orWhere('easy', $easyString . ':2')
                ->update(['easy' => null]);
        }

        $methods = [
            [
                'name' => 'Выключить реле',
                'easy' => $registerId ? $easyString : $easyString . ':0',
            ],
            [
                'name' => 'Включить реле',
                'easy' => $registerId ? $easyString : $easyString . ':1',
            ],
            [
                'name' => 'Переключить реле',
                'easy' => $registerId ? $easyString : $easyString . ':2',
            ],
        ];

        foreach ($methods as $method) {
            Method::where('id_object', $objectId)
                ->where('name', $method['name'])
                ->update(['easy' => $method['easy']]);
        }
    }

    /**
     * Обновление методов реле выбранными регистрами
     *
     * @return void
     */
    public function updateRelayMethodsWithCurrentRegisters(HomeObject $relayObject, array $data)
    {
        if ($relayObject->methods->isNotEmpty()) {
            foreach ($relayObject->methods as $method) {
                $method->update([
                    'easy' => array_key_exists('register_id_' . $method->id, $data) && $data['register_id_' . $method->id]
                        ? 'm;' . $data['register_id_' . $method->id]
                        : null,
                ]);
            }
        }
    }
}

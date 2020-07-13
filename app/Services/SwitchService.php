<?php

namespace App\Services;

use App\Models\DeviceSwitch;
use App\Models\HomeObject;
use App\Models\Port;
use App\Models\Device;
use Illuminate\Support\Facades\DB;
use App\Services\DeviceService;

class SwitchService {

    private $switch_object_service;

    public function __construct(SwitchObjectService $switch_object_service)
    {
        $this->switch_object_service = $switch_object_service;

    }

    /**
     * Удаление выключателя. Если связанный объект системный, то удаление объекта,
     * созданного автоматически при создании выключателя
     *
     * @param int $id
     * @return bool
     * @throws \Throwable
     */
    public function delete(int $id): bool
    {
        $switch = DeviceSwitch::findOrFail($id);

        PortService::deleteAllMethodsForPort($switch->id_object);

        if ($switch->object && $switch->object->is_system) {
            DB::transaction(function () use (&$switch) {
                //if (!HomeObject::isObjectUsed($switch->id_object, $switch->id, 'switches')) {
                    HomeObject::deleteAutoObject($switch->id_object);
                //}
                $switch->delete();
            });
        } else {
            $switch->delete();
        }

        Port::where('object', $switch->id_object)->update([
            'object' => null,
            'method' => null, 'method_params' => null,
            'dc_method' => null, 'dc_method_params' => null,
            'lc_method' => null, 'lc_method_params' => null ]);


        return true;
    }

    public function prepareSwitch(DeviceSwitch $switch, array $data)
    {

        $switch->name = trim($data['name']);
        if (isset($data['type'])) {
            $switch->type = $data['type'];
        }
        $switch->id_object = (int)$data['id_object'];
    }

    /**
     * Настройка физического порта устройства
     * @param int $idPort - ИД порта, который будем изменять
     * @param string $typeObject - тип объекта switch или button
     * @return bool
     */
    private function setPort($idPort, $typeObject)
    {
        $answer = false;

        $port = Port::where('id', $idPort)->first();
        $device = Device::where('id', $port->id_device)->first();

        if (DeviceService::getStatus($port->id_device) === 1) {

            if ($typeObject == 'button')
                $answer = file_get_contents("http://{$device->ip_address}/sec/?pn={$port->num_port}&pty=0");
            else
                $answer = file_get_contents("http://{$device->ip_address}/sec/?pn={$port->num_port}&pty=1");

        } else throw new \Exception(': контроллер недоступен');

        return $answer;

    }

    /**
     * Создание выключателя. Если $data['type'] === 'auto',
     * то еще создается объект
     *
     * @param array $data
     * @return int
     * @throws \Throwable
     */
    public function store(array $data): int
    {
        $switch = new DeviceSwitch();
        $this->prepareSwitch($switch, $data);

        $result = false;
        $answer = true;

        if ($data['object_type'] === 'manual') {
            $switch->save();
        } else if ($data['object_type'] === 'auto') {
            DB::transaction(function () use (&$switch, $data) {
                $unique_name = HomeObject::getUniqueObjectName(0, $switch->name);
                $object = $this->switch_object_service->createSwitchObject($unique_name, $switch->type);
                $switch->id_object = $object->id;

                if ($data['port_id']) {

                    //$answer = $this->setPort($data['port_id'], $data['type']);

                    Port::where('id', $data['port_id'])->update(['object' => $object->id,
                        'method' => $data['method'], 'method_params' => $data['method_params'],
                        'dc_method' => $data['method_dc'], 'dc_method_params' => $data['method_dc_params'],
                        'lc_method' => $data['method_lc'], 'lc_method_params' => $data['method_lc_params'] ]);
                }


                $switch->save();
                $result = true;
                /*
                if ($answer === false) {
                    throw new \Exception('Некорректный ответ от удаленного сервера');
                } else {
                    $switch->save();
                    $result = true;
                }
                */

            });
        }


        return $result;
    }

    private function isUpdateAutoObjectName(DeviceSwitch $switch, string $name): bool
    {
        return $switch->name !== trim($name) && $switch->object && $switch->object->is_system;
    }

    /**
     * Обновление выключателя. Если изменилось название и у выключателя системный объект,
     * то изменяем название объекта.
     * При этом проверяем на уникальность название объекта. Если неуникально, то добавляем число.
     *
     * @param DeviceSwitch $switch
     * @param array $data
     * @return int
     * @throws \Throwable
     */
    public function update(DeviceSwitch $switch, array $data): int
    {
        DB::transaction(function () use (&$switch, $data) {
            if ($this->isUpdateAutoObjectName($switch, $data['name'])) {
                $switch->object->name = HomeObject::getUniqueObjectName($switch->object->id, trim($data['name']));
                $switch->object->save();
            }
            $this->prepareSwitch($switch, $data);
            $switch->save();
        });

        if ($data['port_id']) {

            Port::where('object', $switch->object->id)->update([
                'object' => null,
                'method' => null, 'method_params' => null,
                'dc_method' => null, 'dc_method_params' => null,
                'lc_method' => null, 'lc_method_params' => null ]);

            Port::where('id', $data['port_id'])->update(['object' => $data['id_object'],
                'method' => $data['method'], 'method_params' => $data['method_params'],
                'dc_method' => $data['method_dc'], 'dc_method_params' => $data['method_dc_params'],
                'lc_method' => $data['method_lc'], 'lc_method_params' => $data['method_lc_params'] ]);
        }

        return $switch->id;
    }
}
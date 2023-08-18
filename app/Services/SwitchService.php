<?php

namespace App\Services;

use App\Models\DeviceSwitch;
use App\Models\HiteproDev;
use App\Models\HomeObject;
use App\Models\Port;
use App\Models\Device;
use App\Repositories\PortRepository;
use Illuminate\Support\Facades\DB;
use App\Services\DeviceService;

class SwitchService {

    private $switch_object_service;
    private $portRepository;

    public function __construct(SwitchObjectService $switch_object_service, PortRepository $portRepository)
    {
        $this->switch_object_service = $switch_object_service;
        $this->portRepository = $portRepository;

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
        $this->setPort($this->portRepository->getPortByObject($switch->id_object), 'button');

        Port::where('object', $switch->id_object)->update([
            'object' => null, 'comment' => '']);

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



        return true;
    }

    public function prepareSwitch(DeviceSwitch $switch, array $data)
    {
        $switch->name = trim($data['name']);
        $switch->type = $data['type'];
    }

    /**
     * Настройка физического порта устройства
     * @param int $idPort - ИД порта, который будем изменять
     * @param string $typeObject - тип объекта switch или button
     * @return bool
     */
    private function setPort($idPort, $typeObject)
    {

        if($idPort) {

        $port = Port::where('id', $idPort)->first();


            if ($typeObject == 'button')
                $paramsString = 'ecmd=&af=&eth=&naf=&d=&mt=&pty=0&m=3&nr=1'; //for button
            else
                $paramsString = 'ecmd=&af=&eth=&naf=&misc=&d=&mt=&pty=0&m=0&nr=1'; //for switch

            $answer = ConfigMegaService::setPortSetting($port->id_device, $port->num_port, $paramsString);


        return $answer;
        }

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

        DB::transaction(function () use (&$switch, $data) {
            $unique_name = HomeObject::getUniqueObjectName(0, $switch->name);
            $object = $this->switch_object_service->createSwitchObject($unique_name, $switch->type);
            $switch->id_object = $object->id;

            // Если выбрано устройство хитпро, то добавляем метод в таблицу swithes, если выбрано
            // другое устройство с портом, то добавляем на методы на порт
            if($data['place'] == 'Hite-pro') {

                $switch->id_method = $data['method'];
                $switch->method_params = $data['method_params'];

                HiteproDev::where('id_controller', $data['device_id'])->where('id', $data['hitepro_devices'])
                    ->update(['id_object' => $object->id]);

            } elseif ($data['port_id']) {

                $this->setPort($data['port_id'], $data['type']);

                Port::where('id', $data['port_id'])->update(['object' => $object->id,
                    'method' => $data['method'], 'method_params' => $data['method_params'],
                    'dc_method' => $data['method_dc'], 'dc_method_params' => $data['method_dc_params'],
                    'lc_method' => $data['method_lc'], 'lc_method_params' => $data['method_lc_params'],
                    'status' => 'IN', 'comment' => $data['name']]);

            }

            $switch->save();
            $result = true;

        });

        return $switch->id;
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
            $this->switch_object_service->updateSwitchObjectType($switch->object, $data['type']);
            $switch->save();
        });


        // Если выбрано устройство хитпро, то добавляем метод в таблицу swithes, если выбрано
        // другое устройство с портом, то добавляем на методы на порт
        if($data['place'] == 'Hite-pro') {

            $switch->id_method = $data['method'];
            $switch->method_params = $data['method_params'];
            $switch->save();
            HiteproDev::where('id_object', $switch->object->id)->update(['id_object' => null]);
            Port::where('object', $switch->object->id)->update(['object' => null, 'method' => null]);
            HiteproDev::where('id_controller', $data['device_id'])->where('id', $data['hitepro_devices'])
                ->update(['id_object' => $switch->object->id]);
        } else
        if ($data['port_id']) {

            //$this->setPort($this->portRepository->getPortByObject($switch->object->id), 'button');
            HiteproDev::where('id_object', $switch->object->id)->update(['id_object' => null]);
            $this->setPort($data['port_id'], $switch->type);

            Port::where('object', $switch->object->id)->update([
                'object' => null,
                'method' => null, 'method_params' => null,
                'dc_method' => null, 'dc_method_params' => null,
                'lc_method' => null, 'lc_method_params' => null,
                'comment' => '']);

            if ($data['type'] == DeviceSwitch::TYPE_BUTTON) {
                Port::where('id', $data['port_id'])->update(['object' => $data['id_object'],
                'method' => $data['method'], 'method_params' => $data['method_params'],
                'dc_method' => $data['method_dc'], 'dc_method_params' => $data['method_dc_params'],
                'lc_method' => $data['method_lc'], 'lc_method_params' => $data['method_lc_params'],
                'comment' => $data['name'], 'status' => 'IN']);
            } else {
                Port::where('id', $data['port_id'])->update(['object' => $data['id_object'],
                'method' => $data['method'], 'method_params' => $data['method_params'],
                'comment' => $data['name'], 'status' => 'IN']);
            }
        }

        return $switch->id;
    }
}
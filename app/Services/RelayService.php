<?php

namespace App\Services;

use App\Models\HiteproDev;
use App\Models\HomeObject;
use App\Models\Port;
use App\Models\Relay;
use App\Repositories\PortRepository;
use Illuminate\Support\Facades\DB;

class RelayService
{
    private $relay_object_service;

    private $portRepository;

    public function __construct(RelayObjectService $relay_object_service, PortRepository $portRepository)
    {
        $this->relay_object_service = $relay_object_service;
        $this->portRepository = $portRepository;
    }

    /**
     * Удаление реле. Если связанный объект системный, то удаление объекта и методов,
     * созданных автоматически при создании реле
     *
     * @throws \Throwable
     */
    public function delete(int $id): bool
    {
        $relay = Relay::findOrFail($id);

        Port::where('object', $relay->id_object)->update(['object' => null, 'method' => null, 'comment' => '']);

        if ($relay->object && $relay->object->is_system) {
            DB::transaction(function () use (&$relay) {
                //if (!HomeObject::isObjectUsed($relay->id_object, $relay->id, 'relays')) {
                HomeObject::deleteAutoObject($relay->id_object);
                //}
                $relay->delete();
            });
        } else {
            $relay->delete();
        }

        return true;
    }

    public function prepareRelay(Relay $relay, array $data)
    {
        $relay->name = trim($data['name']);
        if (isset($data['type'])) {
            $relay->type = $data['type'];
        }
    }

    /**
     * Создание реле. Если $data['type'] === 'auto',
     * то еще создается объект с методами
     *
     * @throws \Throwable
     */
    public function store(array $data): int
    {

        $relay = new Relay();
        $deviceID = $data['device_id'];
        $this->prepareRelay($relay, $data);

        DB::transaction(function () use (&$relay, $data, $deviceID) {
            $unique_name = HomeObject::getUniqueObjectName(0, $relay->name);
            $object = $this->relay_object_service->createRelayObject($unique_name, $relay->type);
            $relay->id_object = $object->id;
            $relay->save();

            if ($data['port_id'] && $data['place'] == 'port') {
                $this->relay_object_service->createRelayObjectMethods($object->id, $data['device_id'], $this->portRepository->getNumPortByID($data['port_id']));
                Port::where('id', $data['port_id'])->update(['object' => $object->id, 'status' => 'OUT',
                    'comment' => $data['name']]);

                ConfigMegaService::setPortType($deviceID, $this->portRepository->getNumPortByID($data['port_id']), 'OUT');

            } elseif ($data['place'] == 'Hite-pro') {
                $this->relay_object_service->createRelayObjectMethods($object->id, $data['device_id'], $data['hitepro_devices']);
                HiteproDev::where('id_controller', $data['device_id'])->where('id', $data['hitepro_devices'])
                    ->update(['id_object' => $object->id]);

            }
        });

        return $relay->id;
    }

    private function isUpdateAutoObjectName(Relay $relay, string $name): bool
    {
        return $relay->name !== trim($name) && $relay->object && $relay->object->is_system;
    }

    /**
     * Обновление реле. Если изменилось название и у реле системный объект,
     * то изменяем название объекта.
     * При этом проверяем на уникальность название объекта. Если неуникально, то добавляем число.
     *
     * @throws \Throwable
     */
    public function update(Relay $relay, array $data): int
    {

        DB::transaction(function () use (&$relay, $data) {
            if ($this->isUpdateAutoObjectName($relay, $data['name'])) {
                $relay->object->name = HomeObject::getUniqueObjectName($relay->object->id, trim($data['name']));
                $relay->object->save();
            }
            $this->prepareRelay($relay, $data);
            $relay->save();

            //Сохраняем данные в таблицу Алисы или включаем запись если она есть уже
            if (isset($data['alice_checkbox'])) {
                AliceDevicesService::addOrReplaceDevice($relay->object->id, $data['alice_command'], $data['room']);
            } else {
                AliceDevicesService::setActive($relay->object->id, 0);
            }

            if (! is_null($data['port_id']) && $data['place'] == 'port') {

                Port::where('object', $relay->object->id)->update(['object' => null, 'method' => null, 'comment' => '']);
                Port::where('id', $data['port_id'])->update(['object' => $relay->object->id,
                    'method' => null, 'status' => 'OUT', 'comment' => $data['name']]);

                ConfigMegaService::setPortType($relay->device_id, $this->portRepository->getNumPortByID($data['port_id']), 'OUT');

                HiteproDev::where('id_object', $relay->object->id)->update(['id_object' => null]);

                //Меняем метод easy для всех трех системных методов лампы
                $this->relay_object_service->updateRelayObjectMethods($relay->object->id, $data['device_id'], $this->portRepository->getNumPortByID($data['port_id']));

            } elseif ($data['place'] == 'Hite-pro') {

                HiteproDev::where('id_object', $relay->object->id)->update(['id_object' => null]);
                Port::where('object', $relay->object->id)->update(['object' => null, 'method' => null]);
                HiteproDev::where('id_controller', $data['device_id'])->where('id', $data['hitepro_devices'])
                    ->update(['id_object' => $relay->object->id]);

                //Меняем метод easy для всех трех системных методов реле
                $this->relay_object_service->updateRelayObjectMethods($relay->object->id, $data['device_id'], $data['hitepro_devices']);

            }
        });

        return $relay->id;
    }
}

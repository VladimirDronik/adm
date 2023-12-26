<?php

namespace App\Services;

use App\Models\HiteproDev;
use App\Models\HomeObject;
use App\Models\Lamp;
use App\Models\Port;
use App\Repositories\PortRepository;
use Illuminate\Support\Facades\DB;

class LampService
{
    public function __construct(
        private LampObjectService $lamp_object_service,
        private PortRepository $port_repository
    ) {
    }

    public function prepareLamp(Lamp $lamp, array $data)
    {
        $lamp->name = trim($data['name']);

        if (array_key_exists('is_dimer', $data)) {
            $lamp->type = Lamp::TYPE_DIMER;
            $lamp->value = $data['value'];
            $lamp->speed = $data['speed'];
        } else {
            $lamp->type = Lamp::TYPE_LAMP;
            $lamp->value = null;
            $lamp->speed = null;
        }
    }

    /**
     * Создание лампы. Если $data['type'] === 'auto',
     * то еще создается объект с методами
     *
     * @throws \Throwable
     */
    public function store(array $data): int
    {
        $lamp = new Lamp();

        $deviceID = $data['device_id'];

        $this->prepareLamp($lamp, $data);

        DB::transaction(function () use (&$lamp, $data, $deviceID) {
            $unique_name = HomeObject::getUniqueObjectName(0, $lamp->name);

            $object = $this->lamp_object_service
                ->createLampObject($unique_name, $lamp->type);

            $this->lamp_object_service
                ->createLampObjectMethods(
                    $object->id, $data['device_id'],
                    $this->port_repository->getNumPortByID($data['port_id'])
                );

            $lamp->id_object = $object->id;
            $lamp->save();

            if ($data['port_id'] && $data['place'] == 'port') {
                Port::where('id', $data['port_id'])
                    ->update([
                        'object' => $object->id,
                        'comment' => $data['name'],
                        'status' => 'OUT',
                    ]);

                ConfigMegaService::setPortType(
                    $deviceID,
                    $this->port_repository->getNumPortByID($data['port_id']),
                    'OUT'
                );
            } elseif ($data['place'] == 'Hite-pro') {
                HiteproDev::where('id_controller', $data['device_id'])
                    ->where('id', $data['hitepro_devices'])
                    ->update(['id_object' => $object->id]);
            }
        });

        return $lamp->id;
    }

    /**
     * Удаление лампы. Если связанный объект системный, то удаление объекта и методов,
     * созданных автоматически при создании лампы
     *
     * @throws \Throwable
     */
    public function delete(int $id): bool
    {
        $lamp = Lamp::findOrFail($id);

        Port::where('object', $lamp->object->id)
            ->update([
                'object' => null,
                'method' => null,
                'status' => 'OUT',
                'comment' => '',
            ]);

        if ($lamp->object && $lamp->object->is_system) {
            DB::transaction(function () use (&$lamp) {
                //if (!HomeObject::isObjectUsed($relay->id_object, $relay->id, 'relays')) {
                HomeObject::deleteAutoObject($lamp->id_object);
                //}
                $lamp->delete();
            });
        } else {
            $lamp->delete();
        }

        return true;
    }

    private function isUpdateAutoObjectName(Lamp $lamp, string $name): bool
    {
        return $lamp->name !== trim($name) && $lamp->object && $lamp->object->is_system;
    }

    /**
     * Обновление лампы. Если изменилось название и у лампы есть системный объект,
     * то изменяем название объекта.
     * При этом проверяем на уникальность название объекта. Если неуникально, то добавляем число.
     *
     * @throws \Throwable
     */
    public function update(Lamp $lamp, array $data): int
    {
        $deviceID = $data['device_id'];

        DB::transaction(function () use (&$lamp, $data) {
            if ($this->isUpdateAutoObjectName($lamp, $data['name'])) {
                $lamp->object->name = HomeObject::getUniqueObjectName(
                    $lamp->object->id,
                    trim($data['name'])
                );
                $lamp->object->save();
            }

            $this->prepareLamp($lamp, $data);

            $lamp->save();
        });

        //Сохраняем данные в таблицу Алисы или включаем запись если она есть уже
        if (isset($data['alice_checkbox'])) {
            AliceDevicesService::addOrReplaceDevice(
                $lamp->object->id,
                $data['alice_command'],
                $data['room']
            );
        } else {
            AliceDevicesService::setActive($lamp->object->id, 0);
        }

        //Делаем манипуляции с портами контроллера, если необходимо
        if (! is_null($data['port_id']) && $data['place'] == 'port') {
            Port::where('object', $lamp->object->id)
                ->update([
                    'object' => null,
                    'method' => null,
                    'status' => 'OUT',
                    'comment' => '',
                ]);

            Port::where('id', $data['port_id'])
                ->update([
                    'object' => $lamp->object->id,
                    'method' => null,
                    'status' => 'OUT',
                    'comment' => $data['name'],
                ]);

            HiteproDev::where('id_object', $lamp->object->id)
                ->update(['id_object' => null]);

            ConfigMegaService::setPortType(
                $deviceID,
                $this->port_repository->getNumPortByID($data['port_id']),
                'OUT'
            );

            //Меняем метод easy для всех трех системных методов лампы
            $this->lamp_object_service
                ->updateLampObjectMethods(
                    $lamp->object->id,
                    $data['device_id'],
                    $this->port_repository->getNumPortByID($data['port_id'])
                );
        } elseif ($data['place'] == 'Hite-pro') {
            HiteproDev::where('id_object', $lamp->object->id)
                ->update(['id_object' => null]);

            Port::where('object', $lamp->object->id)
                ->update([
                    'object' => null,
                    'method' => null,
                    'status' => 'OUT',
                    'comment' => '',
                ]);

            HiteproDev::where('id_controller', $data['device_id'])
                ->where('id', $data['hitepro_devices'])
                ->update(['id_object' => $lamp->object->id]);

            //Меняем метод easy для всех трех системных методов лампы
            $this->lamp_object_service
                ->updateLampObjectMethods(
                    $lamp->object->id,
                    $data['device_id'],
                    $data['hitepro_devices']
                );
        }

        return $lamp->id;
    }
}

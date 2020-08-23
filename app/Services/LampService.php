<?php

namespace App\Services;


use App\Models\HomeObject;
use App\Models\Method;
use App\Models\Port;
use App\Repositories\PortRepository;
use Illuminate\Support\Facades\DB;
use App\Models\Lamp;
use App\Models\HiteproDev;

class LampService {

    private $lamp_object_service;
    private $port_repository;

    public function __construct(LampObjectService $lamp_object_service, PortRepository $portRepository)
    {
        $this->lamp_object_service = $lamp_object_service;
        $this->port_repository = $portRepository;
    }

    public function prepareLamp(Lamp $lamp, array $data)
    {
        $lamp->name = trim($data['name']);
        $lamp->id_object = (int)$data['id_object'];
        $lamp->type = 'lamp';
    }

    /**
     * Создание лампы. Если $data['type'] === 'auto',
     * то еще создается объект с методами
     *
     * @param array $data
     * @return int
     * @throws \Throwable
     */
    public function store(array $data): int
    {

        $lamp = new Lamp();
        $this->prepareLamp($lamp, $data);


        if ($data['object_type'] === 'manual') {
            $lamp->save();
        } else if ($data['object_type'] === 'auto') {
            DB::transaction(function () use (&$lamp, $data) {
                $unique_name = HomeObject::getUniqueObjectName(0, $lamp->name);
                $object = $this->lamp_object_service->createLampObject($unique_name, $lamp->type);
                $this->lamp_object_service->createLampObjectMethods($object->id, $data['device_id'], $this->port_repository->getNumPortByID($data['port_id']));
                $lamp->id_object = $object->id;
                $lamp->save();


                if ($data['port_id'] && $data['place'] == 'port') {
                    Port::where('id', $data['port_id'])->update(['object' => $object->id]);
                } elseif ($data['place'] == 'Hite-pro') {
                    HiteproDev::where('id_controller', $data['device_id'])->where('id', $data['hitepro_devices'])
                        ->update(['id_object' => $object->id]);
                }

            });
        }

        return $lamp->id;
    }


    /**
     * Удаление лампы. Если связанный объект системный, то удаление объекта и методов,
     * созданных автоматически при создании лампы
     *
     * @param int $id
     * @return bool
     * @throws \Throwable
     */
    public function delete(int $id): bool
    {
        $lamp = Lamp::findOrFail($id);

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

        Port::where('object', $lamp->id_object)->update(['object' => null, 'method' => null]);

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
     * @param array $data
     * @return int
     * @throws \Throwable
     */
    public function update(Lamp $lamp, array $data): int
    {

        DB::transaction(function () use (&$lamp, $data) {
            if ($this->isUpdateAutoObjectName($lamp, $data['name'])) {
                $lamp->object->name = HomeObject::getUniqueObjectName($lamp->object->id, trim($data['name']));
                $lamp->object->save();
            }

            $this->prepareLamp($lamp, $data);

            $lamp->save();
        });

        if (!is_null($data['port_id']) && $data['place'] == 'port') {

            Port::where('object', $lamp->object->id)->update(['object' => null, 'method' => null]);
            Port::where('id', $data['port_id'])->update(['object' => $lamp->object->id,
                'method' => null]);
            HiteproDev::where('id_object', $lamp->object->id)->update(['id_object' => null]);

            //Меняем метод easy для всех трех системных методов лампы
            $this->lamp_object_service->updateLampObjectMethods($lamp->object->id, $data['device_id'], $this->port_repository->getNumPortByID($data['port_id']));


        }elseif ($data['place'] == 'Hite-pro') {

            HiteproDev::where('id_object', $lamp->object->id)->update(['id_object' => null]);
            Port::where('object', $lamp->object->id)->update(['object' => null, 'method' => null]);
            HiteproDev::where('id_controller', $data['device_id'])->where('id', $data['hitepro_devices'])
                ->update(['id_object' => $lamp->object->id]);

            //Меняем метод easy для всех трех системных методов лампы
            $this->lamp_object_service->updateLampObjectMethods($lamp->object->id, $data['device_id'], $data['hitepro_devices']);

        }

        return $lamp->id;
    }

}
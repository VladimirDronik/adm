<?php

namespace App\Services;

use App\Models\HomeObject;
use App\Models\Hygrostat;
use App\Models\Port;
use App\Models\Termostat;
use App\Repositories\PortRepository;
use Illuminate\Support\Facades\DB;
use App\Services\PortService;

class HygrostatService {

    private $hygrostat_object_service;
    private $port_service;
    private $port_repository;

    public function __construct(HygrostatObjectService $hygrostat_object_service,
                                PortService $portService, PortRepository $portRepository)
    {
        $this->hygrostat_object_service = $hygrostat_object_service;
        $this->port_service = $portService;
        $this->port_repository = $portRepository;
    }

    /**
     * Удаление гигростата. Если связанный объект системный, то удаление объекта, метода, задачи расписания,
     * созданных автоматически при создании термостата
     *
     * @param int $id
     * @return bool
     * @throws \Throwable
     */
    public function delete(int $id): bool
    {
        $hygrostat = Hygrostat::findOrFail($id);


        $deviceAndPort = $this->port_service->getIdDeviceAndPortId($hygrostat->id_object);


        if ($hygrostat->iobject && $hygrostat->iobject->is_system) {
            DB::transaction(function () use (&$hygrostat) {
                //if (!HomeObject::isObjectUsed($termostat->id_object, $termostat->id, 'termostats')) {
                    HomeObject::deleteAutoObject($hygrostat->id_object);
                //}
                $hygrostat->delete();
            });
        } else {
            $hygrostat->delete();
        }


        return true;
    }

    public function prepare(Hygrostat $hygrostat, array $data)
    {

        unset($data['object_type']);
        unset($data['device_id']);
        unset($data['placetype_radio']);
        unset($data['HPController_id']);

        $data['min_threshold'] = '0';
        $data['max_threshold'] = '100';
        $data['current'] = '0';

        if (($data['room'] ?? 0) == 0) {
            $data['room'] = null;
        }

        $hygrostat->fill($data);
    }

    /**
     * Создание гигростата. Если $data['type'] === 'auto',
     * то еще создается объект с методом и событием.
     *
     * @param array $data
     * @return int
     * @throws \Throwable
     */
    public function store(array $data): int
    {

        $hygrostat = new Hygrostat();


        $placeType = $data['placetype'];

        $this->prepare($hygrostat, $data);
        $hygrostat->current = null;

        if ($data['object_type'] === 'manual') {
            $hygrostat->save();
        } elseif ($data['object_type'] === 'auto') {
            DB::transaction(function () use (&$hygrostat,  $placeType) {
                $unique_name = HomeObject::getUniqueObjectName(0, $hygrostat->name);
                $object = $this->hygrostat_object_service->createHygrostatObject($unique_name);
                $this->hygrostat_object_service->createHygrostatObjectMethodsWithEvents($object->id);

                if($hygrostat->room != null)
                RoomService::addHygrostat($hygrostat->room, $hygrostat->optimal);

                $hygrostat->id_object = $object->id;
                $hygrostat->save();

            });
        }

        return $hygrostat->id;
    }

    private function isUpdateAutoObjectName(Hygrostat $hygrostat, string $name): bool
    {
        return $hygrostat->name !== trim($name) && $hygrostat->iobject && $hygrostat->iobject->is_system;
    }

    /**
     * Обновление гигростата. Если изменилось название и у термостата системный объект, то
     * изменяем название объекта.
     * При этом проверяем на уникальность название объекта. Если неуникально, то добавляем число.
     *
     * @param Hygrostat $hygrostat
     * @param array $data
     * @return int
     * @throws \Throwable
     */
    public function update(Hygrostat $hygrostat, array $data): int
    {


        $placeType = $data['placetype'];

        DB::transaction(function () use (&$hygrostat, $data, $placeType) {
            if ($this->isUpdateAutoObjectName($hygrostat, $data['name'])) {
                $hygrostat->iobject->name = HomeObject::getUniqueObjectName($hygrostat->iobject->id, trim($data['name']));
                $hygrostat->iobject->save();

            }


            if(($data['room'] != null) && ($data['room'] != 0))
               RoomService::addHygrostat($data['room'], $data['optimal']);

            $this->prepare($hygrostat, $data);
            $hygrostat->save();
        });

        return $hygrostat->id;
    }

}
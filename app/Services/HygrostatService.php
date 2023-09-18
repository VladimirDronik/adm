<?php

namespace App\Services;

use App\Models\HomeObject;
use App\Models\Hygrostat;
use App\Repositories\PortRepository;
use Illuminate\Support\Facades\DB;

class HygrostatService
{
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

        if (($data['room'] ?? 0) == 0) {
            $data['room'] = null;
        }

        $hygrostat->fill($data);
    }

    /**
     * Создание гигростата. Если $data['type'] === 'auto',
     * то еще создается объект с методом и событием.
     *
     * @throws \Throwable
     */
    public function store(array $data): int
    {

        $hygrostat = new Hygrostat();

        $placeType = $data['placetype'];

        $this->prepare($hygrostat, $data);

        DB::transaction(function () use (&$hygrostat) {
            $unique_name = HomeObject::getUniqueObjectName(0, $hygrostat->name);
            $object = $this->hygrostat_object_service->createHygrostatObject($unique_name);
            $this->hygrostat_object_service->createHygrostatObjectMethodsWithEvents($object->id);

            if ($hygrostat->room != null) {
                RoomService::addHygrostat($hygrostat->room, $hygrostat->optimal);
            }

            $hygrostat->id_object = $object->id;
            $hygrostat->save();

        });

        chdir(env('SERVER_FOLDER').'/scripts');
        exec('php check_hygrostat.php '.$hygrostat->id_object);

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
     * @throws \Throwable
     */
    public function update(Hygrostat $hygrostat, array $data): int
    {
        $placeType = $data['placetype'];

        DB::transaction(function () use (&$hygrostat, $data) {
            if ($this->isUpdateAutoObjectName($hygrostat, $data['name'])) {
                $hygrostat->iobject->name = HomeObject::getUniqueObjectName($hygrostat->iobject->id, trim($data['name']));
                $hygrostat->iobject->save();

            }

            if (($data['room'] != null) && ($data['room'] != 0)) {
                RoomService::addHygrostat($data['room'], $data['optimal']);
            }

            $this->prepare($hygrostat, $data);
            $hygrostat->save();
        });

        chdir(env('SERVER_FOLDER').'/scripts');
        exec('php check_hygrostat.php '.$hygrostat->id_object);

        return $hygrostat->id;
    }
}

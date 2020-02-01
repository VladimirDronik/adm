<?php

namespace App\Services;

use App\Models\HomeObject;
use App\Models\Port;
use App\Models\Termostat;
use Illuminate\Support\Facades\DB;

class TermostatService {

    private $termostat_object_service;

    public function __construct(TermostatObjectService $termostat_object_service)
    {
        $this->termostat_object_service = $termostat_object_service;
    }

    /**
     * Удаление термостата. Если связанный объект системный, то удаление объекта, метода, события,
     * созданных автоматически при создании термостата
     *
     * @param int $id
     * @return bool
     * @throws \Throwable
     */
    public function delete(int $id): bool
    {
        $termostat = Termostat::findOrFail($id);

        if ($termostat->iobject && $termostat->iobject->is_system) {
            DB::transaction(function () use (&$termostat) {
                //if (!HomeObject::isObjectUsed($termostat->id_object, $termostat->id, 'termostats')) {
                    HomeObject::deleteAutoObject($termostat->id_object);
                //}
                $termostat->delete();
            });
        } else {
            $termostat->delete();
        }

        return true;
    }

    public function prepare(Termostat $termostat, array $data)
    {
        unset($data['object_type']);
        if (($data['room'] ?? 0) == 0) {
            $data['room'] = null;
        }
        $termostat->fill($data);
    }

    /**
     * Создание термостата. Если $data['type'] === 'auto',
     * то еще создается объект с методом и событием.
     *
     * @param array $data
     * @return int
     * @throws \Throwable
     */
    public function store(array $data): int
    {
        $termostat = new Termostat();

        $port_id = $data['port_id'] ?? null;
        unset($data['port_id']);
        unset($data['device_id']);

        $this->prepare($termostat, $data);
        $termostat->current = 0;

        if ($data['object_type'] === 'manual') {
            $termostat->save();
        } elseif ($data['object_type'] === 'auto') {
            DB::transaction(function () use (&$termostat, $port_id) {
                $unique_name = HomeObject::getUniqueObjectName(0, $termostat->name);
                $object = $this->termostat_object_service->createTermostatObject($unique_name);
                $this->termostat_object_service->createTermostatObjectMethodsWithEvents($object->id);
                $termostat->id_object = $object->id;
                $termostat->save();

                if ($port_id) {
                    Port::where('id', $port_id)->update(['object' => $object->id]);
                }
            });
        }

        return $termostat->id;
    }

    private function isUpdateAutoObjectName(Termostat $termostat, string $name): bool
    {
        return $termostat->name !== trim($name) && $termostat->iobject && $termostat->iobject->is_system;
    }

    /**
     * Обновление термостата. Если изменилось название и у термостата системный объект, то
     * изменяем название объекта.
     * При этом проверяем на уникальность название объекта. Если неуникально, то добавляем число.
     *
     * @param Termostat $termostat
     * @param array $data
     * @return int
     * @throws \Throwable
     */
    public function update(Termostat $termostat, array $data): int
    {
        DB::transaction(function () use (&$termostat, $data) {
            if ($this->isUpdateAutoObjectName($termostat, $data['name'])) {
                $termostat->iobject->name = HomeObject::getUniqueObjectName($termostat->iobject->id, trim($data['name']));
                $termostat->iobject->save();
            }
            $this->prepare($termostat, $data);
            $termostat->save();
        });

        return $termostat->id;
    }
}
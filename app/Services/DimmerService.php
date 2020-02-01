<?php

namespace App\Services;

use App\Models\Dimmer;
use App\Models\HomeObject;
use App\Models\Port;
use Illuminate\Support\Facades\DB;

class DimmerService {

    private $dimmer_object_service;

    public function __construct(DimmerObjectService $dimmer_object_service)
    {
        $this->dimmer_object_service = $dimmer_object_service;
    }

    /**
     * Удаление диммера. Если связанный объект системный, то еще удаление его объекта и методов,
     * созданных автоматически при создании диммера
     *
     * @param int $id
     * @return bool
     * @throws \Throwable
     */
    public function delete(int $id): bool
    {
        $dimmer = Dimmer::findOrFail($id);

        if ($dimmer->object && $dimmer->object->is_system) {
            DB::transaction(function () use (&$dimmer) {
                if (!HomeObject::isObjectUsed($dimmer->id_object, $dimmer->id, 'dimmers')) {
                    HomeObject::deleteAutoObject($dimmer->id_object);
                }
                $dimmer->delete();
            });
        } else {
            $dimmer->delete();
        }

        return true;
    }

    public function prepareDimmer(Dimmer $dimmer, array $data)
    {
        $dimmer->name = trim($data['name']);
        $dimmer->id_object = is_null($data['id_object']) ? null : (int)$data['id_object'];
        $dimmer->value = (int)$data['value'];
        $dimmer->speed= (int)$data['speed'];
    }

    /**
     * Создание диммера. Если $data['type'] === 'auto',
     * то еще создается объект с методами
     *
     * @param array $data
     * @return int
     * @throws \Throwable
     */
    public function store(array $data): int
    {
        $dimmer = new Dimmer();
        $this->prepareDimmer($dimmer, $data);

        if ($data['object_type'] === 'manual') {
            $dimmer->save();
        } else if ($data['object_type'] === 'auto') {
            DB::transaction(function () use (&$dimmer, $data) {
                $unique_name = HomeObject::getUniqueObjectName(0, $dimmer->name);
                $object = $this->dimmer_object_service->createDimmerObject($unique_name);
                $this->dimmer_object_service->createDimmerObjectMethods($object->id);
                $dimmer->id_object = $object->id;
                $dimmer->save();

                if ($data['port_id']) {
                    Port::where('id', $data['port_id'])->update(['object' => $object->id]);
                }
            });
        }

        return $dimmer->id;
    }

    private function isUpdateAutoObjectName(Dimmer $dimmer, string $name): bool
    {
        return $dimmer->name !== trim($name) && $dimmer->object && $dimmer->object->is_system;
    }

    /**
     * Обновление диммера. Если изменилось название и у диммера системный объект,
     * то изменяем название объекта.
     * При этом проверяем на уникальность название объекта. Если неуникально,
     * то добавляем число.
     *
     *
     * @param Dimmer $dimmer
     * @param array $data
     * @return int
     * @throws \Throwable
     */
    public function update(Dimmer $dimmer, array $data): int
    {
        DB::transaction(function () use (&$dimmer, $data) {
            if ($this->isUpdateAutoObjectName($dimmer, $data['name'])) {
                $dimmer->object->name = HomeObject::getUniqueObjectName($dimmer->object->id, trim($data['name']));
                $dimmer->object->save();
            }
            $this->prepareDimmer($dimmer, $data);
            $dimmer->save();
        });

        return $dimmer->id;
    }
}
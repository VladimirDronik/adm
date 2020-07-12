<?php

namespace App\Services;


use App\Models\HomeObject;
use App\Models\Port;
use Illuminate\Support\Facades\DB;
use App\Models\Lamp;


class LampService {

    private $lamp_object_service;

    public function __construct(LampObjectService $lamp_object_service)
    {
        $this->lamp_object_service = $lamp_object_service;
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
                $this->lamp_object_service->createLampObjectMethods($object->id);
                $lamp->id_object = $object->id;
                $lamp->save();

                if ($data['port_id']) {
                    Port::where('id', $data['port_id'])->update(['object' => $object->id]);
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

}
<?php

namespace App\Services;


use App\Models\Motionsensor;
use App\Models\HomeObject;
use App\Services\MotionSensorObjectService;
use Illuminate\Support\Facades\DB;
use App\Models\Port;

class MotionsensorService {

    private $motionsensor_object_service;

    public function __construct(MotionSensorObjectService $motionsensor_object_service)
    {
        $this->motionsensor_object_service = $motionsensor_object_service;
    }

    public function prepareMotionsensor(Motionsensor $motionsensor, array $data)
    {
        $data['name'] = trim($data['name']);

        unset($data['_method']);
        unset($data['object_normal']);
        unset($data['object_eco']);
        unset($data['object_night']);
        unset($data['object_evening']);
        unset($data['object_morning']);
        unset($data['object_guard']);
        unset($data['object_lightstat']);
        unset($data['object_light']);
        unset($data['object_type']);
        unset($data['device_id']);
        unset($data['port_id']);

        $motionsensor->Fill($data);
    }


    public function store(array $data): int
    {

        $motionsensor = new Motionsensor();
        $this->prepareMotionsensor($motionsensor, $data);

        if ($data['object_type'] === 'manual') {
            $motionsensor->save();
        } else if ($data['object_type'] === 'auto') {
            DB::transaction(function () use (&$motionsensor, $data) {
                $unique_name = HomeObject::getUniqueObjectName(0, $motionsensor->name);
                $object = $this->motionsensor_object_service->createMotionsensorObject($unique_name);
                $motionsensor->id_object = $object->id;

                $motionsensor->save();

                if ($data['port_id']) {
                    Port::where('id', $data['port_id'])->update(['object' => $object->id]);
                }
            });
        }

        return $motionsensor->id;
    }

    private function isUpdateAutoObjectName(Motionsensor $motionsensor, string $name): bool
    {
        return $motionsensor->name !== trim($name) && $motionsensor->iobject && $motionsensor->iobject->is_system;
    }

    public function update(Motionsensor $motionsensor, array $data): int
    {

        DB::transaction(function () use (&$motionsensor, $data) {
            if ($this->isUpdateAutoObjectName($motionsensor, $data['name'])) {
                $motionsensor->iobject->name = HomeObject::getUniqueObjectName($motionsensor->iobject->id, trim($data['name']));
                $motionsensor->iobject->save();

            }

            /*
            if ($data['port_id']) {
                Port::where('object', $motionsensor->id_object)->update(['object' => NULL]);
                Port::where('id', $data['port_id'])->update(['object' => $motionsensor->id_object]);
            }
*/


            $this->prepareMotionsensor($motionsensor, $data);
            $motionsensor->save();
        });

        return $motionsensor->id;
    }

    /**
     * Удаление датчика освещения. Если связанный объект системный, то удаление объекта, метода, события,
     * созданных автоматически при создании светостата
     *
     * @param int $id
     * @return bool
     * @throws \Throwable
     */
    public function delete(int $id): bool
    {
        $motionsensor = Motionsensor::findOrFail($id);

        if ($motionsensor->iobject && $motionsensor->iobject->is_system) {
            DB::transaction(function () use (&$motionsensor) {
                HomeObject::deleteAutoObject($motionsensor->id_object);
                $motionsensor->delete();
            });
        } else {
            $motionsensor->delete();
        }

        return true;
    }

}
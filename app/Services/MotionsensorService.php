<?php

namespace App\Services;


use App\Models\Motionsensor;
use App\Models\HomeObject;
use App\Services\MotionSensorObjectService;
use Illuminate\Support\Facades\DB;
use App\Models\Port;
use App\Repositories\PortRepository;
use App\Services\PortService;

class MotionsensorService {

    private $motionsensor_object_service;
    private $objectService;
    private $portRepository;
    private $portService;

    public function __construct(MotionSensorObjectService $motionsensor_object_service, ObjectService $objectService,
                                PortRepository $portRepository, PortService $portService)
    {
        $this->motionsensor_object_service = $motionsensor_object_service;
        $this->objectService = $objectService;
        $this->portRepository = $portRepository;
        $this->portService = $portService;
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

        $deviceID = $data['device_id'];
        $portID =  $data['port_id'];

        $motionsensor = new Motionsensor();
        $this->prepareMotionsensor($motionsensor, $data);

        if ($data['object_type'] === 'manual') {
            $motionsensor->save();
        } else if ($data['object_type'] === 'auto') {
            DB::transaction(function () use (&$motionsensor, $data, $deviceID, $portID) {


                $unique_name = HomeObject::getUniqueObjectName(0, $motionsensor->name);
                $object = $this->motionsensor_object_service->createMotionsensorObject($unique_name);
                $motionsensor->id_object = $object->id;

                $motionsensor->save();

                $idNewMethod = $this->motionsensor_object_service->createMotionsensorObjectMethods($object->id);

                if ($portID) {

                    Port::where('id', $portID)->update(['object' => $object->id, 'method' => $idNewMethod,
                        'status' => 'IN', 'comment' => $data['name']]);
                    ConfigMegaService::setPortType($deviceID, $this->portRepository->getNumPortByID($portID), 'IN');
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


            if ($data['port_id']) {

                ConfigMegaService::setPortType($data['device_id'], $this->portRepository->getNumPortByID($data['port_id']), 'IN');

                Port::where('object', $motionsensor->id_object)->update(['object' => null, 'method' => null, 'comment' => '']);
                Port::where('id', $data['port_id'])->update(['object' => $motionsensor->id_object,
                    'method' => $this->objectService->getMethodByObject($motionsensor->id_object), 'comment' => $data['name'],
                'status' => 'IN']);

            }


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

        Port::where('object', $motionsensor->id_object)->update(['object' => null, 'method' => null, 'comment' => '']);

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
<?php

namespace App\Services;

use App\Models\Count;
use App\Models\HomeObject;
use App\Models\Port;
use Illuminate\Support\Facades\DB;
use App\Repositories\PortRepository;

class CountService {

    private $count_object_service;
    private $portRepository;

    public function __construct(CountObjectService $count_object_service, PortRepository $portRepository)
    {
        $this->count_object_service = $count_object_service;
        $this->portRepository = $portRepository;
    }

    /**
     * Удаление счетчика. Если связанный объект системный, то удаление объекта, методов, событий,
     * созданных автоматически при создании счетчика
     *
     * @param int $id
     * @return bool
     * @throws \Throwable
     */
    public function delete(int $id): bool
    {
        $count = Count::findOrFail($id);

        Port::where('object', $count->id_object)->update(['object' => null, 'method' => null, 'status' => 'IN',
            'comment' => '']);


        if ($count->object && $count->object->is_system) {
            DB::transaction(function () use (&$count) {
                if (!HomeObject::isObjectUsed($count->id_object, $count->id, 'counts')) {
                    HomeObject::deleteAutoObject($count->id_object);
                }
                $count->delete();
            });
        } else {
            $count->delete();
        }


        return true;
    }

    public function prepareCount(Count $count, array $data)
    {
        $count->name = trim($data['name']);
        if (isset($data['type'])) {
            $count->type = $data['type'];
        }
        $count->id_object = (int)$data['id_object'];
        $count->impulse = $data['impulse'];
        if (isset($data['unit'])) {
            $count->unit = trim($data['unit']);
        }
        $count->today_value = $data['today_value'] ?? 0;
        $count->total_value = $data['total_value'] ?? 0;
    }

    /**
     * Создание счетчика. Если $data['type'] === 'auto',
     * то еще создается объект с методами и задача в расписании.
     *
     * @param array $data
     * @return int
     * @throws \Throwable
     */
    public function store(array $data): int
    {
        $count = new Count();
        $this->prepareCount($count, $data);

        if ($data['object_type'] === 'manual') {
            $count->save();
        } else if ($data['object_type'] === 'auto') {
            DB::transaction(function () use (&$count, $data) {
                $unique_name = HomeObject::getUniqueObjectName(0, $count->name);
                $object = $this->count_object_service->createCountObject($unique_name);
                $this->count_object_service->createCountObjectMethodsWithEvents($object->id);
                $count->id_object = $object->id;
                $count->save();

                if ($data['port_id']) {
                    Port::where('id', $data['port_id'])->update(['object' => $object->id, 'status' => 'IN',
                        'comment' => $data['name']]);

                    ConfigMegaService::setPortType($count->device_id, $this->portRepository->getNumPortByID($data['port_id']), 'IN');

                }
            });
        }

        return $count->id;
    }

    private function isUpdateAutoObjectName(Count $count, string $name): bool
    {
        return $count->name !== trim($name) && $count->object && $count->object->is_system;
    }

    /**
     * Обновление счетчика. Если изменилось название и у счетчика системный объект,
     * то изменяем название объекта.
     * При этом проверяем на уникальность название объекта. Если неуникально, то добавляем число.
     *
     * @param Count $count
     * @param array $data
     * @return int
     * @throws \Throwable
     */
    public function update(Count $count, array $data): int
    {
        DB::transaction(function () use (&$count, $data) {
            if ($this->isUpdateAutoObjectName($count, $data['name'])) {
                $count->object->name = HomeObject::getUniqueObjectName($count->object->id, trim($data['name']));
                $count->object->save();
            }
            $this->prepareCount($count, $data);
            $count->save();

            if ($data['port_id']) {
                Port::where('object', $count->id_object)->update(['object' => null, 'method' => null,
                    'comment' => '', 'status' => 'IN']);
                Port::where('id', $data['port_id'])->update(['object' => $count->id_object, 'status' => 'IN',
                    'comment' => $data['port_id']]);

                ConfigMegaService::setPortType($count->device_id, $this->portRepository->getNumPortByID($data['port_id']), 'IN');

            }

        });



        return $count->id;
    }
}
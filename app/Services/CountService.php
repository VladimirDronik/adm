<?php

namespace App\Services;

use App\Models\Count;
use App\Models\HomeObject;
use App\Models\Port;
use App\Repositories\PortRepository;
use Illuminate\Support\Facades\DB;

class CountService
{
    public function __construct(
        private CountObjectService $countObjectService,
        private PortRepository $portRepository
    ) {
    }

    /**
     * Удаление счетчика. Если связанный объект системный, то удаление объекта, методов, событий,
     * созданных автоматически при создании счетчика
     *
     * @throws \Throwable
     */
    public function delete(int $id): bool
    {
        $count = Count::findOrFail($id);

        if ($count->gateway_type == HomeObject::GATEWAY_HTTP) {
            Port::where('object', $count->id_object)
                ->update([
                    'object' => null,
                    'method' => null,
                    'status' => 'IN',
                    'comment' => '',
                ]);
        }

        if ($count->object && $count->object->is_system) {
            DB::transaction(function () use (&$count) {
                if (! HomeObject::isObjectUsed($count->id_object, $count->id, 'counts')) {
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
     * @throws \Throwable
     */
    public function store(array $data): int
    {
        $count = new Count();
        $this->prepareCount($count, $data);

        DB::transaction(function () use (&$count, $data) {
            $unique_name = HomeObject::getUniqueObjectName(0, $count->name);
            $object = $this->countObjectService->createCountObject($unique_name);
            $this->countObjectService->createCountObjectMethodsWithEvents($object->id);

            $count->id_object = $object->id;

            switch ($count->gateway_type) {
                case HomeObject::GATEWAY_MODBUS:
                    $count->gateway_id = $data['modbus_gateway_id'];
                    break;
                case HomeObject::GATEWAY_HTTP:
                    $count->gateway_id = $data['http_gateway_id'];

                    if ($data['port_id']) {
                        Port::where('id', $data['port_id'])
                            ->update([
                                'object' => $object->id,
                                'status' => 'IN',
                                'comment' => $data['name'],
                            ]);

                        ConfigMegaService::setPortType(
                            $data['gateway_id'],
                            $this->portRepository->getNumPortByID($data['port_id']),
                            'IN'
                        );
                    }
                    break;
            }

            $count->save();
        });

        return $count->id;
    }

    /**
     * Обновление счетчика. Если изменилось название и у счетчика системный объект,
     * то изменяем название объекта.
     * При этом проверяем на уникальность название объекта. Если неуникально, то добавляем число.
     *
     * @throws \Throwable
     */
    public function update(Count $count, array $data): int
    {
        DB::transaction(function () use (&$count, $data) {
            if ($count->name !== trim($data['name'])) {
                $count->object->name = HomeObject::getUniqueObjectName(
                    $count->id_object,
                    trim($data['name'])
                );
                $count->object->save();
            }
            $this->prepareCount($count, $data);
            $count->gateway_id = $data['gateway_id'];
            $count->save();

            if ($count->gateway_type == HomeObject::GATEWAY_HTTP) {
                Port::where('object', $count->id_object)
                    ->update([
                        'object' => null,
                        'method' => null,
                        'comment' => '',
                        'status' => 'IN',
                    ]);

                Port::where('id', $data['port_id'])
                    ->update([
                        'object' => $count->id_object,
                        'status' => 'IN',
                        'comment' => $data['port_id'],
                    ]);

                ConfigMegaService::setPortType(
                    $data['gateway_id'],
                    $this->portRepository->getNumPortByID($data['port_id']),
                    'IN',
                );
            }
        });

        return $count->id;
    }
}

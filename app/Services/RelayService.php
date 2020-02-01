<?php

namespace App\Services;

use App\Models\HomeObject;
use App\Models\Port;
use App\Models\Relay;
use Illuminate\Support\Facades\DB;

class RelayService {

    private $relay_object_service;

    public function __construct(RelayObjectService $relay_object_service)
    {
        $this->relay_object_service = $relay_object_service;
    }

    /**
     * Удаление реле. Если связанный объект системный, то удаление объекта и методов,
     * созданных автоматически при создании реле
     *
     * @param int $id
     * @return bool
     * @throws \Throwable
     */
    public function delete(int $id): bool
    {
        $relay = Relay::findOrFail($id);

        if ($relay->object && $relay->object->is_system) {
            DB::transaction(function () use (&$relay) {
                //if (!HomeObject::isObjectUsed($relay->id_object, $relay->id, 'relays')) {
                    HomeObject::deleteAutoObject($relay->id_object);
                //}
                $relay->delete();
            });
        } else {
            $relay->delete();
        }

        return true;
    }

    public function prepareRelay(Relay $relay, array $data)
    {
        $relay->name = trim($data['name']);
        if (isset($data['type'])) {
            $relay->type = $data['type'];
        }
        $relay->id_object = (int)$data['id_object'];
    }

    /**
     * Создание реле. Если $data['type'] === 'auto',
     * то еще создается объект с методами
     *
     * @param array $data
     * @return int
     * @throws \Throwable
     */
    public function store(array $data): int
    {
        $relay = new Relay();
        $this->prepareRelay($relay, $data);

        if ($data['object_type'] === 'manual') {
            $relay->save();
        } else if ($data['object_type'] === 'auto') {
            DB::transaction(function () use (&$relay, $data) {
                $unique_name = HomeObject::getUniqueObjectName(0, $relay->name);
                $object = $this->relay_object_service->createRelayObject($unique_name, $relay->type);
                $this->relay_object_service->createRelayObjectMethods($object->id);
                $relay->id_object = $object->id;
                $relay->save();

                if ($data['port_id']) {
                    Port::where('id', $data['port_id'])->update(['object' => $object->id]);
                }
            });
        }

        return $relay->id;
    }

    private function isUpdateAutoObjectName(Relay $relay, string $name): bool
    {
        return $relay->name !== trim($name) && $relay->object && $relay->object->is_system;
    }

    /**
     * Обновление реле. Если изменилось название и у реле системный объект,
     * то изменяем название объекта.
     * При этом проверяем на уникальность название объекта. Если неуникально, то добавляем число.
     *
     * @param Relay $relay
     * @param array $data
     * @return int
     * @throws \Throwable
     */
    public function update(Relay $relay, array $data): int
    {
        DB::transaction(function () use (&$relay, $data) {
            if ($this->isUpdateAutoObjectName($relay, $data['name'])) {
                $relay->object->name = HomeObject::getUniqueObjectName($relay->object->id, trim($data['name']));
                $relay->object->save();
            }
            $this->prepareRelay($relay, $data);
            $relay->save();
        });

        return $relay->id;
    }
}
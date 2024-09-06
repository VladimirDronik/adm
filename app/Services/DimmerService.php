<?php

namespace App\Services;

use App\Models\Port;
use App\Models\Dimmer;
use App\Models\HomeObject;
use Illuminate\Support\Facades\DB;
use App\Repositories\PortRepository;

class DimmerService
{
    public function __construct(
        private DimmerObjectService $dimmerObjectService,
        private PortRepository $portRepository
    ) {
    }

    /**
     * Удаление диммера. Если связанный объект системный, то еще удаление его объекта и методов,
     * созданных автоматически при создании диммера
     *
     * @throws \Throwable
     */
    public function delete(int $id): bool
    {
        $dimmer = Dimmer::findOrFail($id);

        Port::where('object', $dimmer->id_object)
            ->update([
                'object' => null,
                'method' => null,
                'comment' => '',
                'status' => 'OUT',
            ]);

        if ($dimmer->object && $dimmer->object->is_system) {
            DB::transaction(function () use (&$dimmer) {
                if (! HomeObject::isObjectUsed($dimmer->id_object, $dimmer->id, 'dimmers')) {
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
        $dimmer->value = (int) $data['value'];
        $dimmer->speed = (int) $data['speed'];
    }

    /**
     * Создание диммера. Если $data['type'] === 'auto',
     * то еще создается объект с методами
     *
     * @throws \Throwable
     */
    public function store(array $data): int
    {
        $dimmer = new Dimmer();
        $deviceID = $data['device_id'];
        $this->prepareDimmer($dimmer, $data);

        DB::transaction(function () use (&$dimmer, $data, $deviceID) {
            $unique_name = HomeObject::getUniqueObjectName(0, $dimmer->name);
            $object = $this->dimmerObjectService->createDimmerObject($unique_name);
            $this->dimmerObjectService->createDimmerObjectMethods($object->id);
            $dimmer->id_object = $object->id;
            $dimmer->save();

            if ($data['port_id']) {
                Port::where('id', $data['port_id'])
                    ->update([
                        'object' => $object->id,
                        'comment' => $data['name'],
                    ]);

                ConfigMegaService::setPortType(
                    $deviceID,
                    $this->portRepository->getNumPortByID($data['port_id']),
                    'PWM'
                );
            }
        });

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
     * @throws \Throwable
     */
    public function update(Dimmer $dimmer, array $data): int
    {
        $deviceID = $data['device_id'];

        DB::transaction(function () use (&$dimmer, $data, $deviceID) {
            if ($this->isUpdateAutoObjectName($dimmer, $data['name'])) {
                $dimmer->object->name = HomeObject::getUniqueObjectName($dimmer->object->id, trim($data['name']));
                $dimmer->object->save();
            }
            $this->prepareDimmer($dimmer, $data);
            $dimmer->save();

            //Сохраняем данные в таблицу Алисы или включаем запись если она есть уже
            if (isset($data['alice_checkbox'])) {
                AliceDevicesService::addOrReplaceDevice(
                    $dimmer->object->id,
                    $data['alice_command'],
                    $data['room']
                );
            } else {
                AliceDevicesService::setActive($dimmer->object->id, 0);
            }

            if ($data['port_id']) {
                Port::where('object', $dimmer->id_object)
                    ->update([
                        'object' => null,
                        'method' => null,
                        'comment' => '',
                    ]);

                Port::where('id', $data['port_id'])
                    ->update([
                        'object' => $dimmer->id_object,
                        'comment' => $data['name'],
                    ]);

                ConfigMegaService::setPortType(
                    $deviceID,
                    $this->portRepository->getNumPortByID($data['port_id']),
                    'PWM'
                );
            }
        });

        return $dimmer->id;
    }
}

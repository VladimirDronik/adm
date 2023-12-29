<?php

namespace App\Services;

use App\Models\HiteproDev;
use App\Models\HomeObject;
use App\Models\Lamp;
use App\Models\Port;
use App\Repositories\PortRepository;
use Illuminate\Support\Facades\DB;

class LampService
{
    public function __construct(
        private LampObjectService $lampObjectService,
        private PortRepository $port_repository
    ) {
    }

    /**
     * Создание лампы. Если $data['type'] === 'auto',
     * то еще создается объект с методами
     *
     * @throws \Throwable
     */
    public function store(array $data): int
    {
        $lamp = new Lamp();

        $lamp->name = trim($data['name']);
        $lamp->gateway_type = $data['gateway_type'];

        DB::transaction(function () use (&$lamp, $data) {
            $uniqueName = HomeObject::getUniqueObjectName(0, $lamp->name);

            $object = $this->lampObjectService
                ->createLampObject($uniqueName, $lamp->type);

            switch ($lamp->gateway_type) {
                case HomeObject::GATEWAY_MODBUS:
                    $lamp->gateway_id = $data['modbus_gateway_id'];

                    $this->lampObjectService
                        ->createLampObjectMethods($object->id, null, null, $data['register_id']);
                    break;
                case HomeObject::GATEWAY_HTTP:
                    $lamp->gateway_id = $data['http_gateway_id'];

                    $numPort = $this->port_repository->getNumPortByID($data['port_id']);

                    $this->lampObjectService
                        ->createLampObjectMethods($object->id, $data['http_gateway_id'], $numPort);

                    Port::where('id', $data['port_id'])
                        ->update([
                            'object' => $object->id,
                            'comment' => $data['name'],
                            'status' => 'OUT',
                        ]);

                    ConfigMegaService::setPortType($data['http_gateway_id'], $numPort, 'OUT');
                    break;
            }

            $lamp->id_object = $object->id;
            $lamp->save();
        });

        return $lamp->id;
    }

    /**
     * Удаление лампы. Если связанный объект системный, то удаление объекта и методов,
     * созданных автоматически при создании лампы
     *
     * @throws \Throwable
     */
    public function delete(int $id): bool
    {
        $lamp = Lamp::findOrFail($id);

        Port::where('object', $lamp->object->id)
            ->update([
                'object' => null,
                'method' => null,
                'status' => 'OUT',
                'comment' => '',
            ]);

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

        return true;
    }

    private function isUpdateAutoObjectName(Lamp $lamp, string $name): bool
    {
        return $lamp->name !== trim($name) && $lamp->object && $lamp->object->is_system;
    }

    /**
     * Обновление лампы. Если изменилось название и у лампы есть системный объект,
     * то изменяем название объекта.
     * При этом проверяем на уникальность название объекта. Если неуникально, то добавляем число.
     *
     * @throws \Throwable
     */
    public function update(Lamp $lamp, array $data): int
    {
        DB::transaction(function () use (&$lamp, $data) {
            if ($this->isUpdateAutoObjectName($lamp, $data['name'])) {
                $lamp->object->name = HomeObject::getUniqueObjectName(
                    $lamp->object->id,
                    trim($data['name'])
                );
                $lamp->object->save();
            }

            $lamp->name = trim($data['name']);
            $lamp->gateway_id = $data['gateway_id'];

            switch ($lamp->gateway_type) {
                case HomeObject::GATEWAY_MODBUS:
                    if (array_key_exists('is_dimer', $data)) {
                        $lamp->type = Lamp::TYPE_DIMER;

                        if ($data['register_id']) {
                            $this->lampObjectService
                                ->updateAllLampDimmerMethods($lamp->object->id, $data['register_id']);
                        } else {
                            $this->lampObjectService
                                ->updateLampMethodsWithCurrentRegisters($lamp->object, $data);
                        }
                    } else {
                        $lamp->type = Lamp::TYPE_LAMP;

                        if ($data['register_id']) {
                            $this->lampObjectService
                                ->updateAllLampMethods($lamp->object->id, null, null, $data['register_id']);
                        } else {
                            $this->lampObjectService
                                ->updateLampMethodsWithCurrentRegisters($lamp->object, $data);
                        }
                    }
                    break;
                case HomeObject::GATEWAY_HTTP:
                    $numPort = $this->port_repository->getNumPortByID($data['port_id']);

                    if (array_key_exists('is_dimer', $data)) {
                        $lamp->type = Lamp::TYPE_DIMER;
                        $lamp->value = $data['value'];
                        $lamp->speed = $data['speed'];
                    } else {
                        $lamp->type = Lamp::TYPE_LAMP;
                        $lamp->value = null;
                        $lamp->speed = null;

                        $this->lampObjectService
                            ->updateAllLampMethods($lamp->object->id, $data['gateway_id'], $numPort);
                    }

                    Port::where('object', $lamp->object->id)
                        ->update([
                            'object' => null,
                            'method' => null,
                            'status' => 'OUT',
                            'comment' => '',
                        ]);

                    Port::where('id', $data['port_id'])
                        ->update([
                            'object' => $lamp->object->id,
                            'method' => null,
                            'status' => 'OUT',
                            'comment' => $data['name'],
                        ]);

                    ConfigMegaService::setPortType($data['gateway_id'], $numPort, 'OUT');
                    break;
            }

            $lamp->save();
        });

        //Сохраняем данные в таблицу Алисы или включаем запись если она есть уже
        if (isset($data['alice_checkbox'])) {
            AliceDevicesService::addOrReplaceDevice(
                $lamp->object->id,
                $data['alice_command'],
                $data['room']
            );
        } else {
            AliceDevicesService::setActive($lamp->object->id, 0);
        }

        return $lamp->id;
    }
}

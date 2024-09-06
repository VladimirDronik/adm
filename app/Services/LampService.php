<?php

namespace App\Services;

use App\Models\Lamp;
use App\Models\Port;
use App\Models\HomeObject;
use Illuminate\Support\Facades\DB;
use App\Repositories\PortRepository;

class LampService
{
    public function __construct(
        private LampObjectService $lampObjectService,
        private PortRepository $portRepository
    ) {
    }

    /**
     * Создание лампы.
     *
     * @throws \Throwable
     */
    public function store(array $data): int
    {
        $lamp = new Lamp();

        $lamp->name = trim($data['name']);
        $lamp->gateway_type = $data['gateway_type'];
        $lamp->type = Lamp::TYPE_LAMP;

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
                    $port = Port::find($data['port_id']);

                    $this->lampObjectService
                        ->createLampObjectMethods($object->id, $data['http_gateway_id'], $port);

                    $port->update([
                        'object' => $object->id,
                        'comment' => $data['name'],
                        'status' => 'OUT',
                    ]);

                    ConfigMegaService::setPortType($data['http_gateway_id'], $port->num_port, 'OUT');
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

        if ($lamp->gateway_type == HomeObject::GATEWAY_HTTP) {
            Port::where('object', $lamp->id_object)
                ->update([
                    'object' => null,
                    'method' => null,
                    'status' => 'OUT',
                    'comment' => '',
                ]);
        }

        if ($lamp->object) {
            DB::transaction(function () use ($lamp) {
                $lamp->object->delete();
                $lamp->delete();
            });
        } else {
            $lamp->delete();
        }

        return true;
    }

    /**
     * Обновление лампы.
     *
     * @throws \Throwable
     */
    public function update(Lamp $lamp, array $data): int
    {
        DB::transaction(function () use (&$lamp, $data) {
            if ($lamp->name != trim($data['name'])) {
                $lamp->object->name = HomeObject::getUniqueObjectName(
                    $lamp->id_object,
                    trim($data['name'])
                );
            }

            $lamp->name = trim($data['name']);
            $lamp->gateway_id = $data['gateway_id'];

            switch ($lamp->gateway_type) {
                case HomeObject::GATEWAY_MODBUS:
                    if (array_key_exists('is_dimmer', $data)) {
                        $lamp->type = Lamp::TYPE_DIMMER;
                        $lamp->object->type = Lamp::TYPE_DIMMER;

                        if ($data['register_id']) {
                            $this->lampObjectService
                                ->updateAllLampDimmerMethods($lamp->object->id, $data['register_id']);
                        } else {
                            $this->lampObjectService
                                ->updateLampMethodsWithCurrentRegisters($lamp->object, $data);
                        }
                    } else {
                        $lamp->type = Lamp::TYPE_LAMP;
                        $lamp->object->type = Lamp::TYPE_LAMP;

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
                    Port::where('object', $lamp->object->id)
                        ->update([
                            'object' => null,
                            'method' => null,
                            'status' => 'OUT',
                            'comment' => '',
                        ]);

                    $port = Port::find($data['port_id']);

                    if (array_key_exists('is_dimmer', $data)) {
                        $lamp->type = Lamp::TYPE_DIMMER;
                        $lamp->object->type = Lamp::TYPE_DIMMER;
                        $lamp->value = $data['value'];
                        $lamp->speed = $data['speed'];
                    } else {
                        $lamp->type = Lamp::TYPE_LAMP;
                        $lamp->object->type = Lamp::TYPE_LAMP;
                        $lamp->value = null;
                        $lamp->speed = null;

                        $this->lampObjectService
                            ->updateAllLampMethods($lamp->object->id, $data['gateway_id'], $port);
                    }

                    $port->update([
                        'object' => $lamp->object->id,
                        'method' => null,
                        'status' => 'OUT',
                        'comment' => $data['name'],
                    ]);

                    ConfigMegaService::setPortType($data['gateway_id'], $port->num_port, 'OUT');
                    break;
            }

            $lamp->object->save();
            $lamp->save();

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
        });

        return $lamp->id;
    }
}

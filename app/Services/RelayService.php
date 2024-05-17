<?php

namespace App\Services;

use App\Models\HomeObject;
use App\Models\Port;
use App\Models\Relay;
use App\Repositories\PortRepository;
use Illuminate\Support\Facades\DB;

class RelayService
{
    public function __construct(
        private RelayObjectService $relayObjectService,
        private PortRepository $portRepository
    ) {
    }

    /**
     * Удаление реле.
     *
     * @throws \Throwable
     */
    public function delete(int $id): bool
    {
        $relay = Relay::findOrFail($id);

        if ($relay->gateway_type == HomeObject::GATEWAY_HTTP) {
            Port::where('object', $relay->id_object)
                ->update([
                    'object' => null,
                    'method' => null,
                    'status' => 'OUT',
                    'comment' => '',
                ]);
        }

        if ($relay->object) {
            DB::transaction(function () use ($relay) {
                $relay->object->delete();
                $relay->delete();
            });
        } else {
            $relay->delete();
        }

        return true;
    }

    /**
     * Создание реле
     *
     * @throws \Throwable
     */
    public function store(array $data): int
    {
        $relay = new Relay();
        $relay->name = trim($data['name']);
        $relay->gateway_type = $data['gateway_type'];
        $relay->type = Relay::TYPE_RELAY;

        DB::transaction(function () use (&$relay, $data) {
            $uniqueName = HomeObject::getUniqueObjectName(0, $relay->name);
            $object = $this->relayObjectService->createRelayObject($uniqueName);

            switch ($relay->gateway_type) {
                case HomeObject::GATEWAY_MODBUS:
                    $relay->gateway_id = $data['modbus_gateway_id'];

                    $this->relayObjectService
                        ->createRelayObjectMethods($object->id, null, null, $data['register_id']);
                    break;
                case HomeObject::GATEWAY_HTTP:
                    $relay->gateway_id = $data['http_gateway_id'];
                    $port = Port::find($data['port_id']);

                    $this->relayObjectService
                        ->createRelayObjectMethods($object->id, $data['http_gateway_id'], $port);

                    $port->update([
                        'object' => $object->id,
                        'comment' => $data['name'],
                        'status' => 'OUT',
                    ]);

                    ConfigMegaService::setPortType($data['http_gateway_id'], $port->num_port, 'OUT');
                    break;
            }

            $relay->id_object = $object->id;
            $relay->save();
        });

        return $relay->id;
    }

    /**
     * Обновление реле.
     *
     * @throws \Throwable
     */
    public function update(Relay $relay, array $data): int
    {
        DB::transaction(function () use (&$relay, $data) {
            if ($relay->name !== trim($data['name'])) {
                $relay->object->name = HomeObject::getUniqueObjectName(
                    $relay->object->id,
                    trim($data['name'])
                );
            }

            $relay->name = trim($data['name']);
            $relay->gateway_id = $data['gateway_id'];

            switch ($relay->gateway_type) {
                case HomeObject::GATEWAY_MODBUS:
                        if ($data['register_id']) {
                            $this->relayObjectService
                                ->updateRelayObjectMethods($relay->object->id, null, null, $data['register_id']);
                        } else {
                            $this->relayObjectService
                                ->updateRelayMethodsWithCurrentRegisters($relay->object, $data);
                        }
                    break;
                case HomeObject::GATEWAY_HTTP:
                    Port::where('object', $relay->object->id)
                        ->update([
                            'object' => null,
                            'method' => null,
                            'status' => 'OUT',
                            'comment' => '',
                        ]);

                    $port = Port::find($data['port_id']);

                    $this->relayObjectService
                        ->updateRelayObjectMethods($relay->object->id, $data['gateway_id'], $port);

                    $port->update([
                        'object' => $relay->object->id,
                        'method' => null,
                        'status' => 'OUT',
                        'comment' => $data['name'],
                    ]);

                    ConfigMegaService::setPortType($data['gateway_id'], $port->num_port, 'OUT');
                    break;
            }

            $relay->object->save();
            $relay->save();

            //Сохраняем данные в таблицу Алисы или включаем запись если она есть уже
            if (isset($data['alice_checkbox'])) {
                AliceDevicesService::addOrReplaceDevice(
                    $relay->object->id,
                    $data['alice_command'],
                    $data['room']
                );
            } else {
                AliceDevicesService::setActive($relay->object->id, 0);
            }
        });

        return $relay->id;
    }
}

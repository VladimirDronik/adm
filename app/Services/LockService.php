<?php

namespace App\Services;

use App\Models\Lock;
use App\Models\Port;
use App\Models\HiteproDev;
use App\Models\HomeObject;
use Illuminate\Support\Facades\DB;
use App\Repositories\PortRepository;
use App\Repositories\DeviceRepository;

class LockService
{
    public function __construct(
        private LockObjectService $lockObjectService
    ) {
    }

    public function prepare(Lock $lock, array $data)
    {
        $lock->name = trim($data['name']);
        if (isset($data['type'])) {
            $lock->type = $data['type'];
        }

        if ($data['place'] == 'port') {
            $lock->port_open = $data['port_id_open'];
            $lock->port_close = $data['port_id_close'];
        } elseif ($data['place'] == 'Hite-pro') {
            $lock->port_open = $data['hitepro_device_open'];
            $lock->port_close = $data['hitepro_device_close'];
        }

        $lock->time = $data['time'];
        $lock->place = $data['place'];
    }

    public function update(Lock $lock, array $data): int
    {
        DB::transaction(function () use (&$lock, $data) {
            if ($this->isUpdateAutoObjectName($lock, $data['name'])) {
                $lock->object->name = HomeObject::getUniqueObjectName(
                    $lock->object->id,
                    trim($data['name'])
                );
                $lock->object->save();
            }
            $lock->id_object = (int) $data['id_object'];
            $this->prepare($lock, $data);
            $lock->save();

            //Сохраняем данные в таблицу Алисы или включаем запись если она есть уже
            if (isset($data['alice_checkbox'])) {
                AliceDevicesService::addOrReplaceDevice(
                    $lock->id_object,
                    $data['alice_command'],
                    $data['room']
                );
            } else {
                AliceDevicesService::setActive($lock->id_object, 0);
            }

            //Удаляем объект из портов контроллера и из устройст хитпро, что бы затем внести заново
            PortService::removeObjectOnPorts($data['id_object']);

            //Если штора находится на портах контроллера, то настраиваем эти порты, иначе штора находится на хитпро
            if ($lock->place == 'port') {
                $idDevice = DeviceRepository::getDevByPort($data['port_id_open']);

                //Настройка контроллера для порта открытия
                if ($data['port_id_open']) {
                    ConfigMegaService::setPortType(
                        $idDevice,
                        PortRepository::getNumberPortByID($data['port_id_open']),
                        'OUT'
                    );

                    PortService::setObjectOnSelectedPort(
                        $data['id_object'], $data['port_id_open'],
                        'OUT',
                        $data['name']
                    );
                }

                //Настройка контроллера для порта закрытия
                if ($data['port_id_close']) {
                    ConfigMegaService::setPortType(
                        $idDevice,
                        PortRepository::getNumberPortByID($data['port_id_close']),
                        'OUT'
                    );

                    PortService::setObjectOnSelectedPort(
                        $data['id_object'],
                        $data['port_id_close'],
                        'OUT',
                        $data['name']
                    );
                }
            } else {
                PortService::setObjectOnHitePro(
                    $data['id_object'],
                    $data['hitepro_device_open']
                );

                PortService::setObjectOnHitePro(
                    $data['id_object'],
                    $data['hitepro_device_close']
                );
            }
        });

        return $lock->id;
    }

    private function isUpdateAutoObjectName(Lock $lock, string $name): bool
    {
        return $lock->name !== trim($name) && $lock->object && $lock->object->is_system;
    }

    public function store(array $data): int
    {
        $lock = new Lock();
        $deviceID = $data['device_id'];
        $this->prepare($lock, $data);

        DB::transaction(function () use (&$lock, $data, $deviceID) {
            $unique_name = HomeObject::getUniqueObjectName(0, $lock->name);
            $object = $this->lockObjectService->createLockObject($unique_name);
            $lock->id_object = $object->id;
            $lock->save();

            $this->lockObjectService->createLockObjectMethods($object->id, $data['device_id']);

            if ($data['place'] == 'port') {
                if ($data['port_id_open']) {
                    PortService::setObjectOnSelectedPort(
                        $object->id,
                        $data['port_id_open'],
                        'OUT',
                        $lock->name
                    );

                    ConfigMegaService::setPortType(
                        $deviceID,
                        PortRepository::getNumberPortByID($data['port_id_open']),
                        'OUT'
                    );
                }

                if ($data['port_id_close']) {
                    PortService::setObjectOnSelectedPort(
                        $object->id,
                        $data['port_id_close'],
                        'OUT',
                        $lock->name
                    );

                    ConfigMegaService::setPortType(
                        $deviceID,
                        PortRepository::getNumberPortByID($data['port_id_close']),
                        'OUT'
                    );
                }
            } elseif ($data['place'] == 'Hite-pro') {
                if ($data['hitepro_device_open']) {
                    HiteproDev::where('id_controller', $data['device_id'])
                        ->where('id', $data['hitepro_device_open'])
                        ->update(['id_object' => $object->id]);
                }

                if ($data['hitepro_device_close']) {
                    HiteproDev::where('id_controller', $data['device_id'])
                        ->where('id', $data['hitepro_device_close'])
                        ->update(['id_object' => $object->id]);
                }
            }
        });

        return $lock->id;
    }

    /**
     * Удаление замка. Если связанный объект системный, то еще удаление объекта и методов,
     * созданных автоматически при создании замка
     *
     * @throws \Throwable
     */
    public function delete(int $id): bool
    {
        $lock = Lock::findOrFail($id);

        PortService::removeObjectOnPorts($lock->id_object);

        Port::where('object', $lock->id_object)
            ->update([
                'object' => null,
                'method' => null,
                'comment' => '',
                'status' => 'OUT',
            ]);

        if ($lock->object && $lock->object->is_system) {
            DB::transaction(function () use (&$lock) {
                HomeObject::deleteAutoObject($lock->id_object);
                $lock->delete();
            });
        } else {
            $lock->delete();
        }

        return true;
    }
}

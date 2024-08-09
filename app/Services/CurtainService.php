<?php
/**
 * Created by PhpStorm.
 * User: kinord
 * Date: 10.05.21
 * Time: 16:16
 */

namespace App\Services;

use App\Models\Port;
use App\Models\Curtain;
use App\Models\HomeObject;
use Illuminate\Support\Facades\DB;
use App\Repositories\PortRepository;

class CurtainService
{
    public function __construct(
        private CurtainObjectService $curtainObjectService
    ) {
    }

    public function prepare(Curtain $curtain, array $data)
    {
        $curtain->name = trim($data['name']);

        if ($data['place'] == Curtain::PLACE_PORT || $data['place'] == Curtain::PLACE_PHASE) {
            $curtain->port_open = $data['port_id_open'];
            $curtain->port_close = $data['port_id_close'];
            $curtain->device_id = $data['device_id'];
        } else {
            $curtain->vendor = $data['vendor'];
            $curtain->type = $data['type'];
            $curtain->address = $data['address'];
            $curtain->group = $data['group'];
            $curtain->bus_id = $data['bus_id'];
            $curtain->is_inverse = array_key_exists('is_inverse', $data);
        }

        $curtain->place = $data['place'];

        if ($data['place'] == Curtain::PLACE_PHASE) {
            $curtain->time = $data['time'];
        }
    }

    public function update(Curtain $curtain, array $data): int
    {
        DB::transaction(function () use (&$curtain, $data) {
            if ($this->isUpdateAutoObjectName($curtain, $data['name'])) {
                $curtain->object->name = HomeObject::getUniqueObjectName(
                    $curtain->object->id,
                    trim($data['name'])
                );
                $curtain->object->save();
            }
            $this->prepare($curtain, $data);
            $curtain->save();

            //Сохраняем данные в таблицу Алисы или включаем запись если она есть уже
            if (isset($data['alice_checkbox'])) {
                AliceDevicesService::addOrReplaceDevice(
                    $curtain->id_object,
                    $data['alice_command'],
                    $data['room']
                );
            } else {
                AliceDevicesService::setActive($curtain->id_object, 0);
            }

            if ($data['place'] == Curtain::PLACE_PORT || $data['place'] == Curtain::PLACE_PHASE) {
                //Удаляем объект из портов контроллера, что бы затем внести заново
                PortService::removeObjectOnPorts($curtain->id_object);

                //Настройка контроллера для порта открытия
                ConfigMegaService::setPortType(
                    $data['device_id'],
                    PortRepository::getNumberPortByID($data['port_id_open']),
                    'OUT'
                );

                PortService::setObjectOnSelectedPort(
                    $curtain->id_object,
                    $data['port_id_open'],
                    'OUT',
                    $data['name']
                );

                //Настройка контроллера для порта закрытия
                ConfigMegaService::setPortType(
                    $data['device_id'],
                    PortRepository::getNumberPortByID($data['port_id_close']),
                    'OUT'
                );

                PortService::setObjectOnSelectedPort(
                    $curtain->id_object,
                    $data['port_id_close'],
                    'OUT',
                    $data['name']
                );
            } else {
                $curtain->update([
                    'address' => $data['address'],
                    'group' => $data['group'],
                ]);
            }
        });

        return $curtain->id;
    }

    private function isUpdateAutoObjectName(Curtain $curtain, string $name): bool
    {
        return $curtain->name !== trim($name) && $curtain->object && $curtain->object->is_system;
    }

    public function store(array $data): int
    {
        $curtain = new Curtain();
        $this->prepare($curtain, $data);

        if ($data['place'] == Curtain::PLACE_RS485) {
            $curtain->active = 1;
        }

        DB::transaction(function () use (&$curtain, $data) {
            $unique_name = HomeObject::getUniqueObjectName(0, $curtain->name);

            $object = $this->curtainObjectService
                ->createCurtainObject($unique_name);

            $curtain->id_object = $object->id;
            $curtain->save();

            if ($data['place'] == Curtain::PLACE_PORT || $data['place'] == Curtain::PLACE_PHASE) {
                $deviceId = $data['device_id'];
                $this->curtainObjectService
                    ->createCurtainObjectMethods($object->id, $data['place']);

                PortService::setObjectOnSelectedPort(
                    $object->id,
                    $data['port_id_open'],
                    'OUT',
                    $curtain->name
                );

                ConfigMegaService::setPortType(
                    $deviceId,
                    PortRepository::getNumberPortByID($data['port_id_open']),
                    'OUT'
                );

                PortService::setObjectOnSelectedPort(
                    $object->id,
                    $data['port_id_close'],
                    'OUT',
                    $curtain->name
                );

                ConfigMegaService::setPortType(
                    $deviceId,
                    PortRepository::getNumberPortByID($data['port_id_close']),
                    'OUT'
                );
            } else {
                $this->curtainObjectService
                    ->createCurtainObjectMethods($object->id, $data['place']);

                $curtain->update([
                    'address' => $data['address'],
                    'group' => $data['group'],
                ]);
            }
        });

        return $curtain->id;
    }

    /**
     * Удаление шторы. Если связанный объект системный, то еще удаление объекта и методов,
     * созданных автоматически при создании шторы
     *
     * @throws \Throwable
     */
    public function delete(int $id): bool
    {
        $curtain = Curtain::findOrFail($id);

        PortService::removeObjectOnPorts($curtain->id_object);

        Port::where('object', $curtain->id_object)
            ->update([
                'object' => null,
                'method' => null,
                'comment' => '',
                'status' => 'OUT',
            ]);

        if ($curtain->object && $curtain->object->is_system) {
            DB::transaction(function () use (&$curtain) {
                HomeObject::deleteAutoObject($curtain->id_object);
                $curtain->delete();
            });
        } else {
            $curtain->delete();
        }

        return true;
    }

    /**
     * Запуск скрипта открытия/закрытия шторы
     *
     * @param int $curtainObjId
     * @param string $newState Доступные значения 'open' или 'close'
     * @return array
     */
    public function setStatus(int $curtainObjId, string $newStatus): array
    {
        $output = [];
        $resultCode = null;

        $scripts = [
            'open' => 'curtain_open.php',
            'close' => 'curtain_close.php',
        ];

        chdir(env('SERVER_FOLDER').'/scripts');
        exec('php ' . $scripts[$newStatus] . ' ' . $curtainObjId, $output, $resultCode);

        return [
            'code' => $resultCode,
            'output' => $output,
        ];
    }

    /**
     * Запуск скрипта установки процента открытия шторы
     *
     * @param int $curtainObjId
     * @param int $newPercent
     * @return array
     */
    public function setPercent(int $curtainObjId, int $newPercent): array
    {
        $output = [];
        $resultCode = null;

        chdir(env('SERVER_FOLDER').'/scripts');
        exec('php curtain_set_percent.php ' . $curtainObjId . ' ' . $newPercent, $output, $resultCode);

        return [
            'code' => $resultCode,
            'output' => $output,
        ];
    }

    /**
     * Запуск скрипта для остановки шторы
     *
     * @param int $curtainObjId
     * @return array
     */
    public function stop(int $curtainObjId): array
    {
        $output = [];
        $resultCode = null;

        chdir(env('SERVER_FOLDER').'/scripts');
        exec('php curtain_stop.php ' . $curtainObjId, $output, $resultCode);

        return [
            'code' => $resultCode,
            'output' => $output,
        ];
    }
}

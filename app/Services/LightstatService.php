<?php
/**
 * Created by PhpStorm.
 * User: kinord
 * Date: 05.05.20
 * Time: 20:28
 */

namespace App\Services;


use App\Models\Lightstat;
use App\Models\HomeObject;
use App\Repositories\PortRepository;
use Illuminate\Support\Facades\DB;
use App\Services\LightstatObjectService;
use App\Models\Port;
use App\Services\PortService;


class LightstatService
{
    private $lightstat_object_service;
    private $portRepository;
    private $portService;

    public function __construct(LightstatObjectService $lightstat_object_service, PortRepository $port_rep,
                                PortService $portService)
    {
        $this->lightstat_object_service = $lightstat_object_service;
        $this->portRepository = $port_rep;
        $this->portService = $portService;
    }


    public function prepare(Lightstat $lightstat, array $data)
    {
        unset($data['object_type']);
        unset($data['device_id']);
        unset($data['port_id']);
        unset($data['placetype_radio']);
        unset($data['place_type']);

        if (($data['room'] ?? 0) == 0) {
            $data['room'] = null;
        }

        $lightstat->fill($data);
    }

    /**
     * Создание светостата. Если $data['type'] === 'auto',
     * то еще создается объект с методом и событием.
     *
     * @param array $data
     * @return int
     * @throws \Throwable
     */
    public function store(array $data): int
    {

        $lightstat = new Lightstat();

        $port_SDA = $data['port_SDA'] ?? null;
        $port_SCL = $data['port_SCL'] ?? null;
        $deviceId = $data['device_id'];

        $this->prepare($lightstat, $data);
        $lightstat->current = 0;

        DB::transaction(function () use (&$lightstat, $port_SDA, $port_SCL, $deviceId) {

            $unique_name = HomeObject::getUniqueObjectName(0, $lightstat->name);
            $object = $this->lightstat_object_service->createLightstatObject($unique_name);
            $this->lightstat_object_service->createLightstatObjectMethodsWithEvents($object->id);
            $lightstat->id_object = $object->id;
            $lightstat->save();

            if ($port_SDA) {
                Port::where('id', $port_SDA)->update(['object' => $object->id, 'status' => 'I2C',
                                                                    'comment' => $lightstat->name]);
                ConfigMegaService::setPortType($deviceId, $this->portRepository->getNumPortByID($port_SDA), 'SDA');
            }

            if ($port_SCL) {
                Port::where('id', $port_SCL)->update(['object' => $object->id, 'status' => 'I2C',
                                                                    'comment' => $lightstat->name]);
                ConfigMegaService::setPortType($deviceId, $this->portRepository->getNumPortByID($port_SCL), 'SCL');

            }
        });

        return $lightstat->id;
    }

    /**
     * Удаление светостата. Если связанный объект системный, то удаление объекта, метода, задачи,
     * созданных автоматически при создании светостата
     *
     * @param int $id
     * @return bool
     * @throws \Throwable
     */
    public function delete(int $id): bool
    {

        $lightstat = Lightstat::findOrFail($id);
        //\Log::error('Ошибка !!! '.$id);

        $deviceAndPort = $this->portService->getIdDeviceAndPortId($lightstat->id_object);

        Port::where('object', $lightstat->id_object)->update(['object' => NULL, 'status' => 'IN',
            'comment' => '']);

        ConfigMegaService::setPortType($deviceAndPort['id_device'], $this->portRepository->getNumPortByID($deviceAndPort['id_port']), 'IN');

        //В портах удаляем все упоминания о термостате, порт переводим в режим IN
        Port::where('object', $lightstat->id_object)->update(['status' => 'IN', 'object' => NULL,
            'comment' => '']);


        if ($lightstat->iobject && $lightstat->iobject->is_system) {
            DB::transaction(function () use (&$lightstat) {
                HomeObject::deleteAutoObject($lightstat->id_object);
                $lightstat->delete();
            });
        } else {
            $lightstat->delete();
        }


        return true;
    }

    private function isUpdateAutoObjectName(Lightstat $lightstat, string $name): bool
    {
        return $lightstat->name !== trim($name) && $lightstat->iobject && $lightstat->iobject->is_system;
    }

    /**
     * Обновление светостата. Если изменилось название и у светостата системный объект, то
     * изменяем название объекта.
     * При этом проверяем на уникальность название объекта. Если неуникально, то добавляем число.
     *
     * @param Lightstat $lightstat
     * @param array $data
     * @return int
     * @throws \Throwable
     */
    public function update(Lightstat $lightstat, array $data): int
    {


        DB::transaction(function () use (&$lightstat, $data) {
            if ($this->isUpdateAutoObjectName($lightstat, $data['name'])) {
                $lightstat->iobject->name = HomeObject::getUniqueObjectName($lightstat->iobject->id, trim($data['name']));
                $lightstat->iobject->save();

            }

            //Убираем датчик портов, если он где-то был до этого
            Port::where('object', $lightstat->id_object)->update(['object' => NULL, 'status' => 'IN',
                'comment' => '']);

            ConfigMegaService::setPortType($data['device_id'], $this->portRepository->getNumPortByID($data['port_SDA']), 'IN');
            ConfigMegaService::setPortType($data['device_id'], $this->portRepository->getNumPortByID($data['port_SCL']), 'IN');


            if($data['placetype'] == 'port') {
                if ($data['port_SDA']) {
                    Port::where('id', $data['port_SDA'])->update(['object' => $lightstat->id_object, 'status' => 'I2C',
                                                                        'comment' => $lightstat->name]);

                    ConfigMegaService::setPortType($data['device_id'], $this->portRepository->getNumPortByID($data['port_SDA']), 'SDA');

                }

                if ($data['port_SCL']) {
                    Port::where('id', $data['port_SCL'])->update(['object' => $lightstat->id_object, 'status' => 'I2C',
                                                                        'comment' => $lightstat->name]);

                    ConfigMegaService::setPortType($data['device_id'], $this->portRepository->getNumPortByID($data['port_SCL']), 'SCL');

                }
            }


            $this->prepare($lightstat, $data);
            $lightstat->save();
        });

        return $lightstat->id;
    }

}
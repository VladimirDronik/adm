<?php
/**
 * Created by PhpStorm.
 * User: kinord
 * Date: 10.05.21
 * Time: 16:16
 */

namespace App\Services;


use App\Models\Curtain;
use App\Models\Port;
use App\Repositories\PortRepository;
use Illuminate\Support\Facades\DB;
use App\Models\HomeObject;
use App\Repositories\DeviceRepository;
use App\Models\HiteproDev;

class CurtainService
{

    private $curtainObjectService;

    public function __construct(CurtainObjectService $service)
    {
        $this->curtainObjectService = $service;

    }

    public function prepare(Curtain $curtain, array $data)
    {

        $curtain->name = trim($data['name']);
        if (isset($data['type'])) {
            $curtain->type = $data['type'];
        }


        if($data['place'] == 'port') {
            $curtain->port_open = $data['port_id_open'];
            $curtain->port_close = $data['port_id_close'];
        } elseif($data['place'] == 'Hite-pro') {
            $curtain->port_open = $data['hitepro_device_open'];
            $curtain->port_close = $data['hitepro_device_close'];
        }


        $curtain->time = $data['time'];
        $curtain->place = $data['place'];
    }



    public function update(Curtain $curtain, array $data): int
    {

        DB::transaction(function () use (&$curtain, $data) {
            if ($this->isUpdateAutoObjectName($curtain, $data['name'])) {
                $curtain->object->name = HomeObject::getUniqueObjectName($curtain->object->id, trim($data['name']));
                $curtain->object->save();
            }
            $curtain->id_object = (int)$data['id_object'];
            $this->prepare($curtain, $data);
            $curtain->save();


            //Сохраняем данные в таблицу Алисы или включаем запись если она есть уже
            if(isset($data['alice_checkbox']))
                AliceDevicesService::addOrReplaceDevice($curtain->id_object, $data['alice_command'], $data['room']);
            else
                AliceDevicesService::setActive($curtain->id_object, 0);


            //Удаляем объект из портов контроллера и из устройст хитпро, что бы затем внести заново
            PortService::removeObjectOnPorts($data['id_object']);

            //Если штора находится на портах контроллера, то настраиваем эти порты, иначе штора находится на хитпро
            if($curtain->place == 'port') {

                $idDevice = DeviceRepository::getDevByPort($data['port_id_open']);

                //Настройка контроллера для порта открытия
                if ($data['port_id_open']) {
                    ConfigMegaService::setPortType($idDevice, PortRepository::getNumberPortByID($data['port_id_open']), 'OUT');
                    PortService::setObjectOnSelectedPort($data['id_object'],$data['port_id_open'],'OUT', $data['name']);
                }

                //Настройка контроллера для порта закрытия
                if ($data['port_id_close']) {
                    ConfigMegaService::setPortType($idDevice, PortRepository::getNumberPortByID($data['port_id_close']), 'OUT');
                    PortService::setObjectOnSelectedPort($data['id_object'],$data['port_id_close'],'OUT', $data['name']);
                }
            } else {
                PortService::setObjectOnHitePro($data['id_object'],$data['hitepro_device_open']);
                PortService::setObjectOnHitePro($data['id_object'],$data['hitepro_device_close']);
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
        $deviceID =  $data['device_id'];
        $this->prepare($curtain, $data);


            DB::transaction(function () use (&$curtain, $data, $deviceID) {
                $unique_name = HomeObject::getUniqueObjectName(0, $curtain->name);
                $object = $this->curtainObjectService->createCurtainObject($unique_name);
                $curtain->id_object = $object->id;
                $curtain->save();

                $this->curtainObjectService->createCurtainObjectMethods($object->id, $data['device_id']);

                if ($data['place'] == 'port') {

                    if ($data['port_id_open']) {
                        PortService::setObjectOnSelectedPort($object->id, $data['port_id_open'],'OUT',$curtain->name);
                        ConfigMegaService::setPortType($deviceID, PortRepository::getNumberPortByID($data['port_id_open']), 'OUT');
                    }

                    if($data['port_id_close']) {
                        PortService::setObjectOnSelectedPort($object->id, $data['port_id_close'],'OUT',$curtain->name);
                        ConfigMegaService::setPortType($deviceID, PortRepository::getNumberPortByID($data['port_id_close']), 'OUT');
                    }

                } elseif ($data['place'] == 'Hite-pro') {

                    if ($data['hitepro_device_open']) {
                        HiteproDev::where('id_controller', $data['device_id'])->where('id', $data['hitepro_device_open'])
                            ->update(['id_object' => $object->id]);
                    }

                    if ($data['hitepro_device_close']) {
                        HiteproDev::where('id_controller', $data['device_id'])->where('id', $data['hitepro_device_close'])
                            ->update(['id_object' => $object->id]);
                    }
                }
            });


        return $curtain->id;
    }


    /**
     * Удаление шторы. Если связанный объект системный, то еще удаление объекта и методов,
     * созданных автоматически при создании шторы
     *
     * @param int $id
     * @return bool
     * @throws \Throwable
     */
    public function delete(int $id): bool
    {
        $curtain = Curtain::findOrFail($id);

        PortService::removeObjectOnPorts($curtain->id_object);

        Port::where('object', $curtain->id_object)->update(['object' => null, 'method' => null,
            'comment' => '', 'status' => 'OUT']);


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

}
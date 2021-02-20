<?php
/**
 * Created by PhpStorm.
 * User: kinord
 * Date: 08.04.20
 * Time: 21:11
 */

namespace App\Services;


use App\Models\Drycontact;
use App\Models\HomeObject;
use App\Services\DryContactObjectService;
use App\Models\Port;
use Illuminate\Support\Facades\DB;
use App\Repositories\PortRepository;

class DrycontactService
{

    private $drycontact_object_service;
    private $objectService;
    private $portRepository;


    public function __construct(DryContactObjectService $drycontact_object_service, ObjectService $objectService,
                                PortRepository $portRepository)
    {
        $this->drycontact_object_service = $drycontact_object_service;
        $this->objectService = $objectService;
        $this->portRepository = $portRepository;
    }

    public function prepareDrycontact(Drycontact $drycontact, array $data)
    {


        $drycontact->name = trim($data['name']);
        $drycontact->id_object = (int)$data['id_object'];
        $drycontact->method_on = $data['method_on'];
        $drycontact->method_off = $data['method_off'];
        $drycontact->param_method_on = $data['method_on_params'];
        $drycontact->param_method_off = $data['method_off_params'];


    }


    public function store(array $data): int
    {

        $deviceID = $data['device_id'];

        $drycontact = new Drycontact();
        $this->prepareDrycontact($drycontact, $data);

        if ($data['object_type'] === 'manual') {
            $drycontact->save();
        } else if ($data['object_type'] === 'auto') {
            DB::transaction(function () use (&$drycontact, $data, $deviceID) {
                $unique_name = HomeObject::getUniqueObjectName(0, $drycontact->name);
                $object = $this->drycontact_object_service->createDrycontactObject($unique_name);
                $drycontact->id_object = $object->id;
               // $idNewMethod = $this->drycontact_object_service->createDryContactObjectMethods($object->id);

                $drycontact->save();

                if ($data['port_id']) {
                    Port::where('id', $data['port_id'])->update(['object' => $object->id, 'method' => null,
                        'status' => 'IN', 'comment' => $data['name']]);
                    ConfigMegaService::setPortType($deviceID, $this->portRepository->getNumPortByID($data['port_id']), 'IN');

                }
            });
        }

        return $drycontact->id;
    }


    private function isUpdateAutoObjectName(Drycontact $drycontact, string $name): bool
    {
        return $drycontact->name !== trim($name) && $drycontact->object && $drycontact->object->is_system;
    }

    /**
     * Обновление сухого контакта. Если изменилось название и у датчика есть системный объект,
     * то изменяем название объекта.
     * При этом проверяем на уникальность название объекта. Если неуникально, то добавляем число.
     *
     * @param array $data
     * @return int
     * @throws \Throwable
     */
    public function update(Drycontact $drycontact, array $data): int
    {


        $deviceID = $data['device_id'];

        DB::transaction(function () use (&$drycontact, $data) {
            if ($this->isUpdateAutoObjectName($drycontact, $data['name'])) {
                $drycontact->object->name = HomeObject::getUniqueObjectName($drycontact->object->id, trim($data['name']));
                $drycontact->object->save();
            }
            $this->prepareDrycontact($drycontact, $data);

            $drycontact->save();
        });

        if ($data['port_id']) {

            Port::where('object', $drycontact->object->id)->update(['object' => null, 'method' => null, 'comment' => '']);
            Port::where('id', $data['port_id'])->update(['object' => $drycontact->object->id,
                'method' => null, 'status' => 'IN', 'comment' => $data['name']]);

            ConfigMegaService::setPortType($deviceID, $this->portRepository->getNumPortByID($data['port_id']), 'IN');

        }

        return $drycontact->id;
    }

    /**
     * Удаление сухого контакта. Если связанный объект системный, то удаление объекта,
     * созданного автоматически при создании сухого контакта
     *
     * @param int $id
     * @return bool
     * @throws \Throwable
     */
    public function delete(int $id): bool
    {
        $drycontact = Drycontact::findOrFail($id);

        Port::where('object', $drycontact->id_object)->update(['object' => null, 'method' => null, 'comment' => '']);

        if ($drycontact->object && $drycontact->object->is_system) {
            DB::transaction(function () use (&$drycontact) {
                //if (!HomeObject::isObjectUsed($switch->id_object, $switch->id, 'switches')) {
                HomeObject::deleteAutoObject($drycontact->id_object);
                //}
                $drycontact->delete();
            });
        } else {
            $drycontact->delete();
        }



        return true;
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: kinord
 * Date: 08.10.20
 * Time: 11:26
 */

namespace App\Services;

use App\Models\Carbmonoxide;
use App\Models\HomeObject;
use App\Models\Port;
use App\Repositories\PortRepository;
use Illuminate\Support\Facades\DB;

class CarbmonoxideService
{
    private $carbmonoxide_object_service;

    private $portRepository;

    public function __construct(CarbmonoxideObjectService $objectService, PortRepository $portRepository)
    {
        $this->carbmonoxide_object_service = $objectService;
        $this->portRepository = $portRepository;
    }

    public function prepare(Carbmonoxide $carbmonoxide, array $data)
    {

        unset($data['device_id']);
        unset($data['port']);

        if (($data['room'] ?? 0) == 0) {
            $data['room'] = null;
        }

        $carbmonoxide->fill($data);
    }

    /**
     * Создание датчика.
     *
     * @throws \Throwable
     */
    public function store(array $data): int
    {

        $carbmonoxide = new Carbmonoxide();

        $port = $data['port'] ?? null;
        $deviceID = $data['device_id'];

        $this->prepare($carbmonoxide, $data);
        $carbmonoxide->cur_value = 0;

        DB::transaction(function () use (&$carbmonoxide, $port, $deviceID) {

            $unique_name = HomeObject::getUniqueObjectName(0, $carbmonoxide->name);
            $object = $this->carbmonoxide_object_service->createCarbmonoxideObject($unique_name);
            $this->carbmonoxide_object_service->createCarbmonoxideObjectMethodsWithEvents($object->id);
            $carbmonoxide->id_object = $object->id;
            $carbmonoxide->save();

            if ($port) {

                Port::where('id', $port)->update(['object' => $object->id, 'method' => null,
                    'status' => 'ADC', 'comment' => $carbmonoxide['name']]);

                ConfigMegaService::setPortType($deviceID, $this->portRepository->getNumPortByID($port), 'ADC');

            }

        });

        return $carbmonoxide->id;
    }

    public function delete(int $id): bool
    {
        $carbmonoxide = Carbmonoxide::findOrFail($id);

        Port::where('object', $carbmonoxide->id_object)->update(['object' => null, 'comment' => '',
            'status' => 'IN']);

        if ($carbmonoxide->iobject && $carbmonoxide->iobject->is_system) {
            DB::transaction(function () use (&$carbmonoxide) {
                HomeObject::deleteAutoObject($carbmonoxide->id_object);
                $carbmonoxide->delete();
            });
        } else {
            $carbmonoxide->delete();
        }

        return true;
    }

    private function isUpdateAutoObjectName(Carbmonoxide $carbmonoxide, string $name): bool
    {
        return $carbmonoxide->name !== trim($name) && $carbmonoxide->iobject && $carbmonoxide->iobject->is_system;
    }

    public function update(Carbmonoxide $carbmonoxide, array $data): int
    {

        DB::transaction(function () use (&$carbmonoxide, $data) {
            if ($this->isUpdateAutoObjectName($carbmonoxide, $data['name'])) {
                $carbmonoxide->iobject->name = HomeObject::getUniqueObjectName($carbmonoxide->iobject->id, trim($data['name']));
                $carbmonoxide->iobject->save();

            }

            if ($data['port']) {

                Port::where('object', $carbmonoxide->id_object)->update(['object' => null, 'comment' => '',
                    'status' => 'IN']);
                Port::where('id', $data['port'])->update(['object' => $carbmonoxide->id_object,
                    'comment' => $data['name'], 'status' => 'ADC']);

                ConfigMegaService::setPortType($data['device_id'], $this->portRepository->getNumPortByID($data['port']), 'ADC');

            }

            $this->prepare($carbmonoxide, $data);
            $carbmonoxide->save();
        });

        return $carbmonoxide->id;
    }
}

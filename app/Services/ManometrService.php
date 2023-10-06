<?php
/**
 * Created by PhpStorm.
 * User: kinord
 * Date: 08.10.20
 * Time: 11:26
 */

namespace App\Services;

use App\Models\HomeObject;
use App\Models\Manometr;
use App\Models\Port;
use App\Repositories\PortRepository;
use Illuminate\Support\Facades\DB;

class ManometrService
{
    public function __construct(
        private ManometrObjectService $manometr_object_service,
        private PortRepository $portRepository
    ) {
    }

    public function prepare(Manometr $manometr, array $data)
    {
        unset($data['device_id']);
        unset($data['port']);

        if (($data['room'] ?? 0) == 0) {
            $data['room'] = null;
        }

        $manometr->fill($data);
    }

    /**
     * Создание датчика.
     *
     * @throws \Throwable
     */
    public function store(array $data): int
    {
        $manometr = new Manometr();

        $port = $data['port'] ?? null;
        $deviceID = $data['device_id'];

        $this->prepare($manometr, $data);
        $manometr->cur_value = 0;

        DB::transaction(function () use (&$manometr, $port, $deviceID) {
            $unique_name = HomeObject::getUniqueObjectName(0, $manometr->name);
            $object = $this->manometr_object_service->createManometrObject($unique_name);
            $this->manometr_object_service->createManometrObjectMethodsWithEvents($object->id);
            $manometr->id_object = $object->id;
            $manometr->save();

            if ($port) {
                Port::where('id', $port)
                    ->update([
                        'object' => $object->id,
                        'method' => null,
                        'status' => 'ADC',
                        'comment' => $manometr['name'],
                    ]);

                ConfigMegaService::setPortType(
                    $deviceID,
                    $this->portRepository->getNumPortByID($port),
                    'ADC'
                );
            }
        });

        return $manometr->id;
    }

    public function delete(int $id): bool
    {
        $manometr = Manometr::findOrFail($id);

        Port::where('object', $manometr->id_object)
            ->update([
                'object' => null,
                'comment' => '',
                'status' => 'IN',
            ]);

        if ($manometr->iobject && $manometr->iobject->is_system) {
            DB::transaction(function () use (&$manometr) {
                HomeObject::deleteAutoObject($manometr->id_object);
                $manometr->delete();
            });
        } else {
            $manometr->delete();
        }

        return true;
    }

    private function isUpdateAutoObjectName(Manometr $manometr, string $name): bool
    {
        return $manometr->name !== trim($name) && $manometr->iobject && $manometr->iobject->is_system;
    }

    public function update(Manometr $manometr, array $data): int
    {
        DB::transaction(function () use (&$manometr, $data) {
            if ($this->isUpdateAutoObjectName($manometr, $data['name'])) {
                $manometr->iobject->name = HomeObject::getUniqueObjectName(
                    $manometr->iobject->id,
                    trim($data['name'])
                );
                $manometr->iobject->save();
            }

            if ($data['port']) {
                Port::where('object', $manometr->id_object)
                    ->update([
                        'object' => null,
                        'comment' => '',
                        'status' => 'IN',
                    ]);

                Port::where('id', $data['port'])
                    ->update([
                        'object' => $manometr->id_object,
                        'comment' => $data['name'],
                        'status' => 'ADC',
                    ]);

                ConfigMegaService::setPortType(
                    $data['device_id'],
                    $this->portRepository->getNumPortByID($data['port']),
                    'ADC'
                );
            }
            $this->prepare($manometr, $data);
            $manometr->save();
        });

        return $manometr->id;
    }
}

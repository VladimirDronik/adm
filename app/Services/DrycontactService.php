<?php

namespace App\Services;

use App\Models\Port;
use App\Models\Drycontact;
use App\Models\HomeObject;
use Illuminate\Support\Facades\DB;
use App\Repositories\PortRepository;

class DrycontactService
{
    public function __construct(
        private DryContactObjectService $drycontactObjectService,
        private ObjectService $objectService,
        private PortRepository $portRepository
    ) {
    }

    public function prepareDrycontact(Drycontact $drycontact, array $data)
    {
        $drycontact->name = trim($data['name']);
        $drycontact->method_on = $data['method_on'];
        $drycontact->method_off = $data['method_off'];
        $drycontact->param_method_on = $data['param_method_on'];
        $drycontact->param_method_off = $data['param_method_off'];
    }

    public function store(array $data): int
    {
        $deviceID = $data['device_id'];

        $drycontact = new Drycontact();
        $this->prepareDrycontact($drycontact, $data);

        DB::transaction(function () use (&$drycontact, $data, $deviceID) {
            $uniqueName = HomeObject::getUniqueObjectName(0, $drycontact->name);
            $object = $this->drycontactObjectService->createDrycontactObject($uniqueName);
            $drycontact->id_object = $object->id;

            $drycontact->save();

            if ($data['port_id']) {
                Port::where('id', $data['port_id'])->update([
                    'object' => $object->id,
                    'method' => null,
                    'status' => 'IN',
                    'comment' => $data['name']
                ]);
                ConfigMegaService::setPortType($deviceID, $this->portRepository->getNumPortByID($data['port_id']), 'IN-P&R');
            }
        });

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
            Port::where('object', $drycontact->object->id)
                ->update([
                    'object' => null,
                    'method' => null,
                    'comment' => '',
                ]);

            Port::where('id', $data['port_id'])
                ->update([
                    'object' => $drycontact->object->id,
                    'method' => null,
                    'status' => 'IN',
                    'comment' => $data['name'],
                ]);

            ConfigMegaService::setPortType(
                $deviceID,
                $this->portRepository->getNumPortByID($data['port_id']),
                'IN-P&R'
            );
        }

        return $drycontact->id;
    }

    /**
     * Удаление сухого контакта. Если связанный объект системный, то удаление объекта,
     * созданного автоматически при создании сухого контакта
     *
     * @throws \Throwable
     */
    public function delete(int $id): bool
    {
        $drycontact = Drycontact::findOrFail($id);

        Port::where('object', $drycontact->id_object)
            ->update([
                'object' => null,
                'method' => null,
                'comment' => '',
            ]);

        if ($drycontact->object && $drycontact->object->is_system) {
            DB::transaction(function () use (&$drycontact) {
                HomeObject::deleteAutoObject($drycontact->id_object);
                $drycontact->delete();
            });
        } else {
            $drycontact->delete();
        }

        return true;
    }
}

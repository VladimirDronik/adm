<?php

namespace App\Http\Controllers;

use App\Http\Requests\Lamp\CreateRequest;
use App\Http\Requests\Lamp\UpdateRequest;
use App\Models\HomeObject;
use App\Models\Lamp;
use App\Models\Port;
use App\Repositories\DeviceRepository;
use App\Repositories\ModbusRepository;
use App\Repositories\ObjectRepository;
use App\Services\LampService;
use App\Services\PortService;
use App\Services\Service;

class LampController extends Controller
{
    public function __construct(
        private ObjectRepository $object_rep,
        private DeviceRepository $deviceRepository,
        private LampService $service,
        private PortService $portService,
        private ModbusRepository $modbusRepository,
    ) {
    }

    public function create()
    {
        $objects = $this->object_rep->getAllToArray();
        $object_types = HomeObject::getFullTypeIds();
        $devices = $this->deviceRepository->getAllToArray();
        $gatewayTypes = HomeObject::getGatewayTypes();
        $modbusSlavers = $this->modbusRepository->getFilteredSlaversToArray(['light', 'relay'], 1);

        return view('lamps.create', compact('objects', 'object_types', 'devices', 'gatewayTypes', 'modbusSlavers'));
    }

    public function store(CreateRequest $r)
    {
        try {
            if ($id = $this->service->store($r->except('_token'))) {
                return redirect()->route('lamps.edit', [$id])
                    ->with('success', 'Лампа успешно добавлена');
            }
        } catch (\Throwable $e) {
            \Log::error('Ошибка при добавлении лампы '.
                json_encode($r->all()).' '.$e->getMessage());
        }

        return back()->withInput($r->all())->with('error', 'Ошибка при добавлении лампы');
    }

    public function update(UpdateRequest $r, int $id)
    {
        $lamp = Lamp::findOrFail($id);

        try {
            if ($this->service->update($lamp, $r->except('_token'))) {
                return redirect()->route('lamps.edit', [$lamp->id])
                    ->with('success', 'Лампа успешно изменена');
            }
        } catch (\Throwable $e) {
            \Log::error('Ошибка при изменении лампы '.$lamp->id
                .' '.json_encode($r->all()).' '.$e->getMessage());
        }

        return back()->withInput($r->all())->with('error', 'Ошибка при изменении лампы');
    }

    public function edit(Lamp $lamp, $tab = 1)
    {
        $can = gates('devices.show-object');
        [$messages, $events, $sounds, $views, $rooms, $scripts, $objects, $object_types, $alice, $allEvents] =
            Service::getListElements($lamp->id_object);

        $messagePoint['first'] = 'При включении';
        $messagePoint['second'] = 'При выключении';

        $availableEvents = Lamp::getEvents();
        $properties = Lamp::getProperties();

        $modbusSlavers = null;
        $devices = null;
        $currentPort = null;
        $methodsIdWithRegisters = [];
        $systemMethods = $lamp->object->methods->whereIn('alias', $lamp->getMethodsAliasByType());

        if ($lamp->gateway_type == HomeObject::GATEWAY_MODBUS) {
            $modbusSlavers = $this->modbusRepository->getFilteredSlaversToArray(['light', 'relay'], 1);

            if ($systemMethods->isNotEmpty()) {
                foreach ($systemMethods as $method) {
                    $methodsIdWithRegisters[$method->id] = $method->register ? $method->register->id : 0;
                }
            }
        } else {
            $devices = $this->deviceRepository->getAllToArray();
            $currentPort = Port::where('object', $lamp->object->id)->first();
        }

        return view('lamps.edit', compact(
            'lamp', 'messagePoint', 'messages', 'properties', 'sounds', 'views', 'methodsIdWithRegisters',
            'rooms', 'objects', 'object_types', 'scripts', 'can', 'tab', 'events', 'devices',
            'alice', 'availableEvents', 'allEvents', 'systemMethods', 'currentPort', 'modbusSlavers'
        ));
    }
}

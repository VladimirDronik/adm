<?php

namespace App\Http\Controllers;

use App\Http\Requests\Relay\CreateRequest;
use App\Http\Requests\Relay\UpdateRequest;
use App\Models\HomeObject;
use App\Models\Port;
use App\Models\Relay;
use App\Repositories\DeviceRepository;
use App\Repositories\ModbusRepository;
use App\Repositories\RelayRepository;
use App\Services\PortService;
use App\Services\RelayService;
use App\Services\Service;

class RelayController extends Controller
{
    public function __construct(
        private RelayRepository $relayRepository,
        private DeviceRepository $deviceRepository,
        private RelayService $service,
        private PortService $portService,
        private ModbusRepository $modbusRepository
    ) {
    }

    public function index()
    {
        $relays = $this->relayRepository->getAll();

        return view('relays.index', compact('relays'));
    }

    public function create()
    {
        $devices = $this->deviceRepository->getAllToArray();
        $gatewayTypes = HomeObject::getGatewayTypes();
        $modbusSlavers = $this->modbusRepository->getAllSlaversToArray();

        return view('relays.create', compact('devices', 'gatewayTypes', 'modbusSlavers'));
    }

    public function store(CreateRequest $r)
    {
        try {
            if ($id = $this->service->store($r->except('_token'))) {
                return redirect()->route('relays.edit', [$id])
                    ->with('success', 'Реле успешно добавлено');
            }
        } catch (\Throwable $e) {
            \Log::error('Ошибка при добавлении реле '.
                json_encode($r->all()).' '.$e->getMessage());
        }

        return back()->withInput($r->all())->with('error', 'Ошибка при добавлении реле');
    }

    public function edit(Relay $relay, $tab = 1)
    {
        $can = gates('devices.show-object');

        [$messages, $events, $sounds, $views, $rooms, $scripts, $objects, $object_types, $alice] =
            Service::getListElements($relay->id_object);

        $messagePoint['first'] = 'При включении';
        $messagePoint['second'] = 'При выключении';

        $availableEvents = Relay::getEvents();
        $properties = Relay::getProperties();

        $allEvents = '';

        $modbusSlavers = null;
        $devices = null;
        $currentPort = null;
        $methodsIdWithRegisters = [];

        if ($relay->gateway_type == HomeObject::GATEWAY_MODBUS) {
            $modbusSlavers = $this->modbusRepository->getAllSlaversToArray();

            if ($relay->object->methods->isNotEmpty()) {
                foreach ($relay->object->methods as $method) {
                    $methodsIdWithRegisters[$method->id] = $method->register ? $method->register->id : 0;
                }
            }
        } else {
            $devices = $this->deviceRepository->getAllToArray();
            $currentPort = Port::where('object', $relay->object->id)->first();
        }

        return view('relays.edit', compact('relay', 'properties', 'events', 'sounds', 'views', 'rooms',
            'devices', 'messagePoint', 'messages', 'alice', 'tab', 'availableEvents', 'currentPort',
            'objects', 'object_types', 'scripts', 'allEvents', 'can', 'modbusSlavers', 'methodsIdWithRegisters'));
    }

    public function update(UpdateRequest $r, Relay $relay)
    {
        try {
            if ($this->service->update($relay, $r->except('_token'))) {
                return redirect()->route('relays.edit', [$relay->id])
                    ->with('success', 'Реле успешно изменено');
            }
        } catch (\Throwable $e) {
            \Log::error('Ошибка при изменении реле '.$relay->id
                .' '.json_encode($r->all()).' '.$e->getMessage());
        }

        return back()->withInput($r->all())->with('error', 'Ошибка при изменении реле');
    }
}

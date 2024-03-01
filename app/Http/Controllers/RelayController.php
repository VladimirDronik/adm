<?php

namespace App\Http\Controllers;

use App\Http\Requests\Relay\CreateRequest;
use App\Http\Requests\Relay\UpdateRequest;
use App\Models\HomeObject;
use App\Models\Relay;
use App\Repositories\DeviceRepository;
use App\Repositories\ObjectRepository;
use App\Repositories\RelayRepository;
use App\Services\PortService;
use App\Services\RelayService;
use App\Services\Service;

class RelayController extends Controller
{
    public function __construct(
        private RelayRepository $relay_rep,
        private ObjectRepository $object_rep,
        private DeviceRepository $device_rep,
        private RelayService $service,
        private PortService $portService,
    ) {
    }

    public function index()
    {
        $relays = $this->relay_rep->getAll();

        return view('relays.index', compact('relays'));
    }

    public function create()
    {
        $objects = $this->object_rep->getAllToArray();
        $object_types = HomeObject::getFullTypeIds();
        $devices = $this->device_rep->getAllToArray();

        return view('relays.create', compact('objects', 'object_types', 'devices'));
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

        [$idDevice, $idPort, $devices, $ports, $hp_device, $hp_devices] = $this->portService->getCurrentDevPort($relay->id_object);

        [$messages, $events, $sounds, $views, $rooms, $scripts, $objects, $object_types, $alice] =
            Service::getListElements($relay->id_object);

        $messagePoint['first'] = 'При включении';
        $messagePoint['second'] = 'При выключении';

        $availableEvents = Relay::getEvents();
        $properties = Relay::getProperties();

        $allEvents = '';

        return view('relays.edit', compact('relay', 'properties', 'events', 'sounds', 'views', 'rooms',
            'idDevice', 'idPort', 'devices', 'ports', 'messagePoint', 'messages', 'alice', 'tab', 'availableEvents',
            'objects', 'object_types', 'scripts', 'hp_device', 'hp_devices', 'allEvents', 'can'));
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

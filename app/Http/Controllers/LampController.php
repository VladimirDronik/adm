<?php

namespace App\Http\Controllers;

use App\Http\Requests\Lamp\CreateRequest;
use App\Http\Requests\Lamp\UpdateRequest;
use App\Models\HomeObject;
use App\Models\Lamp;
use App\Repositories\DeviceRepository;
use App\Repositories\LampRepository;
use App\Repositories\ObjectRepository;
use App\Services\LampService;
use App\Services\PortService;
use App\Services\Service;

class LampController extends Controller
{
    public function __construct(
        private LampRepository $lamp_rep,
        private ObjectRepository $object_rep,
        private DeviceRepository $device_rep,
        private LampService $service,
        private PortService $portService,
    ) {
    }

    public function index()
    {
        $lamps = $this->lamp_rep->getAll();

        return view('lamps.index', compact('lamps'));
    }

    public function create()
    {

        $objects = $this->object_rep->getAllToArray();
        $object_types = HomeObject::getFullTypeIds();
        $devices = $this->device_rep->getAllWithoutTypesToArray(['Hite-pro']);

        return view('lamps.create', compact('objects', 'object_types', 'devices'));
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

        [$idDevice, $idPort, $devices, $ports, $hp_device, $hp_devices] =
            $this->portService->getCurrentDevPort($lamp->id_object);

        [$messages, $events, $sounds, $views, $rooms, $scripts, $objects, $object_types, $alice, $allEvents] =
            Service::getListElements($lamp->id_object);

        $messagePoint['first'] = 'При включении';
        $messagePoint['second'] = 'При выключении';

        $availableEvents = Lamp::getEvents();
        $properties = Lamp::getProperties();

        return view('lamps.edit', compact('lamp',
            'idDevice', 'idPort', 'devices', 'ports', 'messagePoint', 'messages', 'properties', 'sounds', 'views', 'rooms',
            'objects', 'object_types', 'scripts', 'hp_device', 'hp_devices', 'can', 'tab', 'events',
            'alice', 'availableEvents', 'allEvents'));
    }
}

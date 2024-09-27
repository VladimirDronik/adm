<?php

namespace App\Http\Controllers;

use App\Models\Manometr;
use App\Services\Service;
use App\Models\HomeObject;
use App\Services\PortService;
use App\Services\ObjectService;
use App\Services\ManometrService;
use Illuminate\Support\Facades\Log;
use App\Repositories\RoomRepository;
use App\Repositories\DeviceRepository;
use App\Repositories\ObjectRepository;
use App\Repositories\ManometrRepository;
use App\Http\Requests\Manometr\CreateRequest;
use App\Http\Requests\Manometr\UpdateRequest;

class ManometrController extends Controller
{
    public function __construct(
        private ManometrRepository $manometrRep,
        private ObjectRepository $objectRep,
        private RoomRepository $roomRep,
        private DeviceRepository $deviceRep,
        private ManometrService $service,
        private ObjectService $objectService,
        private PortService $portService,
    ) {
    }

    public function index()
    {
        $manometrs = $this->manometrRep->getAll();

        return view('manometr.index', compact('manometrs'));
    }

    private function getLists()
    {
        $objects = $this->objectRep->getAllToArray();
        $rooms = $this->roomRep->getAllToArray();
        $devices = $this->deviceRep->getAllWithoutTypesToArray(['Hite-pro']);

        return [$objects, $rooms, $devices];
    }

    public function create()
    {
        [$objects, $rooms, $devices] = $this->getLists();
        $object_types = HomeObject::getFullTypeIds();
        $can = gates('devices.show-object');

        return view('manometr.create', compact(
            'objects', 'rooms', 'devices', 'object_types', 'can'
        ));
    }

    public function store(CreateRequest $r)
    {
        try {
            if ($id = $this->service->store($r->except('_token'))) {
                return redirect()
                    ->route('manometr.edit', [$id])
                    ->with('success', 'Манометр успешно добавлен');
            }
        } catch (\Throwable $e) {
            Log::error(
                'Ошибка при добавлении манометра '
                .json_encode($r->all()).' '.$e->getMessage()
            );
        }

        return back()->withInput($r->all())
            ->with('error', 'Ошибка при добавлении манометра');
    }

    public function edit(Manometr $manometr, int $tab = 1)
    {
        [$objects, $rooms, $devices] = $this->getLists();

        $low_methods = $this->objectService->getMethodsByObjectIdToArray($manometr->low_object);
        $high_methods = $this->objectService->getMethodsByObjectIdToArray($manometr->low_object);

        $can = gates('devices.show-object');

        $deviceAndPort = $this->portService->getIdDeviceAndPortId($manometr->id_object);

        $deviceId = $deviceAndPort['id_device'];
        $port = $deviceAndPort['id_port'];

        $ports = $this->portService->getPortsIntoList($deviceId, 'IN,I2C,1WIRE,1W-BUS,ADC');

        [$messages, $events, $sounds, $views, $rooms, $scripts, $objects, $object_types, $alice, $allEvents] =
            Service::getListElements($manometr->id_object);

        $availableEvents = Manometr::getEvents();
        $properties = Manometr::getProperties();

        $messagePoint['first'] = 'При нижнем пороге';
        $messagePoint['second'] = 'При верхнем пороге';

        return view('manometr.edit', compact(
            'manometr', 'objects', 'rooms', 'properties',
            'devices', 'low_methods', 'high_methods', 'object_types', 'messages',
            'events', 'sounds', 'views', 'scripts', 'allEvents', 'availableEvents',
            'scripts', 'deviceId', 'ports', 'messagePoint', 'port', 'tab', 'can'
        ));
    }

    public function update(UpdateRequest $r, Manometr $manometr)
    {
        try {
            if ($this->service->update($manometr, $r->except('_token'))) {
                return redirect()
                    ->route('manometr.edit', [$manometr->id])
                    ->with('success', 'Манометр успешно изменен');
            }
        } catch (\Throwable $e) {
            Log::error(
                'Ошибка при изменении манометра '.$manometr->id
                .' '.json_encode($r->all()).' '.$e->getMessage()
            );
        }

        return back()->withInput($r->all())
            ->with('error', 'Ошибка при изменении манометра');
    }
}

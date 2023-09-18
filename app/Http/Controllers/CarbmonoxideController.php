<?php

namespace App\Http\Controllers;

use App\Http\Requests\Carbmonoxide\CreateRequest;
use App\Http\Requests\Carbmonoxide\UpdateRequest;
use App\Models\Carbmonoxide;
use App\Models\HomeObject;
use App\Repositories\CarbmonoxideRepository;
use App\Repositories\DeviceRepository;
use App\Repositories\ObjectRepository;
use App\Repositories\RoomRepository;
use App\Repositories\ScriptRepository;
use App\Services\CarbmonoxideService;
use App\Services\MessageService;
use App\Services\ObjectService;
use App\Services\PortService;
use App\Services\Service;

class CarbmonoxideController extends Controller
{
    public function __construct(
        private CarbmonoxideRepository $carbmonoxideRepository,
        private ObjectRepository $objectRepository,
        private RoomRepository $roomRepository,
        private DeviceRepository $deviceRepository,
        private CarbmonoxideService $service,
        private ObjectService $objectService,
        private ScriptRepository $scriptRepository,
        private PortService $portService,
        private MessageService $messageService,
    ) {
    }

    public function index()
    {

        $carbmonoxides = $this->carbmonoxideRepository->getAll();

        return view('carbmonoxide.index', compact('carbmonoxides'));
    }

    private function getLists()
    {
        $objects = $this->objectRepository->getAllToArray();
        $rooms = $this->roomRepository->getAllToArray();
        $devices = $this->deviceRepository->getAllWithoutTypesToArray(['Hite-pro']);

        return [$objects, $rooms, $devices];
    }

    public function create()
    {

        [$objects, $rooms, $devices] = $this->getLists();
        $object_types = HomeObject::getFullTypeIds();
        $can = gates('devices.show-object');

        return view('carbmonoxide.create', compact('objects', 'rooms', 'devices', 'object_types', 'can'));
    }

    public function store(CreateRequest $r)
    {
        try {
            if ($id = $this->service->store($r->except('_token'))) {
                return redirect()->route('carbmonoxide.edit', [$id])
                    ->with('success', 'Датчик УГ успешно добавлен');
            }
        } catch (\Throwable $e) {
            \Log::error('Ошибка при добавлении датчика УГ '.json_encode($r->all()).' '.$e->getMessage());
        }

        return back()->withInput($r->all())->with('error', 'Ошибка при добавлении датчика УГ');

    }

    public function edit(Carbmonoxide $carbmonoxide, $tab = 1)
    {
        [$objects, $rooms, $devices] = $this->getLists();

        $low_methods = $this->objectService->getMethodsByObjectIdToArray($carbmonoxide->low_object);
        $high_methods = $this->objectService->getMethodsByObjectIdToArray($carbmonoxide->low_object);

        $can = gates('devices.show-object');

        $deviceAndPort = $this->portService->getIdDeviceAndPortId($carbmonoxide->id_object);

        $deviceId = $deviceAndPort['id_device'];
        $port = $deviceAndPort['id_port'];

        $ports = $this->portService->getPortsIntoList($deviceId, 'IN,I2C,1WIRE,1W-BUS,ADC');

        [$messages, $events, $sounds, $views, $rooms, $scripts, $objects, $object_types, $alice, $allEvents] =
            Service::getListElements($carbmonoxide->id_object);

        $messagePoint['first'] = 'При нижнем пороге';
        $messagePoint['second'] = 'При верхнем пороге';

        $availableEvents = Carbmonoxide::getEvents();
        $properties = Carbmonoxide::getProperties();

        return view('carbmonoxide.edit', compact('carbmonoxide', 'objects', 'rooms',
            'devices', 'low_methods', 'high_methods', 'object_types', 'messages', 'events', 'sounds', 'views',
            'allEvents', 'availableEvents', 'properties',
            'scripts', 'deviceId', 'ports', 'messagePoint', 'port', 'tab', 'can'));
    }

    public function update(UpdateRequest $r, Carbmonoxide $carbmonoxide)
    {
        try {
            if ($this->service->update($carbmonoxide, $r->except('_token'))) {
                return redirect()->route('carbmonoxide.edit', [$carbmonoxide->id])->with('success', 'Датчик УГ успешно изменен');
            }
        } catch (\Throwable $e) {
            \Log::error('Ошибка при изменении датчика УГ '.$carbmonoxide->id.' '.json_encode($r->all()).' '.$e->getMessage());
        }

        return back()->withInput($r->all())->with('error', 'Ошибка при изменении датчика УГ');
    }
}

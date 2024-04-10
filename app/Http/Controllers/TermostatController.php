<?php

namespace App\Http\Controllers;

use App\Http\Requests\Termostat\CreateRequest;
use App\Http\Requests\Termostat\UpdateRequest;
use App\Models\HomeObject;
use App\Models\Termostat;
use App\Repositories\DeviceRepository;
use App\Repositories\EventRepository;
use App\Repositories\ObjectRepository;
use App\Repositories\RoomRepository;
use App\Repositories\ScriptRepository;
use App\Repositories\SoundRepository;
use App\Repositories\TermostatRepository;
use App\Repositories\UsensorRepository;
use App\Repositories\ViewRepository;
use App\Services\MessageService;
use App\Services\ObjectService;
use App\Services\PortService;
use App\Services\TermostatService;
use Illuminate\Support\Facades\Log;

class TermostatController extends Controller
{
    public function __construct(
        private TermostatRepository $termostat_rep,
        private ObjectRepository $object_rep,
        private DeviceRepository $device_rep,
        private UsensorRepository $usensor_rep,
        private RoomRepository $room_rep,
        private TermostatService $service,
        private EventRepository $event_rep,
        private ViewRepository $view_rep,
        private ObjectService $object_service,
        private ScriptRepository $script_rep,
        private PortService $portService,
        private MessageService $messageService,
    ) {
    }

    public function index()
    {
        $termostats = $this->termostat_rep->getAll();

        return view('termostats.index', compact('termostats'));
    }

    private function getLists()
    {
        $objects = $this->object_rep->getAllToArray();
        $rooms = $this->room_rep->getAllToArray();
        $types = Termostat::getFullThermostatIds();
        $devices = $this->device_rep->getAllWithoutTypesToArray(['Hite-pro']);
        $usensors = $this->usensor_rep->getAllToArray();

        return [$objects, $rooms, $types, $devices, $usensors];
    }

    public function create()
    {
        list($objects, $rooms, $types, $devices, $usensors) = $this->getLists();
        $object_types =  HomeObject::getFullTypeIds();
        $can = gates('devices.show-object');
        $tab = 1;

        return view('termostats.create', compact('objects','rooms', 'types', 'devices',
            'usensors', 'object_types', 'can', 'tab'));
    }

    public function store(CreateRequest $r)
    {
        try {
            if ($id = $this->service->store($r->except('_token'))) {
                return redirect()->route('termostats.edit', [$id])
                    ->with('success', 'Датчик температуры успешно добавлен');
            }
        } catch (\Throwable $e) {
            Log::error('Ошибка при добавлении датчика температуры '.json_encode($r->all()).' '.$e->getMessage());
        }

        return back()->withInput($r->all())->with('error', 'Ошибка при добавлении датчика температуры');
    }

    public function edit(Termostat $termostat, $tab = 1)
    {
        list($objects, $rooms, $types, $devices, $usensors) = $this->getLists();

        $methods = $this->object_service->getMethodsByObjectIdToArray($termostat->object);
        $object_types = HomeObject::getFullTypeIds();
        $scripts = $this->script_rep->getAllToArray();
        $can = gates('devices.show-object');

        $deviceAndPort = $this->portService->getIdDeviceAndPortId($termostat->id_object);

        $deviceId = $deviceAndPort['id_device'];
        $portId = $deviceAndPort['id_port'];

        $ports = $this->portService->getPortsIntoList($deviceId, 'IN,I2C,1WIRE,1W-BUS,ADC');

        $messages = $this->messageService->getNotifications($termostat->id_object);

        $id_controller = $this->portService->getIdControllerBySubdevice($termostat->subdev_id, 'Hite-pro');
        $subdevs = $this->portService->getSubdevsForController($id_controller, 'Hite-pro', 'temperature');

        $messagePoint['first'] = 'При включении';
        $messagePoint['second'] = 'При выключении';

        $events = $this->event_rep->getAllById($termostat->id_object);
        $availableEvents = Termostat::getEvents();
        $properties = Termostat::getProperties();
        $sounds = SoundRepository::getAllToArray();
        $views = $this->view_rep->getAllToArray();
        $allEvents = '';

        return view('termostats.edit', compact('termostat', 'objects', 'rooms',
            'types', 'devices', 'methods', 'object_types', 'scripts', 'id_controller',
            'subdevs', 'usensors', 'deviceId', 'portId', 'ports', 'messages', 'messagePoint', 'can', 'tab', 'events',
            'availableEvents', 'properties', 'sounds', 'views', 'allEvents'));
    }

    public function update(UpdateRequest $r, Termostat $termostat)
    {
        try {
            if ($this->service->update($termostat, $r->except('_token'))) {
                return redirect()->route('termostats.edit', [$termostat->id])->with('success', 'Датчик температуры успешно изменен');
            }
        } catch (\Throwable $e) {
            Log::error('Ошибка при изменении датчика температуры '.$termostat->id.' '.json_encode($r->all()).' '.$e->getMessage());
        }

        return back()->withInput($r->all())->with('error', 'Ошибка при изменении датчика температуры');
    }
}

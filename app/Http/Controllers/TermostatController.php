<?php

namespace App\Http\Controllers;

use App\Models\Termostat;
use App\Models\HomeObject;
use App\Services\PortService;
use App\Services\ObjectService;
use App\Services\MessageService;
use App\Services\TermostatService;
use Illuminate\Support\Facades\Log;
use App\Repositories\RoomRepository;
use App\Repositories\ViewRepository;
use App\Repositories\EventRepository;
use App\Repositories\SoundRepository;
use App\Repositories\DeviceRepository;
use App\Repositories\ObjectRepository;
use App\Repositories\ScriptRepository;
use App\Repositories\UsensorRepository;
use App\Repositories\TermostatRepository;
use App\Http\Requests\Termostat\CreateRequest;
use App\Http\Requests\Termostat\UpdateRequest;

class TermostatController extends Controller
{
    public function __construct(
        private TermostatRepository $termostatRep,
        private ObjectRepository $objectRep,
        private DeviceRepository $deviceRep,
        private UsensorRepository $usensorRep,
        private RoomRepository $roomRep,
        private TermostatService $service,
        private EventRepository $eventRep,
        private ViewRepository $viewRep,
        private ObjectService $objectService,
        private ScriptRepository $scriptRep,
        private PortService $portService,
        private MessageService $messageService,
    ) {
    }

    public function index()
    {
        $termostats = $this->termostatRep->getAll();

        return view('termostats.index', compact('termostats'));
    }

    private function getLists()
    {
        $objects = $this->objectRep->getAllToArray();
        $rooms = $this->roomRep->getAllToArray();
        $types = Termostat::getFullThermostatIds();
        $devices = $this->deviceRep->getAllWithoutTypesToArray(['Hite-pro']);
        $usensors = $this->usensorRep->getAllToArray();

        return [$objects, $rooms, $types, $devices, $usensors];
    }

    public function create()
    {
        [$objects, $rooms, $types, $devices, $usensors] = $this->getLists();
        $object_types = HomeObject::getFullTypeIds();
        $can = gates('devices.show-object');
        $tab = 1;

        return view('termostats.create', compact(
            'objects', 'rooms', 'types', 'devices',
            'usensors', 'object_types', 'can', 'tab'
        ));
    }

    public function store(CreateRequest $r)
    {
        try {
            if ($id = $this->service->store($r->except('_token'))) {
                return redirect()
                    ->route('termostats.edit', [$id])
                    ->with('success', 'Датчик температуры успешно добавлен');
            }
        } catch (\Throwable $e) {
            Log::error(
                'Ошибка при добавлении датчика температуры '
                .json_encode($r->all()).' '.$e->getMessage()
            );
        }

        return back()->withInput($r->all())
            ->with('error', 'Ошибка при добавлении датчика температуры');
    }

    public function edit(Termostat $termostat, int $tab = 1)
    {
        [$objects, $rooms, $types, $devices, $usensors] = $this->getLists();

        $methods = $this->objectService->getMethodsByObjectIdToArray($termostat->object);
        $object_types = HomeObject::getFullTypeIds();
        $scripts = $this->scriptRep->getAllToArray();
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

        $events = $this->eventRep->getAllById($termostat->id_object);
        $availableEvents = Termostat::getEvents();
        $properties = Termostat::getProperties();
        $sounds = SoundRepository::getAllToArray();
        $views = $this->viewRep->getAllToArray();
        $allEvents = '';

        return view('termostats.edit', compact(
            'termostat', 'objects', 'rooms',
            'types', 'devices', 'methods', 'object_types', 'scripts', 'id_controller',
            'subdevs', 'usensors', 'deviceId', 'portId', 'ports', 'messages', 'messagePoint',
            'availableEvents', 'properties', 'sounds', 'views', 'allEvents', 'can', 'tab', 'events',
        ));
    }

    public function update(UpdateRequest $r, Termostat $termostat)
    {
        try {
            if ($this->service->update($termostat, $r->except('_token'))) {
                return redirect()
                    ->route('termostats.edit', [$termostat->id])
                    ->with('success', 'Датчик температуры успешно изменен');
            }
        } catch (\Throwable $e) {
            Log::error(
                'Ошибка при изменении датчика температуры '.$termostat->id
                .' '.json_encode($r->all()).' '.$e->getMessage()
            );
        }

        return back()->withInput($r->all())
            ->with('error', 'Ошибка при изменении датчика температуры');
    }
}

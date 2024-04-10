<?php

namespace App\Http\Controllers;

use App\Http\Requests\Lightstat\CreateRequest;
use App\Http\Requests\Lightstat\UpdateRequest;
use App\Models\HomeObject;
use App\Models\Lightstat;
use App\Repositories\DeviceRepository;
use App\Repositories\LightstatRepository;
use App\Repositories\ObjectRepository;
use App\Repositories\RoomRepository;
use App\Repositories\ScriptRepository;
use App\Repositories\UsensorRepository;
use App\Services\LightstatService;
use App\Services\MessageService;
use App\Services\ObjectService;
use App\Services\PortService;
use App\Services\Service;

class LightstatController extends Controller
{
    public function __construct(
        private LightstatRepository $lightstat_rep,
        private ObjectRepository $object_rep,
        private DeviceRepository $device_rep,
        private UsensorRepository $usensor_rep,
        private RoomRepository $room_rep,
        private LightstatService $service,
        private ObjectService $object_service,
        private ScriptRepository $script_rep,
        private PortService $portsService,
        private MessageService $messageService,
    ) {
    }

    public function index()
    {

        $lightstats = $this->lightstat_rep->getAll();

        return view('lightstats.index', compact('lightstats'));
    }

    public function store(CreateRequest $r)
    {
        try {
            if ($id = $this->service->store($r->except('_token'))) {
                return redirect()->route('lightstats.edit', [$id])
                    ->with('success', 'Датчик освещенности успешно добавлен');
            }
        } catch (\Throwable $e) {
            \Log::error('Ошибка при добавлении датчика освещенности '.json_encode($r->all()).' '.$e->getMessage());
        }

        return back()->withInput($r->all())->with('error', 'Ошибка при добавлении датчика освещенности');

    }

    private function getLists()
    {
        $objects = $this->object_rep->getAllToArray();
        $rooms = $this->room_rep->getAllToArray();
        $types = Lightstat::getFullLigtstatIds();
        $devices = $this->device_rep->getAllWithoutTypesToArray(['Hite-pro']);
        $usensors = $this->usensor_rep->getAllToArray();

        return [$objects, $rooms, $types, $devices, $usensors];
    }

    public function edit(Lightstat $lightstat, $tab = 1)
    {
        [$objects, $rooms, $types, $devices, $usensors] = $this->getLists();

        $methods = $this->object_service->getMethodsByObjectIdToArray($lightstat->object);
        $can = gates('devices.show-object');

        $deviceAndPort = $this->portsService->getIdDeviceAndPortId($lightstat->id_object);

        $deviceId = $deviceAndPort['id_device'];
        $port_SCL = $lightstat->port_SCL;
        $port_SDA = $lightstat->port_SDA;

        $portsSCL = $this->portsService->getPortsIntoList($deviceId, 'IN,I2C,1WIRE,1W-BUS,ADC');
        $portsSDA = $this->portsService->getPortsIntoList($deviceId, 'IN,I2C,1WIRE,1W-BUS,ADC');

        [$messages, $events, $sounds, $views, $rooms, $scripts, $objects, $object_types, $alice, $allEvents] =
            Service::getListElements($lightstat->id_object);

        $messagePoint['first'] = 'При включении';
        $messagePoint['second'] = 'При выключении';

        $availableEvents = Lightstat::getEvents();
        $properties = Lightstat::getProperties();

        return view('lightstats.edit', compact('lightstat', 'objects', 'rooms', 'tab',
            'types', 'devices', 'methods', 'object_types', 'messages', 'events', 'allEvents', 'sounds', 'views',
            'availableEvents', 'properties',
            'scripts', 'usensors', 'deviceId', 'portsSCL', 'portsSDA', 'port_SCL', 'messagePoint', 'port_SDA', 'can'));
    }

    public function create()
    {

        [$objects, $rooms, $types, $devices, $usensors] = $this->getLists();
        $object_types = HomeObject::getFullTypeIds();
        $can = gates('devices.show-object');
        $tab = 1;

        return view('lightstats.create', compact('objects', 'rooms', 'types', 'devices', 'usensors',
            'object_types', 'tab', 'can'));
    }

    public function update(UpdateRequest $r, Lightstat $lightstat)
    {
        try {
            if ($this->service->update($lightstat, $r->except('_token'))) {
                return redirect()->route('lightstats.edit', [$lightstat->id])->with('success', 'Датчик освещенности успешно изменен');
            }
        } catch (\Throwable $e) {
            \Log::error('Ошибка при изменении датчика освещенности '.$lightstat->id.' '.json_encode($r->all()).' '.$e->getMessage());
        }

        return back()->withInput($r->all())->with('error', 'Ошибка при изменении датчика освещенности');
    }
}

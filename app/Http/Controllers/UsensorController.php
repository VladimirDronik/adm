<?php

namespace App\Http\Controllers;

use App\Http\Requests\Usensor\CreateRequest;
use App\Http\Requests\Usensor\UpdateRequest;
use App\Models\HomeObject;
use App\Models\Usensor;
use App\Repositories\DeviceRepository;
use App\Repositories\ObjectRepository;
use App\Repositories\RoomRepository;
use App\Repositories\ScriptRepository;
use App\Repositories\UsensorRepository;
use App\Services\ObjectService;
use App\Services\PortService;
use App\Services\UsensorService;

class UsensorController extends Controller
{
    public function __construct(
        private UsensorRepository $usensor_rep,
        private ObjectRepository $object_rep,
        private DeviceRepository $device_rep,
        private RoomRepository $room_rep,
        private UsensorService $service,
        private PortService $portService,
        private ScriptRepository $script_rep,
        private PortService $portsService,
        private ObjectService $object_service,
    ) {
    }

    public function index()
    {
        $usensors = $this->usensor_rep->getAll();

        return view('usensors.index', compact('usensors'));
    }

    private function getLists()
    {
        $objects = $this->object_rep->getAllToArray();
        $rooms = $this->room_rep->getAllToArray();
        $devices = $this->device_rep->getAllWithoutTypesToArray(['Hite-pro']);

        return [$objects, $rooms, $devices];
    }

    public function create()
    {
        [$objects, $rooms, $devices] = $this->getLists();
        $object_types = HomeObject::getFullTypeIds();
        $can = gates('devices.show-object');
        $types = Usensor::getTypes(true);

        return view('usensors.create', compact('objects', 'rooms', 'devices', 'object_types', 'can', 'types'));
    }

    public function store(CreateRequest $r)
    {
        try {
            if ($id = $this->service->store($r->except('_token'))) {
                return redirect()->route('usensors.index')
                    ->with('success', 'Универсальный датчик успешно добавлен');
            }
        } catch (\Throwable $e) {
            \Log::error('Ошибка при добавлении универсального датчика '.json_encode($r->all()).' '.$e->getMessage());
        }

        return back()->withInput($r->all())->with('error', 'Ошибка при добавлении универсального датчика');
    }

    public function edit(Usensor $usensor)
    {
        [$objects, $rooms, $devices] = $this->getLists();

        $methods = $this->object_service->getMethodsByObjectIdToArray($usensor->object);
        $object_types = HomeObject::getFullTypeIds();
        $scripts = $this->script_rep->getAllToArray();
        $can = gates('devices.show-object');
        $SCL = $SDA = $this->portService->getPortsIntoList($usensor->device_id, 'IN,I2C,1WIRE,1W-BUS,ADC');
        $types = Usensor::getTypes(true);

        return view('usensors.edit', compact('usensor', 'objects', 'rooms',
            'devices', 'methods', 'object_types', 'scripts', 'can', 'SCL', 'SDA', 'types'));
    }

    public function update(UpdateRequest $r, Usensor $usensor)
    {
        try {
            if ($this->service->update($usensor, $r->except('_token'))) {
                return redirect()->route('usensors.edit', [$usensor->id])->with('success', 'Универсальный датчик успешно изменен');
            }
        } catch (\Throwable $e) {
            \Log::error('Ошибка при изменении универсального датчика '.$usensor->id.' '.json_encode($r->all()).' '.$e->getMessage());
        }

        return back()->withInput($r->all())->with('error', 'Ошибка при изменении универсального датчика');
    }
}

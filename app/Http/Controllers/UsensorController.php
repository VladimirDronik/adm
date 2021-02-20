<?php

namespace App\Http\Controllers;

use App\Models\Usensor;
use Illuminate\Http\Request;
use App\Http\Requests\Usensor\CreateRequest;
use App\Http\Requests\Usensor\UpdateRequest;
use App\Models\HomeObject;
use App\Repositories\DeviceRepository;
use App\Repositories\ObjectRepository;
use App\Repositories\RoomRepository;
use App\Repositories\UsensorRepository;
use App\Repositories\ScriptRepository;
use App\Services\UsensorService;
use App\Services\ObjectService;
use App\Services\PortService;




class UsensorController extends Controller
{

    private $usensor_rep;
    private $object_rep;
    private $device_rep;
    private $room_rep;
    private $service;
    private $portService;

    public function __construct(UsensorRepository $usensor_rep, ObjectRepository $object_rep,
                                DeviceRepository $device_rep, RoomRepository $room_rep, UsensorService $service,
                                PortService $port_service)
    {
        $this->usensor_rep = $usensor_rep;
        $this->object_rep = $object_rep;
        $this->device_rep = $device_rep;
        $this->room_rep = $room_rep;
        $this->service = $service;
        $this->portService = $port_service;
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
        $devices = $this->device_rep->getAllToArray();

        return [$objects, $rooms, $devices];
    }

    public function create()
    {
        list($objects, $rooms, $devices) = $this->getLists();
        $object_types =  HomeObject::getFullTypeIds();
        $can = gates('devices.show-object');

        return view('usensors.create', compact('objects','rooms', 'devices', 'object_types', 'can'));
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


    public function edit(Usensor $usensor, ObjectService $object_service, ScriptRepository $script_rep, PortService $portService)
    {
        list($objects, $rooms, $devices) = $this->getLists();

        $methods = $object_service->getMethodsByObjectIdToArray($usensor->object);
        $object_types = HomeObject::getFullTypeIds();
        $scripts = $script_rep->getAllToArray();
        $can = gates('devices.show-object');
        $SCL = $SDA = $portService->getPortsIntoList($usensor->device_id, 'IN,I2C,1WIRE,1W-BUS,ADC');




        return view('usensors.edit', compact('usensor', 'objects', 'rooms',
            'devices', 'methods', 'object_types', 'scripts', 'can', 'SCL', 'SDA'));
    }

    public function update(UpdateRequest $r, Usensor $usensor)
    {
        try {
            if ($this->service->update($usensor, $r->except('_token'))) {
                return redirect()->route('usensors.edit', [$usensor->id])->with('success','Универсальный датчик успешно изменен');
            }
        } catch (\Throwable $e) {
            \Log::error('Ошибка при изменении универсального датчика '.$usensor->id.' ' .json_encode($r->all()).' '.$e->getMessage());
        }

        return back()->withInput($r->all())->with('error', 'Ошибка при изменении универсального датчика');
    }
}

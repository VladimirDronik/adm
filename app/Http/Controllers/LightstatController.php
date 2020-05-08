<?php

namespace App\Http\Controllers;

use App\Http\Requests\Lightstat\CreateRequest;
use App\Models\Lightstat;
use App\Repositories\DeviceRepository;
use App\Repositories\LightstatRepository;
use App\Repositories\ObjectRepository;
use App\Repositories\RoomRepository;
use App\Repositories\UsensorRepository;
use App\Services\LightstatService;
use App\Models\HomeObject;
use App\Services\ObjectService;
use App\Repositories\ScriptRepository;
use App\Services\PortService;
use App\Http\Requests\Lightstat\UpdateRequest;


class LightstatController extends Controller
{
    private $lightstat_rep;
    private $object_rep;
    private $device_rep;
    private $usensors_rep;
    private $room_rep;
    private $service;


    public function __construct(LightstatRepository $lighstat_rep, ObjectRepository $object_rep,
                                DeviceRepository $device_rep, UsensorRepository $usensor_rep,
                                RoomRepository $room_rep, LightstatService $service)
    {
        $this->lightstat_rep = $lighstat_rep;
        $this->object_rep = $object_rep;
        $this->device_rep = $device_rep;
        $this->usensors_rep = $usensor_rep;
        $this->room_rep = $room_rep;
        $this->service = $service;
    }

    public function index()
    {

        $lightstats = $this->lightstat_rep->getAll();

        return view('lightstats.index', compact('lightstats'));
    }

    public function delete()
    {

    }

    public function store(CreateRequest $r)
    {
        try {
            if ($id = $this->service->store($r->except('_token'))) {
                return redirect()->route('lightstats.edit', [$id])
                    ->with('success', 'Светостат успешно добавлен');
            }
        } catch (\Throwable $e) {
            \Log::error('Ошибка при добавлении светостата '.json_encode($r->all()).' '.$e->getMessage());
        }

        return back()->withInput($r->all())->with('error', 'Ошибка при добавлении светостата');

    }

    private function getLists()
    {
        $objects = $this->object_rep->getAllToArray();
        $rooms = $this->room_rep->getAllToArray();
        $types = Lightstat::getFullLigtstatIds();
        $devices = $this->device_rep->getAllToArray();
        $usensors = $this->usensors_rep->getAllToArray();

        return [$objects, $rooms, $types, $devices, $usensors];
    }

    public function edit(Lightstat $lightstat, ObjectService $object_service, ScriptRepository $script_rep,
                         PortService $portsService)
    {
        list($objects, $rooms, $types, $devices, $usensors) = $this->getLists();


        $methods = $object_service->getMethodsByObjectIdToArray($lightstat->object);
        $object_types = HomeObject::getFullTypeIds();
        $scripts = $script_rep->getAllToArray();
        $can = gates('devices.show-object');

        $deviceAndPort = $portsService->getIdDeviceAndPortId($lightstat->id_object);

        $deviceId = $deviceAndPort['id_device'];
        //$portId = $deviceAndPort['id_port'];
        $port_SCL = $lightstat->port_SCL;
        $port_SDA = $lightstat->port_SDA;

        $portsSCL =  $portsService->getPortsIntoList($deviceId, 'I2C');
        $portsSDA =  $portsService->getPortsIntoList($deviceId, 'I2C');

        return view('lightstats.edit', compact('lightstat', 'objects', 'rooms',
            'types', 'devices', 'methods', 'object_types',
            'scripts', 'usensors', 'deviceId', 'portId', 'portsSCL', 'portsSDA', 'port_SCL', 'port_SDA', 'can'));
    }

    public function create()
    {

        list($objects, $rooms, $types, $devices, $usensors) = $this->getLists();
        $object_types =  HomeObject::getFullTypeIds();
        $can = gates('devices.show-object');

        return view('lightstats.create', compact('objects','rooms', 'types', 'devices', 'usensors', 'object_types', 'can'));
    }

    public function update(UpdateRequest $r, Lightstat $lightstat)
    {
        try {
            if ($this->service->update($lightstat, $r->except('_token'))) {
                return redirect()->route('lightstats.edit', [$lightstat->id])->with('success','Светостат успешно изменен');
            }
        } catch (\Throwable $e) {
            \Log::error('Ошибка при изменении светостата '.$lightstat->id.' ' .json_encode($r->all()).' '.$e->getMessage());
        }

        return back()->withInput($r->all())->with('error', 'Ошибка при изменении светостата');
    }


}

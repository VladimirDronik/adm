<?php

namespace App\Http\Controllers;

use App\Http\Requests\Termostat\CreateRequest;
use App\Http\Requests\Termostat\UpdateRequest;
use App\Models\HomeObject;
use App\Models\Termostat;
use App\Repositories\DeviceRepository;
use App\Repositories\ObjectRepository;
use App\Repositories\RoomRepository;
use App\Repositories\ScriptRepository;
use App\Repositories\TermostatRepository;
use App\Repositories\UsensorRepository;
use App\Services\ObjectService;
use App\Services\TermostatService;


class TermostatController extends Controller
{
    private $termostat_rep;
    private $object_rep;
    private $device_rep;
    private $usensors_rep;
    private $room_rep;
    private $service;

    public function __construct(TermostatRepository $termostat_rep, ObjectRepository $object_rep, UsensorRepository $usensor_rep,
                                DeviceRepository $device_rep, RoomRepository $room_rep, TermostatService $service)
    {
        $this->termostat_rep = $termostat_rep;
        $this->object_rep = $object_rep;
        $this->device_rep = $device_rep;
        $this->usensors_rep = $usensor_rep;
        $this->room_rep = $room_rep;
        $this->service = $service;
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
        $devices = $this->device_rep->getAllToArray();
        $usensors = $this->usensors_rep->getAllToArray();

        return [$objects, $rooms, $types, $devices, $usensors];
    }

    public function create()
    {
        list($objects, $rooms, $types, $devices, $usensors) = $this->getLists();
        $object_types =  HomeObject::getFullTypeIds();
        $can = gates('devices.show-object');

        return view('termostats.create', compact('objects','rooms', 'types', 'devices', 'usensors', 'object_types', 'can'));
    }

    public function store(CreateRequest $r)
    {
        try {
            if ($id = $this->service->store($r->except('_token'))) {
                return redirect()->route('termostats.edit', [$id])
                    ->with('success', 'Термостат успешно добавлен');
            }
        } catch (\Throwable $e) {
            \Log::error('Ошибка при добавлении термостата '.json_encode($r->all()).' '.$e->getMessage());
        }

        return back()->withInput($r->all())->with('error', 'Ошибка при добавлении термостата');
    }

    public function edit(Termostat $termostat, ObjectService $object_service, ScriptRepository $script_rep)
    {
        list($objects, $rooms, $types, $devices) = $this->getLists();

        $methods = $object_service->getMethodsByObjectIdToArray($termostat->object);
        $object_types = HomeObject::getFullTypeIds();
        $scripts = $script_rep->getAllToArray();
        $can = gates('devices.show-object');

        return view('termostats.edit', compact('termostat', 'objects', 'rooms',
            'types', 'devices', 'methods', 'object_types', 'scripts', 'can'));
    }

    public function update(UpdateRequest $r, Termostat $termostat)
    {
        try {
            if ($this->service->update($termostat, $r->except('_token'))) {
                return redirect()->route('termostats.edit', [$termostat->id])->with('success','Термостат успешно изменен');
            }
        } catch (\Throwable $e) {
            \Log::error('Ошибка при изменении термостата '.$termostat->id.' ' .json_encode($r->all()).' '.$e->getMessage());
        }

        return back()->withInput($r->all())->with('error', 'Ошибка при изменении термостата');
    }
}

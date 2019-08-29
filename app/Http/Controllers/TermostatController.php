<?php

namespace App\Http\Controllers;

use App\Http\Requests\Termostat\CreateRequest;
use App\Http\Requests\Termostat\UpdateRequest;
use App\Models\Termostat;
use App\Repositories\DeviceRepository;
use App\Repositories\MethodRepository;
use App\Repositories\ObjectRepository;
use App\Repositories\RoomRepository;
use App\Repositories\TermostatRepository;
use App\Services\TermostatService;

class TermostatController extends Controller
{
    private $termostat_rep;
    private $object_rep;
    private $device_rep;
    private $room_rep;
    private $method_rep;
    private $service;

    public function __construct(TermostatRepository $termostat_rep, ObjectRepository $object_rep, DeviceRepository $device_rep,
                                RoomRepository $room_rep, MethodRepository $method_rep, TermostatService $service)
    {
        $this->termostat_rep = $termostat_rep;
        $this->object_rep = $object_rep;
        $this->device_rep = $device_rep;
        $this->room_rep = $room_rep;
        $this->method_rep = $method_rep;
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
        $devices = $this->device_rep->getAllToArray();
        $rooms = $this->room_rep->getAllToArray();
        $methods = $this->method_rep->getAllToArray();
        $types = Termostat::getFullThermostatIds();

        return [$objects, $devices, $rooms, $methods, $types];
    }

    public function create()
    {
        list($objects, $devices, $rooms, $methods, $types) = $this->getLists();

        return view('termostats.create', compact('objects','devices','rooms','methods','types'));
    }

    public function store(CreateRequest $r)
    {dd($r->all);
        try {
            if ($id = $this->service->store($r->except('_token'))) {
                return redirect()->route('termostats.edit',[$id])->with('success', 'Термостат успешно добавлен');
            }
        } catch (\Throwable $e) {
            \Log::error('Ошибка при добавлении термостата ' .json_encode($r->all()).' '.$e->getMessage());
        }

        return back()->withInput($r->all())->with('error', 'Ошибка при добавлении термостата');
    }

    public function edit(Termostat $termostat)
    {
        list($objects, $devices, $rooms, $methods) = $this->getLists();

        return view('termostats.edit', compact('termostat', 'objects','devices','rooms','methods'));
    }

    public function update(UpdateRequest $r, Termostat $termostat)
    {
        try {
            if ($this->service->update($termostat, $r->except('_token'))) {
                return redirect()->route('termostats.edit',[$termostat->id])->with('success','Термостат успешно изменен');
            }
        } catch (\Throwable $e) {
            \Log::error('Ошибка при изменении термостата '.$termostat->id.' ' .json_encode($r->all()).' '.$e->getMessage());
        }

        return back()->withInput($r->all())->with('error','Ошибка при изменении термостата');
    }
}

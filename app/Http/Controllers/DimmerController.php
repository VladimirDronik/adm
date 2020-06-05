<?php

namespace App\Http\Controllers;

use App\Http\Requests\Dimmer\CreateRequest;
use App\Http\Requests\Dimmer\UpdateRequest;
use App\Models\Dimmer;
use App\Models\HomeObject;
use App\Repositories\DeviceRepository;
use App\Repositories\DimmerRepository;
use App\Repositories\ObjectRepository;
use App\Repositories\ScriptRepository;
use App\Services\DeviceService;
use App\Services\DimmerService;
use App\Services\NetworkService;
use App\Services\PortService;

class DimmerController extends Controller
{
    private $dimmer_rep;
    private $object_rep;
    private $device_rep;
    private $portService;
    private $service;

    public function __construct(DimmerRepository $dimmer_rep, ObjectRepository $object_rep, DeviceRepository $device_rep,
                                DimmerService $service, PortService $portService)
    {
        $this->dimmer_rep = $dimmer_rep;
        $this->object_rep = $object_rep;
        $this->device_rep = $device_rep;
        $this->portService = $portService;
        $this->service = $service;
    }

    public function index()
    {
        $dimmers = $this->dimmer_rep->getAll();

        return view('dimmers.index', compact('dimmers'));
    }

    public function create()
    {
        $objects = $this->object_rep->getAllToArray();
        $object_types = HomeObject::getFullTypeIds();
        $devices = $this->device_rep->getAllToArray();

        return view('dimmers.create', compact('objects', 'object_types', 'devices'));
    }

    public function store(CreateRequest $r)
    {
        try {
            if ($id = $this->service->store($r->except('_token'))) {
                return redirect()->route('dimmers.edit', [$id])
                    ->with('success', 'Диммер успешно добавлен');
            }
        } catch (\Throwable $e) {
            \Log::error('Ошибка при добавлении диммера ' .
                json_encode($r->all()).' '.$e->getMessage());
        }

        return back()->withInput($r->all())->with('error', 'Ошибка при добавлении диммера');
    }

    public function edit(Dimmer $dimmer, ScriptRepository $script_rep)
    {
        $objects = $this->object_rep->getAllToArray();
        $object_types = HomeObject::getFullTypeIds();
        $scripts = $script_rep->getAllToArray();
        $can = gates('devices.show-object');

        list ($idDevice, $idPort, $devices, $ports) = $this->portService->getCurrentDevPort($dimmer->id_object);


        return view('dimmers.edit', compact('dimmer',
            'idDevice','idPort','devices','ports',
            'objects', 'object_types', 'scripts', 'can'));
    }

    public function update(UpdateRequest $r, Dimmer $dimmer)
    {
        try {
            if ($this->service->update($dimmer, $r->except('_token'))) {
                return redirect()->route('dimmers.edit',[$dimmer->id])
                    ->with('success', 'Диммер успешно изменен');
            }
        } catch (\Throwable $e) {
            \Log::error('Ошибка при изменении диммера '.$dimmer->id
                .' ' .json_encode($r->all()).' '.$e->getMessage());
        }

        return back()->withInput($r->all())->with('error', 'Ошибка при изменении диммера');
    }
}

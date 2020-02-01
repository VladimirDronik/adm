<?php

namespace App\Http\Controllers;

use App\Http\Requests\Relay\CreateRequest;
use App\Http\Requests\Relay\UpdateRequest;
use App\Models\HomeObject;
use App\Models\Relay;
use App\Repositories\DeviceRepository;
use App\Repositories\ObjectRepository;
use App\Repositories\RelayRepository;
use App\Repositories\ScriptRepository;
use App\Services\RelayService;

class RelayController extends Controller
{
    private $relay_rep;
    private $object_rep;
    private $device_rep;
    private $service;

    public function __construct(RelayRepository $relay_rep, ObjectRepository $object_rep, DeviceRepository $device_rep,
                                RelayService $service)
    {
        $this->relay_rep = $relay_rep;
        $this->object_rep = $object_rep;
        $this->device_rep = $device_rep;
        $this->service = $service;
    }

    public function index()
    {
        $relays = $this->relay_rep->getAll();

        return view('relays.index', compact('relays'));
    }

    public function create()
    {
        $types = Relay::getTypes(true);
        $objects = $this->object_rep->getAllToArray();
        $object_types =  HomeObject::getFullTypeIds();
        $devices = $this->device_rep->getAllToArray();

        return view('relays.create', compact('types', 'objects', 'object_types', 'devices'));
    }

    public function store(CreateRequest $r)
    {
        try {
            if ($id = $this->service->store($r->except('_token'))) {
                return redirect()->route('relays.edit', [$id])
                    ->with('success', 'Реле успешно добавлено');
            }
        } catch (\Throwable $e) {
            \Log::error('Ошибка при добавлении реле ' .
                json_encode($r->all()).' '.$e->getMessage());
        }

        return back()->withInput($r->all())->with('error', 'Ошибка при добавлении реле');
    }

    public function edit(Relay $relay, ScriptRepository $script_rep)
    {
        $types = Relay::getTypes(true);
        $objects = $this->object_rep->getAllToArray();
        $object_types =  HomeObject::getFullTypeIds();

        $scripts = $script_rep->getAllToArray();
        $can = gates('devices.show-object');

        return view('relays.edit', compact('relay', 'types',
            'objects', 'object_types', 'scripts', 'can'));
    }

    public function update(UpdateRequest $r, Relay $relay)
    {
        try {
            if ($this->service->update($relay, $r->except('_token'))) {
                return redirect()->route('relays.edit', [$relay->id])
                    ->with('success', 'Реле успешно изменено');
            }
        } catch (\Throwable $e) {
            \Log::error('Ошибка при изменении реле '.$relay->id
                .' ' .json_encode($r->all()).' '.$e->getMessage());
        }

        return back()->withInput($r->all())->with('error','Ошибка при изменении реле');
    }
}

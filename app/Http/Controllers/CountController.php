<?php

namespace App\Http\Controllers;

use App\Http\Requests\Count\CreateRequest;
use App\Http\Requests\Count\UpdateRequest;
use App\Models\Count;
use App\Models\HomeObject;
use App\Repositories\CountRepository;
use App\Repositories\DeviceRepository;
use App\Repositories\ObjectRepository;
use App\Repositories\ScriptRepository;
use App\Services\CountService;
use App\Services\PortService;
use App\Services\Service;

class CountController extends Controller
{
    public function __construct(
        private CountRepository $count_rep,
        private ObjectRepository $object_rep,
        private DeviceRepository $device_rep,
        private CountService $service,
        private PortService $portService,
        private ScriptRepository $script_rep,
    )
    {}

    public function index()
    {
        $counts = $this->count_rep->getAll();

        return view('counts.index', compact('counts'));
    }

    public function create()
    {
        $types = Count::getTypes(true);
        $objects = $this->object_rep->getAllToArray();
        $object_types =  HomeObject::getFullTypeIds();
        $devices = $this->device_rep->getAllWithoutTypesToArray(['Hite-pro']);

        return view('counts.create', compact('types', 'objects', 'object_types', 'devices'));
    }

    public function store(CreateRequest $r)
    {
        try {
            if ($id = $this->service->store($r->except('_token'))) {
                return redirect()->route('counts.edit', [$id])
                    ->with('success', 'Счетчик успешно добавлен');
            }
        } catch (\Throwable $e) {
            \Log::error('Ошибка при добавлении счетчика ' .
                json_encode($r->all()).' '.$e->getMessage());
        }

        return back()->withInput($r->all())->with('error', 'Ошибка при добавлении счетчика');
    }

    public function edit(Count $count, $tab=1)
    {
        $types = Count::getTypes(true);
        $objects = $this->object_rep->getAllToArray();
        $object_types =  HomeObject::getFullTypeIds();

        $scripts = $this->script_rep->getAllToArray();
        $can = gates('devices.show-object');

        list ($idDevice, $idPort, $devices, $ports) = $this->portService->getCurrentDevPort($count->id_object, 'IN,I2C,1WIRE,1W-BUS');
        list($messages, $events, $sounds, $views, $rooms, $scripts, $objects, $object_types, $alice, $allEvents) =
            Service::getListElements($count->id_object);

        $availableEvents = Count::getEvents();
        $properties = Count::getProperties();

        return view('counts.edit', compact('count', 'types', 'tab',
            'idDevice','idPort','devices','ports', 'events', 'allEvents', 'availableEvents', 'properties', 'sounds',
            'views',
            'objects', 'object_types', 'scripts', 'can'));
    }

    public function update(UpdateRequest $r, Count $count)
    {
        try {
            if ($this->service->update($count, $r->except('_token'))) {
                return redirect()->route('counts.edit',[$count->id])
                    ->with('success', 'Счетчик успешно изменен');
            }
        } catch (\Throwable $e) {
            \Log::error('Ошибка при изменении счетчика '.$count->id
                .' ' .json_encode($r->all()).' '.$e->getMessage());
        }

        return back()->withInput($r->all())->with('error','Ошибка при изменении счетчика');
    }
}

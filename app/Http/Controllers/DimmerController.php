<?php

namespace App\Http\Controllers;

use App\Models\Dimmer;
use App\Services\Service;
use App\Models\HomeObject;
use App\Services\PortService;
use App\Services\DimmerService;
use Illuminate\Support\Facades\Log;
use App\Repositories\DeviceRepository;
use App\Repositories\DimmerRepository;
use App\Repositories\ObjectRepository;
use App\Http\Requests\Dimmer\CreateRequest;
use App\Http\Requests\Dimmer\UpdateRequest;

class DimmerController extends Controller
{
    public function __construct(
        private DimmerRepository $dimmerRep,
        private ObjectRepository $objectRep,
        private DeviceRepository $deviceRep,
        private DimmerService $service,
        private PortService $portService,
    ) {
    }

    public function index()
    {
        $dimmers = $this->dimmerRep->getAll();

        return view('dimmers.index', compact('dimmers'));
    }

    public function create()
    {
        $objects = $this->objectRep->getAllToArray();
        $object_types = HomeObject::getFullTypeIds();
        $devices = $this->deviceRep->getAllWithoutTypesToArray(['Hite-pro']);

        return view('dimmers.create', compact(
            'objects', 'object_types', 'devices'
        ));
    }

    public function store(CreateRequest $r)
    {
        try {
            if ($id = $this->service->store($r->except('_token'))) {
                return redirect()
                    ->route('dimmers.edit', [$id])
                    ->with('success', 'Диммер успешно добавлен');
            }
        } catch (\Throwable $e) {
            Log::error(
                'Ошибка при добавлении диммера '
                .json_encode($r->all()).' '.$e->getMessage()
            );
        }

        return back()->withInput($r->all())
            ->with('error', 'Ошибка при добавлении диммера');
    }

    public function edit(Dimmer $dimmer, int $tab = 1)
    {
        $can = gates('devices.show-object');

        [$idDevice, $idPort, $devices, $ports] = $this->portService
            ->getCurrentDevPort($dimmer->id_object, 'OUT,0..10V');

        [
            $messages, $events, $sounds, $views,
            $rooms, $scripts, $objects, $object_types, $alice
        ] = Service::getListElements($dimmer->id_object);

        $availableEvents = Dimmer::getEvents();
        $properties = Dimmer::getProperties();
        $allEvents = '';

        return view('dimmers.edit', compact(
            'dimmer', 'messages', 'events', 'sounds', 'idDevice', 'idPort',
            'devices', 'ports', 'views', 'rooms', 'alice', 'tab', 'properties',
            'objects', 'object_types', 'scripts', 'can', 'availableEvents', 'allEvents'
        ));
    }

    public function update(UpdateRequest $r, Dimmer $dimmer)
    {
        try {
            if ($this->service->update($dimmer, $r->except('_token'))) {
                return redirect()
                    ->route('dimmers.edit', [$dimmer->id])
                    ->with('success', 'Диммер успешно изменен');
            }
        } catch (\Throwable $e) {
            Log::error(
                'Ошибка при изменении диммера '.$dimmer->id
                .' '.json_encode($r->all()).' '.$e->getMessage()
            );
        }

        return back()->withInput($r->all())
            ->with('error', 'Ошибка при изменении диммера');
    }
}

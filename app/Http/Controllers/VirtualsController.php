<?php

namespace App\Http\Controllers;

use App\Models\Virtual;
use App\Services\Service;
use App\Models\HomeObject;
use App\Services\VirtualService;
use Illuminate\Support\Facades\Log;
use App\Repositories\DeviceRepository;
use App\Repositories\ObjectRepository;
use App\Repositories\VirtualRepository;
use App\Http\Requests\Virtual\CreateRequest;
use App\Http\Requests\Virtual\UpdateRequest;

class VirtualsController extends Controller
{
    public function __construct(
        private VirtualRepository $virtRep,
        private ObjectRepository $objectRep,
        private DeviceRepository $deviceRep,
        private VirtualService $service,
    ) {
    }

    public function index()
    {
        $virtuals = $this->virtRep->getAll();

        return view('virtuals.index', compact('virtuals'));
    }

    public function create()
    {
        $objects = $this->objectRep->getAllToArray();
        $object_types = HomeObject::getFullTypeIds();
        $devices = $this->deviceRep->getAllToArray();

        return view('virtuals.create', compact(
            'objects', 'object_types', 'devices'
        ));
    }

    public function store(CreateRequest $r)
    {
        try {
            if ($id = $this->service->store($r->except('_token'))) {
                return redirect()
                    ->route('virtuals.edit', [$id])
                    ->with('success', 'Виртуальное устройство успешно добавлено');
            }
        } catch (\Throwable $e) {
            Log::error(
                'Ошибка при добавлении виртуального устройства '
                .json_encode($r->all()).' '.$e->getMessage()
            );
        }

        return back()->withInput($r->all())
            ->with('error', 'Ошибка при добавлении виртуального устройства');
    }

    public function edit(Virtual $virtual, int $tab = 1)
    {
        $can = gates('devices.show-object');

        [
            $messages, $events, $sounds, $views,
            $rooms, $scripts, $objects, $object_types, $alice
        ] = Service::getListElements($virtual->id_object);

        $messagePoint['first'] = 'При включении';
        $messagePoint['second'] = 'При выключении';

        $availableEvents = Virtual::getEvents();
        $properties = Virtual::getProperties();

        $allEvents = '';

        return view('virtuals.edit', compact(
            'virtual', 'tab', 'allEvents', 'alice', 'rooms',
            'availableEvents', 'properties', 'sounds', 'views', 'events',
            'messagePoint', 'messages', 'objects', 'object_types', 'scripts', 'can'
        ));
    }

    public function update(UpdateRequest $r, Virtual $virtual)
    {
        try {
            if ($this->service->update($virtual, $r->except('_token'))) {
                return redirect()
                    ->route('virtuals.edit', [$virtual->id])
                    ->with('success', 'Виртуальное устройство успешно изменено');
            }
        } catch (\Throwable $e) {
            Log::error(
                'Ошибка при изменении виртуального устройства '.$virtual->id
                .' '.json_encode($r->all()).' '.$e->getMessage()
            );
        }

        return back()->withInput($r->all())
            ->with('error', 'Ошибка при изменении виртуального устройсва');
    }
}

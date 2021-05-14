<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Repositories\CurtainRepository;
use App\Models\Curtain;
use App\Repositories\DeviceRepository;
use App\Repositories\HiteProDevRepository;
use App\Repositories\ObjectRepository;
use App\Services\PortService;
use App\Services\Service;
use App\Http\Requests\Curtain\UpdateRequest;
use App\Services\CurtainService;
use App\Models\HomeObject;
use App\Http\Requests\Curtain\CreateRequest;

class LockController extends Controller
{
    private $curtain_rep;
    private $portService;
    private $curtainService;
    private $object_rep;
    private $device_rep;


    public function __construct(CurtainRepository $curtainRepository, PortService $portService,
                                CurtainService $curtainService, ObjectRepository $objectRepository,
                                DeviceRepository $deviceRepository)
    {
        $this->curtain_rep = $curtainRepository;
        $this->portService = $portService;
        $this->curtainService = $curtainService;
        $this->object_rep = $objectRepository;
        $this->device_rep = $deviceRepository;
    }

    public function index()
    {
        $curtains = $this->curtain_rep->getAll();

        return view('curtains.index', compact('curtains'));
    }

    public function edit(Curtain $curtain, $tab =1)
    {
        $types = Curtain::getTypes(true);

        $can = gates('devices.show-object');

        list ($idDevice, $idPort, $devices, $ports, $hp_device, $hp_devices) =
            $this->portService->getCurrentDevPort($curtain->id_object, 'OUT');

        list($messages, $events, $sounds, $views, $rooms, $scripts, $objects, $object_types, $alice) =
            Service::getListElements($curtain->id_object);


        $messagePoint['first'] = 'При включении';
        $messagePoint['second'] = 'При выключении';

        $availableEvents = Curtain::getEvents();
        $properties = Curtain::getProperties();


        $devices = $this->device_rep->getAllToArray();
        $idPort_open = $curtain->port_open;
        $idPort_close = $curtain->port_close;
        $hp_device_open = $curtain->port_open;
        $hp_device_close = $curtain->port_close;
        $place = $curtain->place;

        $allEvents = '';

        return view('curtains.edit', compact('curtain', 'types', 'events', 'sounds', 'views', 'rooms',
            'idDevice','idPort','devices','ports', 'messagePoint', 'messages', 'alice', 'tab', 'availableEvents', 'properties',
            'objects', 'object_types', 'scripts', 'hp_device', 'hp_devices', 'idPort_open', 'idPort_close',
            'hp_device_open', 'hp_device_close', 'allEvents', 'place', 'can'));
    }



    public function update(UpdateRequest $r, int $id)
    {
        $curtain = Curtain::findOrFail($id);

        try {
            if ($this->curtainService->update($curtain, $r->except('_token'))) {
                return redirect()->route('curtains.edit', [$curtain->id])
                    ->with('success', 'Штора успешно изменена');
            }
        } catch (\Throwable $e) {
            \Log::error('Ошибка при изменении шторы '.$curtain->id
                .' ' .json_encode($r->all()).' '.$e->getMessage());
        }

        return back()->withInput($r->all())->with('error','Ошибка при изменении шторы');
    }


    public function create()
    {
        $types = Curtain::getTypes(true);
        $objects = $this->object_rep->getAllToArray();
        $object_types =  HomeObject::getFullTypeIds();
        $devices = $this->device_rep->getAllToArray();
        $tab = 1;

        return view('curtains.create', compact('types', 'tab', 'objects', 'object_types', 'devices'));
    }


    public function store(CreateRequest $r)
    {
        try {
            if ($id = $this->curtainService->store($r->except('_token'))) {
                return redirect()->route('curtains.edit', [$id])
                    ->with('success', 'Штора успешно добавлена');
            }
        } catch (\Throwable $e) {
            \Log::error('Ошибка при добавлении шторы ' .
                json_encode($r->all()).' '.$e->getMessage());
        }

        return back()->withInput($r->all())->with('error', 'Ошибка при добавлении шторы');
    }


}

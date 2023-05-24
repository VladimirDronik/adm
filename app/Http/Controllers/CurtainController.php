<?php

namespace App\Http\Controllers;

use App\Repositories\CurtainRepository;
use App\Models\Curtain;
use App\Repositories\DeviceRepository;
use App\Repositories\ObjectRepository;
use App\Services\PortService;
use App\Services\Service;
use App\Services\CurtainService;
use App\Models\HomeObject;
use App\Http\Requests\Curtain\CurtainFormRequest;

class CurtainController extends Controller
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

    public function edit(Curtain $curtain, $tab = 1)
    {
        $can = gates('devices.show-object');
        $ports = '';

        if ($curtain->place == Curtain::PLACE_PORT || $curtain->place == Curtain::PLACE_PHASE) {
            list($idDevice, , , $ports) =
                $this->portService->getCurrentDevPort($curtain->id_object, 'OUT');
        } else {
            $idDevice = $curtain->device_id;
        }

        list($messages, $events, $sounds, $views, $rooms, $scripts, $objects, $object_types, $alice) =
            Service::getListElements($curtain->id_object);

        $types = Curtain::getTypes(true);
        $messagePoint['first'] = 'При включении';
        $messagePoint['second'] = 'При выключении';
        $availableEvents = Curtain::getEvents();
        $properties = Curtain::getProperties();
        $devices = $this->device_rep->getAllToArray();
        $allEvents = '';

        return view('curtains.edit', compact('types', 'curtain', 'events', 'sounds', 'views', 'rooms',
            'idDevice', 'devices', 'ports', 'messagePoint', 'messages', 'alice', 'tab', 'availableEvents',
            'properties', 'objects', 'object_types', 'scripts', 'allEvents', 'can'));
    }



    public function update(CurtainFormRequest $r, int $id)
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
        $places = Curtain::getPlaces(true);
        $objects = $this->object_rep->getAllToArray();
        $object_types =  HomeObject::getFullTypeIds();
        $devices = $this->device_rep->getAllToArray();
        $tab = 1;

        return view('curtains.create', compact('types', 'places', 'tab', 'objects', 'object_types', 'devices'));
    }


    public function store(CurtainFormRequest $r)
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

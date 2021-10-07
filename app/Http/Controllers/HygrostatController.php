<?php

namespace App\Http\Controllers;

use App\Http\Requests\Hygrostat\CreateRequest;
use App\Http\Requests\Hygrostat\UpdateRequest;
use App\Models\HomeObject;
use App\Models\Hygrostat;
use App\Models\Sound;
use App\Models\Termostat;
use App\Repositories\DeviceRepository;
use App\Repositories\ObjectRepository;
use App\Repositories\PortRepository;
use App\Repositories\RoomRepository;
use App\Repositories\ScriptRepository;
use App\Repositories\TermostatRepository;
use App\Repositories\HygrostatRepository;
use App\Repositories\UsensorRepository;
use App\Repositories\ViewRepository;
use App\Services\HygrostatService;
use App\Services\MessageService;
use App\Services\ObjectService;
use App\Services\PortService;
use App\Services\TermostatService;
use App\Repositories\EventRepository;
use App\Repositories\SoundRepository;



class HygrostatController extends Controller
{
    private $hygrostat_rep;
    private $object_rep;
    private $device_rep;
    private $usensors_rep;
    private $room_rep;
    private $service;
    private $event_rep;
    private $view_rep;


    public function __construct(HygrostatRepository $hygrostat_rep, ObjectRepository $object_rep, UsensorRepository $usensor_rep,
                                DeviceRepository $device_rep, RoomRepository $room_rep, HygrostatService $service,
                                EventRepository $eventRepository, ViewRepository $viewRepository)
    {
        $this->hygrostat_rep = $hygrostat_rep;
        $this->object_rep = $object_rep;
        $this->device_rep = $device_rep;
        $this->usensors_rep = $usensor_rep;
        $this->room_rep = $room_rep;
        $this->service = $service;
        $this->event_rep = $eventRepository;
        $this->view_rep = $viewRepository;

    }

    public function index()
    {
        $hygrostats = $this->hygrostat_rep->getAll();


        return view('hygrostats.index', compact('hygrostats'));
    }

    private function getLists()
    {
        $objects = $this->object_rep->getAllToArray();
        $rooms = $this->room_rep->getAllToArray();
        $types = Hygrostat::getFullHygrostatIds();
        $devices = $this->device_rep->getAllWithoutTypesToArray(['Hite-pro']);
        $usensors = $this->usensors_rep->getAllToArray();
        $HPControllers = $this->device_rep->getAllByTypesToArray(['Hite-pro']);



        return [$objects, $rooms, $types, $devices, $usensors, $HPControllers];
    }

    public function create()
    {
        list($objects, $rooms, $types, $devices, $usensors, $HPControllers) = $this->getLists();
        $object_types =  HomeObject::getFullTypeIds();
        $can = gates('devices.show-object');
        $tab =1;

        return view('hygrostats.create', compact('objects','rooms', 'types', 'devices',
            'usensors', 'object_types', 'HPControllers', 'can', 'tab'));
    }



    public function store(CreateRequest $r)
    {
        try {
            if ($id = $this->service->store($r->except('_token'))) {
                return redirect()->route('hygrostats.edit', [$id])
                    ->with('success', 'Гигростат успешно добавлен');
            }
        } catch (\Throwable $e) {
            \Log::error('Ошибка при добавлении гигростата '.json_encode($r->all()).' '.$e->getMessage());
        }

        return back()->withInput($r->all())->with('error', 'Ошибка при добавлении гигростата');
    }



    public function edit(Hygrostat $hygrostat, $tab=1, ObjectService $object_service, ScriptRepository $script_rep,
                         PortService $portsService, MessageService $messagesService)
    {

        list($objects, $rooms, $types, $devices, $usensors, $HPControllers) = $this->getLists();


        $methods = $object_service->getMethodsByObjectIdToArray($hygrostat->object);
        $object_types = HomeObject::getFullTypeIds();
        $scripts = $script_rep->getAllToArray();
        $can = gates('devices.show-object');

        $deviceAndPort = $portsService->getIdDeviceAndPortId($hygrostat->id_object);

        $deviceId = $deviceAndPort['id_device'];

        $messages = $messagesService->getNotifications($hygrostat->id_object);

        $id_controller = $portsService->getIdControllerBySubdevice($hygrostat->subdev_id, 'Hite-pro');
        $subdevs = $portsService->getSubdevsForController($id_controller, 'Hite-pro', 'temperature');

        $messagePoint['first'] = 'При включении';
        $messagePoint['second'] = 'При выключении';

        $events = $this->event_rep->getAllById($hygrostat->id_object);
        $availableEvents = Termostat::getEvents();
        $properties = Termostat::getProperties();
        $sounds = SoundRepository::getAllToArray();
        $views = $this->view_rep->getAllToArray();
        $allEvents = '';



        return view('hygrostats.edit', compact('hygrostat', 'objects', 'rooms',
            'types', 'devices', 'methods', 'object_types', 'scripts', 'HPControllers', 'id_controller',
            'subdevs', 'usensors', 'deviceId', 'messages', 'messagePoint', 'can', 'tab', 'events',
            'availableEvents', 'properties', 'sounds', 'views', 'allEvents'));
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

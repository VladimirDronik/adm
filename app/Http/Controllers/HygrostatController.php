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
    public function __construct(
        private HygrostatRepository $hygrostat_rep,
        private ObjectRepository $object_rep,
        private DeviceRepository $device_rep,
        private UsensorRepository $usensor_rep,
        private RoomRepository $room_rep,
        private HygrostatService $service,
        private EventRepository $event_rep,
        private ViewRepository $view_rep,
        private ObjectService $object_service,
        private ScriptRepository $script_rep,
        private PortService $portsService,
        private MessageService $messagesService,
    )
    {}

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
        $usensors = $this->usensor_rep->getAllToArray();
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



    public function edit(Hygrostat $hygrostat, $tab=1)
    {

        list($objects, $rooms, $types, $devices, $usensors, $HPControllers) = $this->getLists();


        $methods = $this->object_service->getMethodsByObjectIdToArray($hygrostat->object);
        $object_types = HomeObject::getFullTypeIds();
        $scripts = $this->script_rep->getAllToArray();
        $can = gates('devices.show-object');

        $deviceAndPort = $this->portsService->getIdDeviceAndPortId($hygrostat->id_object);

        $deviceId = $deviceAndPort['id_device'];

        $messages = $this->messagesService->getNotifications($hygrostat->id_object);

        $id_controller = $this->portsService->getIdControllerBySubdevice($hygrostat->subdev_id, 'Hite-pro');
        $subdevs = $this->portsService->getSubdevsForController($id_controller, 'Hite-pro', 'temperature');

        $messagePoint['first'] = 'При включении';
        $messagePoint['second'] = 'При выключении';

        $events = $this->event_rep->getAllById($hygrostat->id_object);
        $availableEvents = Hygrostat::getEvents();
        $properties = Hygrostat::getProperties();
        $sounds = SoundRepository::getAllToArray();
        $views = $this->view_rep->getAllToArray();
        $allEvents = '';



        return view('hygrostats.edit', compact('hygrostat', 'objects', 'rooms',
            'types', 'devices', 'methods', 'object_types', 'scripts', 'HPControllers', 'id_controller',
            'subdevs', 'usensors', 'deviceId', 'messages', 'messagePoint', 'can', 'tab', 'events',
            'availableEvents', 'properties', 'sounds', 'views', 'allEvents'));
    }




    public function update(UpdateRequest $r, Hygrostat $hygrostat)
    {
        try {
            if ($this->service->update($hygrostat, $r->except('_token'))) {
                return redirect()->route('hygrostats.edit', [$hygrostat->id])->with('success','Гигростат успешно изменен');
            }
        } catch (\Throwable $e) {
            \Log::error('Ошибка при изменении гигростата '.$hygrostat->id.' ' .json_encode($r->all()).' '.$e->getMessage());
        }

        return back()->withInput($r->all())->with('error', 'Ошибка при изменении гигростата');
    }
}

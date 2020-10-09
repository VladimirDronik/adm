<?php

namespace App\Http\Controllers;






use App\Repositories\CarbmonoxideRepository;
use App\Repositories\DeviceRepository;
use App\Repositories\ObjectRepository;
use App\Repositories\RoomRepository;
use App\Models\HomeObject;
use App\Models\Carbmonoxide;
use App\Services\CarbmonoxideService;
use App\Http\Requests\Carbmonoxide\CreateRequest;
use App\Services\ObjectService;
use App\Repositories\ScriptRepository;
use App\Services\PortService;
use App\Services\MessageService;
use App\Http\Requests\Carbmonoxide\UpdateRequest;

class CarbmonoxideController extends Controller
{

    private $carbmonoxide_rep;
    private $object_rep;
    private $room_rep;
    private $device_rep;
    private $service;
    private $object_service;
    private $script_repository;
    private $port_service;
    private $messagesService;

    public function __construct(CarbmonoxideRepository $carbmonoxideRepository, ObjectRepository $objectRepository,
                                RoomRepository $roomRepository, DeviceRepository $deviceRepository, CarbmonoxideService $service,
                                ObjectService $objectService, ScriptRepository $scriptRepository, PortService $portService,
                                MessageService $messageService)
    {
        $this->carbmonoxide_rep = $carbmonoxideRepository;
        $this->object_rep = $objectRepository;
        $this->room_rep = $roomRepository;
        $this->device_rep = $deviceRepository;
        $this->service = $service;
        $this->object_service = $objectService;
        $this->script_repository = $scriptRepository;
        $this->port_service = $portService;
        $this->messagesService = $messageService;

    }

    public function index()
    {

        $carbmonoxides = $this->carbmonoxide_rep->getAll();

        return view('carbmonoxide.index', compact('carbmonoxides'));
    }

    private function getLists()
    {
        $objects = $this->object_rep->getAllToArray();
        $rooms = $this->room_rep->getAllToArray();
        $devices = $this->device_rep->getAllToArray();


        return [$objects, $rooms, $devices];
    }

    public function create()
    {

        list($objects, $rooms, $devices) = $this->getLists();
        $object_types =  HomeObject::getFullTypeIds();
        $can = gates('devices.show-object');

        return view('carbmonoxide.create', compact('objects','rooms', 'devices', 'object_types', 'can'));
    }

    public function store(CreateRequest $r)
    {
        try {
            if ($id = $this->service->store($r->except('_token'))) {
                return redirect()->route('carbmonoxide.edit', [$id])
                    ->with('success', 'Датчик CO2 успешно добавлен');
            }
        } catch (\Throwable $e) {
            \Log::error('Ошибка при добавлении датчика CO2 '.json_encode($r->all()).' '.$e->getMessage());
        }

        return back()->withInput($r->all())->with('error', 'Ошибка при добавлении датчика CO2');

    }

    public function edit(Carbmonoxide $carbmonoxide, ObjectService $object_service, ScriptRepository $script_rep,
                         PortService $portsService, MessageService $messagesService)
    {
        list($objects, $rooms, $devices) = $this->getLists();


        $low_methods = $object_service->getMethodsByObjectIdToArray($carbmonoxide->low_object);
        $high_methods = $object_service->getMethodsByObjectIdToArray($carbmonoxide->low_object);

        $object_types = HomeObject::getFullTypeIds();
        $scripts = $script_rep->getAllToArray();
        $can = gates('devices.show-object');

        $deviceAndPort = $portsService->getIdDeviceAndPortId($carbmonoxide->id_object);

        $deviceId = $deviceAndPort['id_device'];
        $port = $deviceAndPort['id_port'];

        $ports =  $portsService->getPortsIntoList($deviceId, 'IN');

        $messages = $messagesService->getNotifications($carbmonoxide->id_object);




        $messagePoint['first'] = 'При нижнем пороге';
        $messagePoint['second'] = 'При верхнем пороге';

        return view('carbmonoxide.edit', compact('carbmonoxide', 'objects', 'rooms',
            'devices', 'low_methods', 'high_methods', 'object_types', 'messages',
            'scripts', 'deviceId', 'ports', 'messagePoint', 'port', 'can'));
    }


    public function update(UpdateRequest $r, Carbmonoxide $carbmonoxide)
    {
        try {
            if ($this->service->update($carbmonoxide, $r->except('_token'))) {
                return redirect()->route('carbmonoxide.edit', [$carbmonoxide->id])->with('success','Датчик CO2 успешно изменен');
            }
        } catch (\Throwable $e) {
            \Log::error('Ошибка при изменении датчика CO2 '.$carbmonoxide->id.' ' .json_encode($r->all()).' '.$e->getMessage());
        }

        return back()->withInput($r->all())->with('error', 'Ошибка при изменении датчика CO2');
    }

}

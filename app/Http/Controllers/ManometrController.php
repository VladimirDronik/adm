<?php

namespace App\Http\Controllers;



use App\Repositories\DeviceRepository;
use App\Repositories\ManometrRepository;
use App\Repositories\ObjectRepository;
use App\Repositories\RoomRepository;
use App\Models\HomeObject;
use App\Http\Requests\Manometr\CreateRequest;
use App\Http\Requests\Manometr\UpdateRequest;
use App\Services\ManometrService;
use App\Services\ObjectService;
use App\Repositories\ScriptRepository;
use App\Services\PortService;
use App\Services\MessageService;
use App\Models\Manometr;

class ManometrController extends Controller
{

    private $manometr_rep;
    private $object_rep;
    private $room_rep;
    private $device_rep;
    private $service;
    private $object_service;
    private $script_repository;
    private $port_service;
    private $messagesService;

    public function __construct(ManometrRepository $manometrRepository, ObjectRepository $objectRepository,
                                RoomRepository $roomRepository, DeviceRepository $deviceRepository, ManometrService $service,
                                ObjectService $objectService, ScriptRepository $scriptRepository, PortService $portService,
                                MessageService $messageService)
    {
        $this->manometr_rep = $manometrRepository;
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
        $manometrs = $this->manometr_rep->getAll();

        return view('manometr.index', compact('manometrs'));
    }

    private function getLists()
    {
        $objects = $this->object_rep->getAllToArray();
        $rooms = $this->room_rep->getAllToArray();
        $devices = $this->device_rep->getAllWithoutTypesToArray(['Hite-pro']);


        return [$objects, $rooms, $devices];
    }

    public function create()
    {

        list($objects, $rooms, $devices) = $this->getLists();
        $object_types =  HomeObject::getFullTypeIds();
        $can = gates('devices.show-object');

        return view('manometr.create', compact('objects','rooms', 'devices', 'object_types', 'can'));
    }

        public function store(CreateRequest $r)
        {
            try {
                if ($id = $this->service->store($r->except('_token'))) {
                    return redirect()->route('manometr.edit', [$id])
                        ->with('success', 'Манометр успешно добавлен');
                }
            } catch (\Throwable $e) {
                \Log::error('Ошибка при добавлении манометра '.json_encode($r->all()).' '.$e->getMessage());
            }

            return back()->withInput($r->all())->with('error', 'Ошибка при добавлении манометра');

        }

            public function edit(Manometr $manometr, ObjectService $object_service, ScriptRepository $script_rep,
                                 PortService $portsService, MessageService $messagesService)
            {
                list($objects, $rooms, $devices) = $this->getLists();


                $low_methods = $object_service->getMethodsByObjectIdToArray($manometr->low_object);
                $high_methods = $object_service->getMethodsByObjectIdToArray($manometr->low_object);

                $object_types = HomeObject::getFullTypeIds();
                $scripts = $script_rep->getAllToArray();
                $can = gates('devices.show-object');

                $deviceAndPort = $portsService->getIdDeviceAndPortId($manometr->id_object);

                $deviceId = $deviceAndPort['id_device'];
                $port = $deviceAndPort['id_port'];

                $ports =  $portsService->getPortsIntoList($deviceId, 'IN,I2C,1WIRE,1W-BUS,ADC');

                $messages = $messagesService->getNotifications($manometr->id_object);


                $messagePoint['first'] = 'При нижнем пороге';
                $messagePoint['second'] = 'При верхнем пороге';

                return view('manometr.edit', compact('manometr', 'objects', 'rooms',
                    'devices', 'low_methods', 'high_methods', 'object_types', 'messages',
                    'scripts', 'deviceId', 'ports', 'messagePoint', 'port', 'can'));
            }


            public function update(UpdateRequest $r, Manometr $manometr)
            {
                try {
                    if ($this->service->update($manometr, $r->except('_token'))) {
                        return redirect()->route('manometr.edit', [$manometr->id])->with('success','Манометр успешно изменен');
                    }
                } catch (\Throwable $e) {
                    \Log::error('Ошибка при изменении манометра '.$manometr->id.' ' .json_encode($r->all()).' '.$e->getMessage());
                }

                return back()->withInput($r->all())->with('error', 'Ошибка при изменении манометра');
            }

}

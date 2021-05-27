<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\Lock;
use App\Repositories\LockRepository;
use App\Repositories\DeviceRepository;
use App\Repositories\HiteProDevRepository;
use App\Repositories\ObjectRepository;
use App\Services\PortService;
use App\Services\Service;
use App\Http\Requests\Lock\CreateRequest;
use App\Http\Requests\Lock\UpdateRequest;
use App\Services\LockService;
use App\Models\HomeObject;



class LockController extends Controller
{

    private $portService;
    private $object_rep;
    private $device_rep;
    private $lock_rep;
    private $lockService;


    public function __construct(LockRepository $lockRepository, PortService $portService,
                                LockService $lockService, ObjectRepository $objectRepository,
                                DeviceRepository $deviceRepository)
    {
        $this->lock_rep = $lockRepository;
        $this->portService = $portService;
        $this->lockService = $lockService;
        $this->object_rep = $objectRepository;
        $this->device_rep = $deviceRepository;
    }

    public function index()
    {
        $locks = $this->lock_rep->getAll();

        return view('locks.index', compact('locks'));
    }

    public function edit(Lock $lock, $tab =1)
    {
        $types = Lock::getTypes(true);

        $can = gates('devices.show-object');

        list ($idDevice, $idPort, $devices, $ports, $hp_device, $hp_devices) =
            $this->portService->getCurrentDevPort($lock->id_object, 'OUT');

        list($messages, $events, $sounds, $views, $rooms, $scripts, $objects, $object_types, $alice) =
            Service::getListElements($lock->id_object);


        $messagePoint['first'] = 'При включении';
        $messagePoint['second'] = 'При выключении';

        $availableEvents = Lock::getEvents();
        $properties = Lock::getProperties();


        $devices = $this->device_rep->getAllToArray();
        $idPort_open = $lock->port_open;
        $idPort_close = $lock->port_close;
        $hp_device_open = $lock->port_open;
        $hp_device_close = $lock->port_close;
        $place = $lock->place;

        if ($lock->type == 'Electromechanical') {
            $label_port = 'Порт на открытие: ';
            $label_hitepro = 'Устройство на открытие: ';
        } else {
            $label_port = 'Порт: ';
            $label_hitepro = 'Устройство: ';
        }


        $allEvents = '';

        return view('locks.edit', compact('lock', 'types', 'events', 'sounds', 'views', 'rooms',
            'idDevice','idPort','devices','ports', 'messagePoint', 'messages', 'alice', 'tab', 'availableEvents', 'properties',
            'objects', 'object_types', 'scripts', 'hp_device', 'hp_devices', 'idPort_open', 'idPort_close',
            'label_port', 'label_hitepro', 'hp_device_open', 'hp_device_close', 'allEvents', 'place', 'can'));
    }



    public function update(UpdateRequest $r, int $id)
    {
        $lock = Lock::findOrFail($id);

        try {
            if ($this->lockService->update($lock, $r->except('_token'))) {
                return redirect()->route('locks.edit', [$lock->id])
                    ->with('success', 'Змаок успешно изменен');
            }
        } catch (\Throwable $e) {
            \Log::error('Ошибка при изменении замка '.$lock->id
                .' ' .json_encode($r->all()).' '.$e->getMessage());
        }

        return back()->withInput($r->all())->with('error','Ошибка при изменении замка');
    }


    public function create()
    {
        $types = Lock::getTypes(true);
        $objects = $this->object_rep->getAllToArray();
        $object_types =  HomeObject::getFullTypeIds();
        $devices = $this->device_rep->getAllToArray();
        $tab = 1;

        return view('locks.create', compact('types', 'tab', 'objects', 'object_types', 'devices'));
    }


    public function store(CreateRequest $r)
    {
        try {
            if ($id = $this->lockService->store($r->except('_token'))) {
                return redirect()->route('locks.edit', [$id])
                    ->with('success', 'Замок успешно добавлен');
            }
        } catch (\Throwable $e) {
            \Log::error('Ошибка при добавлении замка ' .
                json_encode($r->all()).' '.$e->getMessage());
        }

        return back()->withInput($r->all())->with('error', 'Ошибка при добавлении замка');
    }


}

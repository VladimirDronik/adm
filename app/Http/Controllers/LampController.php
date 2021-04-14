<?php

namespace App\Http\Controllers;

use App\Models\Lamp;
use App\Services\PortService;
use Illuminate\Http\Request;
use App\Repositories\LampRepository;
use App\Repositories\ObjectRepository;
use App\Repositories\DeviceRepository;
use App\Services\LampService;
use App\Models\HomeObject;
use App\Http\Requests\Lamp\CreateRequest;
use App\Repositories\ScriptRepository;
use App\Services\MessageService;
use App\Http\Requests\Lamp\UpdateRequest;

class LampController extends Controller
{

    private $lamp_rep;
    private $object_rep;
    private $device_rep;
    private $service;
    private $portService;

    public function __construct(LampRepository $lamp_rep, ObjectRepository $object_rep, DeviceRepository $device_rep,
                                LampService $service, PortService $portService)
    {
        $this->lamp_rep = $lamp_rep;
        $this->object_rep = $object_rep;
        $this->device_rep = $device_rep;
        $this->service = $service;
        $this->portService = $portService;
    }

    public function index()
    {
        $lamps = $this->lamp_rep->getAll();

        return view('lamps.index', compact('lamps'));
    }

    public function create()
    {

        $objects = $this->object_rep->getAllToArray();
        $object_types =  HomeObject::getFullTypeIds();
        $devices = $this->device_rep->getAllWithoutTypesToArray(['Hite-pro']);

        return view('lamps.create', compact( 'objects', 'object_types', 'devices'));
    }

    public function store(CreateRequest $r)
    {
        try {
            if ($id = $this->service->store($r->except('_token'))) {
                return redirect()->route('lamps.edit', [$id])
                    ->with('success', 'Лампа успешно добавлена');
            }
        } catch (\Throwable $e) {
            \Log::error('Ошибка при добавлении лампы ' .
                json_encode($r->all()).' '.$e->getMessage());
        }

        return back()->withInput($r->all())->with('error', 'Ошибка при добавлении лампы');
    }

    public function update(UpdateRequest $r, int $id)
    {
        $lamp = Lamp::findOrFail($id);

        try {
            if ($this->service->update($lamp, $r->except('_token'))) {
                return redirect()->route('lamps.edit', [$lamp->id])
                    ->with('success', 'Лампа успешно изменена');
            }
        } catch (\Throwable $e) {
            \Log::error('Ошибка при изменении лампы '.$lamp->id
                .' ' .json_encode($r->all()).' '.$e->getMessage());
        }

        return back()->withInput($r->all())->with('error','Ошибка при изменении лампы');
    }

    public function edit(Lamp $lamp, ScriptRepository $script_rep, MessageService $messageService)
    {
        $objects = $this->object_rep->getAllToArray();
        $object_types =  HomeObject::getFullTypeIds();

        $scripts = $script_rep->getAllToArray();
        $can = gates('devices.show-object');

        list ($idDevice, $idPort, $devices, $ports, $hp_device, $hp_devices) = $this->portService->getCurrentDevPort($lamp->id_object);


        $messages = $messageService->getNotifications($lamp->id_object);

        $messagePoint['first'] = 'При включении';
        $messagePoint['second'] = 'При выключении';

        return view('lamps.edit', compact('lamp',
            'idDevice','idPort','devices','ports', 'messagePoint', 'messages',
            'objects', 'object_types', 'scripts', 'hp_device', 'hp_devices', 'can'));
    }
}

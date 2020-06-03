<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeviceSwitch\CreateRequest;
use App\Http\Requests\DeviceSwitch\UpdateRequest;
use App\Models\DeviceSwitch;
use App\Models\HomeObject;
use App\Models\Method;
use App\Repositories\DeviceRepository;
use App\Repositories\ObjectRepository;
use App\Repositories\ScriptRepository;
use App\Repositories\SwitchRepository;
use App\Services\MessageService;
use App\Services\MethodService;
use App\Services\ObjectService;
use App\Services\PortService;
use App\Services\SwitchService;

class SwitchController extends Controller
{
    private $switch_rep;
    private $object_rep;
    private $device_rep;
    private $service;

    public function __construct(SwitchRepository $switch_rep, ObjectRepository $object_rep, DeviceRepository $device_rep,
                                SwitchService $service)
    {
        $this->switch_rep = $switch_rep;
        $this->object_rep = $object_rep;
        $this->device_rep = $device_rep;
        $this->service = $service;
    }

    public function index()
    {
        $switches = $this->switch_rep->getAll();

        return view('switches.index', compact('switches'));
    }

    public function create()
    {
        $types = DeviceSwitch::getTypes(true);
        $objects = $this->object_rep->getAllToArray();
        $object_types =  HomeObject::getFullTypeIds();
        $devices = $this->device_rep->getAllToArray();
        $can = gates('devices.show-object');

        return view('switches.create', compact('types', 'objects', 'object_types', 'devices', 'can'));
    }

    public function store(CreateRequest $r)
    {
        try {
            if ($id = $this->service->store($r->except('_token'))) {
                return redirect()->route('switches.edit', [$id])
                    ->with('success', 'Выключатель успешно добавлен');
            }
        } catch (\Throwable $e) {
            \Log::error('Ошибка при добавлении выключателя ' .
                json_encode($r->all()).' '.$e->getMessage());
        }

        return back()->withInput($r->all())->with('error', 'Ошибка при добавлении выключателя');
    }

    public function edit(int $id, ScriptRepository $script_rep,
                         ObjectService $objectService, PortService $portService,
                         MessageService $messagesService,DeviceRepository $device_rep)
    {
        $switch = DeviceSwitch::findOrFail($id);

        $types = DeviceSwitch::getTypes(true);
        $objects = $this->object_rep->getAllToArray();
        $object_types =  HomeObject::getFullTypeIds();

        $deviceAndPort = $portService->getIdDeviceAndPortId($switch->id_object);
        $idDevice =  $deviceAndPort['id_device'];
        $idPort = $deviceAndPort['id_port'];

        $devices = $this->device_rep->getAllToArray();
        $ports =  $portService->getPortsIntoList($idDevice);

        $port = $portService->getMethodsByObject($switch->id_object);

        if($port) {
            $method = $port->method;
            $object = $objectService->getObjectByMethod($method);
            $methods = $objectService->getMethodsByObjectIdToArray($object);

            $method_dc = $port->dc_method;
            $object_dc = $objectService->getObjectByMethod($method_dc);
            $methods_dc = $objectService->getMethodsByObjectIdToArray($object_dc);

            $method_lc = $port->lc_method;
            $object_lc = $objectService->getObjectByMethod($method_lc);
            $methods_lc = $objectService->getMethodsByObjectIdToArray($object_lc);
        } else {

            $method = null;
            $object = null;
            $methods = [];
            $method_dc =  null;
            $object_dc =  null;
            $methods_dc =  [];
            $method_lc =  null;
            $object_lc =  null;
            $methods_lc =  [];
        }


        $messages = $messagesService->getNotifications($switch->id_object);

        $messagePoint['first'] = 'При включении';
        $messagePoint['second'] = 'При выключении';

        $scripts = $script_rep->getAllToArray();
        $can = gates('devices.show-object');

        return view('switches.edit', compact('switch', 'types',
            'object', 'method', 'methods', 'object_dc', 'method_dc', 'methods_dc',
            'object_lc', 'method_lc', 'methods_lc', 'idDevice', 'idPort', 'devices', 'ports',
            'messages', 'messagePoint',
            'objects', 'object_types', 'scripts', 'can'));
    }

    public function update(UpdateRequest $r, int $id)
    {
        $switch = DeviceSwitch::findOrFail($id);

        try {
            if ($this->service->update($switch, $r->except('_token'))) {
                return redirect()->route('switches.edit', [$switch->id])
                    ->with('success', 'Выключатель успешно изменен');
            }
        } catch (\Throwable $e) {
            \Log::error('Ошибка при изменении выключателя '.$switch->id
                .' ' .json_encode($r->all()).' '.$e->getMessage());
        }

        return back()->withInput($r->all())->with('error','Ошибка при изменении выключателя');
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\DeviceSwitch\CreateRequest;
use App\Http\Requests\DeviceSwitch\UpdateRequest;
use App\Models\DeviceSwitch;
use App\Models\HomeObject;
use App\Repositories\DeviceRepository;
use App\Repositories\ObjectRepository;
use App\Repositories\ScriptRepository;
use App\Repositories\SwitchRepository;
use App\Services\MessageService;
use App\Services\ObjectService;
use App\Services\PortService;
use App\Services\Service;
use App\Services\SwitchService;

class SwitchController extends Controller
{
    public function __construct(
        private SwitchRepository $switch_rep,
        private ObjectRepository $object_rep,
        private DeviceRepository $device_rep,
        private SwitchService $service,
        private PortService $portService,
        private ScriptRepository $script_rep,
        private ObjectService $objectService,
        private MessageService $messagesService,
    ) {
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
        $object_types = HomeObject::getFullTypeIds();
        $devices = $this->device_rep->getAllWithoutTypesToArray(['Hite-pro']);
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
            \Log::error('Ошибка при добавлении выключателя '.
                json_encode($r->all()).' '.$e->getMessage());
        }

        return back()->withInput($r->all())->with('error', 'Ошибка при добавлении выключателя');
    }

    public function edit(int $id, $tab = 1)
    {
        $switch = DeviceSwitch::findOrFail($id);

        $types = DeviceSwitch::getTypes(true);

        $objects = $this->object_rep
            ->getAllExcludeGivenType('conditioner')
            ->pluck('name', 'id')
            ->toArray();

        $object_types = HomeObject::getFullTypeIds();

        $deviceAndPort = $this->portService->getIdDeviceAndPortId($switch->id_object);

        $port = $this->portService->getMethodsByObject($switch->id_object);

        [$idDevice, $idPort, $devices, $ports, $hp_device, $hp_devices] = $this->portService->getCurrentDevPort($switch->id_object, 'IN,I2C,1WIRE,1W-BUS,ADC');
        [$messages, $events, $sounds, $views, $rooms, $scripts, , $object_types, $alice, $allEvents] =
            Service::getListElements($switch->id_object);

        $params['value'] = '';
        $params['name'] = '';
        $params_dc['value'] = '';
        $params_dc['name'] = '';
        $params_lc['value'] = '';
        $params_lc['name'] = '';
        $place = '';

        if ($port) {
            $method = $port->method;
            $object = $this->objectService->getObjectByMethod($method);
            $methods = $this->objectService->getMethodsByObjectIdToArray($object);
            if ($port->method) {
                $params['value'] = $port->method_params;
                $params['name'] = $this->objectService->getParamsByMethodId($port->method);
            }

            $method_dc = $port->dc_method;
            $object_dc = $this->objectService->getObjectByMethod($method_dc);
            $methods_dc = $this->objectService->getMethodsByObjectIdToArray($object_dc);

            if ($port->dc_method) {
                $params_dc['value'] = $port->dc_method_params;
                $params_dc['name'] = $this->objectService->getParamsByMethodId($port->dc_method);
            }

            $method_lc = $port->lc_method;
            $object_lc = $this->objectService->getObjectByMethod($method_lc);
            $methods_lc = $this->objectService->getMethodsByObjectIdToArray($object_lc);

            if ($port->lc_method) {
                $params_lc['value'] = $port->lc_method_params;
                $params_lc['name'] = $this->objectService->getParamsByMethodId($port->lc_method);
            }

            $place = 'port';

        } else {

            $method = null;
            $object = null;
            $methods = [];
            $method_dc = null;
            $object_dc = null;
            $methods_dc = [];
            $method_lc = null;
            $object_lc = null;
            $methods_lc = [];

            //Если выбрано устройство hite-pro, значит контроллер тоже хитпро
            if ($hp_device != null) {
                $method = $switch->id_method;
                $object = $this->objectService->getObjectByMethod($method);
                $methods = $this->objectService->getMethodsByObjectIdToArray($object);
                $place = 'Hite-pro';
                if ($switch->id_method) {
                    $params['value'] = $switch->method_params;
                    $params['name'] = $this->objectService->getParamsByMethodId($switch->id_method);
                }
            }
        }

        $messages = $this->messagesService->getNotifications($switch->id_object);

        $messagePoint['first'] = 'При включении';
        $messagePoint['second'] = 'При выключении';

        $scripts = $this->script_rep->getAllToArray();
        $can = gates('devices.show-object');

        $availableEvents = DeviceSwitch::getEvents();
        $properties = DeviceSwitch::getProperties();

        //Если тип = кнопка, то выводим в устройствах хит-про
        if ($switch->type == 'button') {
            $devices = $this->device_rep->getAllWithoutTypesToArray();
        }

        return view('switches.edit', compact('switch', 'types', 'tab', 'events', 'allEvents',
            'object', 'method', 'methods', 'object_dc', 'method_dc', 'methods_dc', 'availableEvents', 'properties',
            'object_lc', 'method_lc', 'methods_lc', 'idDevice', 'idPort', 'devices', 'ports', 'sounds', 'views',
            'messages', 'messagePoint', 'hp_device', 'hp_devices', 'params', 'params_dc', 'params_lc',
            'objects', 'object_types', 'scripts', 'place', 'can'));
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
                .' '.json_encode($r->all()).' '.$e->getMessage());
        }

        return back()->withInput($r->all())->with('error', 'Ошибка при изменении выключателя');
    }
}

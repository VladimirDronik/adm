<?php

namespace App\Http\Controllers;

use App\Services\Service;
use App\Models\HomeObject;
use App\Models\DeviceSwitch;
use App\Services\PortService;
use App\Services\ObjectService;
use App\Services\SwitchService;
use App\Services\MessageService;
use Illuminate\Support\Facades\Log;
use App\Repositories\DeviceRepository;
use App\Repositories\ObjectRepository;
use App\Repositories\ScriptRepository;
use App\Repositories\SwitchRepository;
use App\Http\Requests\DeviceSwitch\CreateRequest;
use App\Http\Requests\DeviceSwitch\UpdateRequest;

class SwitchController extends Controller
{
    public function __construct(
        private SwitchRepository $switchRep,
        private ObjectRepository $objectRep,
        private DeviceRepository $deviceRep,
        private SwitchService $service,
        private PortService $portService,
        private ScriptRepository $scriptRep,
        private ObjectService $objectService,
        private MessageService $messagesService,
    ) {
    }

    public function index()
    {
        $switches = $this->switchRep->getAll();

        return view('switches.index', compact('switches'));
    }

    public function create()
    {
        $types = DeviceSwitch::getTypes(true);
        $objects = $this->objectRep->getAllToArray();
        $object_types = HomeObject::getFullTypeIds();
        $devices = $this->deviceRep->getAllWithoutTypesToArray(['Hite-pro']);
        $can = gates('devices.show-object');

        return view('switches.create', compact(
            'types', 'objects', 'object_types', 'devices', 'can'
        ));
    }

    public function store(CreateRequest $r)
    {
        try {
            if ($id = $this->service->store($r->except('_token'))) {
                return redirect()
                    ->route('switches.edit', [$id])
                    ->with('success', 'Выключатель успешно добавлен');
            }
        } catch (\Throwable $e) {
            Log::error(
                'Ошибка при добавлении выключателя '
                .json_encode($r->all()).' '.$e->getMessage()
            );
        }

        return back()->withInput($r->all())
            ->with('error', 'Ошибка при добавлении выключателя');
    }

    public function edit(int $id, int $tab = 1)
    {
        $switch = DeviceSwitch::findOrFail($id);

        $types = DeviceSwitch::getTypes(true);

        $objects = $this->objectRep
            ->getAllExcludeGivenType('conditioner')
            ->pluck('name', 'id')
            ->toArray();

        $object_types = HomeObject::getFullTypeIds();

        $deviceAndPort = $this->portService->getIdDeviceAndPortId($switch->id_object);

        $port = $this->portService->getMethodsByObject($switch->id_object);

        [$idDevice, $idPort, $devices, $ports, $hp_device, $hp_devices] = $this->portService
            ->getCurrentDevPort($switch->id_object, 'IN,I2C,1WIRE,1W-BUS,ADC');

        [
            $messages, $events, $sounds, $views,
            $rooms, $scripts, , $object_types, $alice, $allEvents
        ] = Service::getListElements($switch->id_object);

        $params['value'] = '';
        $params['name'] = '';
        $params_dc['value'] = '';
        $params_dc['name'] = '';
        $params_lc['value'] = '';
        $params_lc['name'] = '';
        $params_lcr['value'] = '';
        $params_lcr['name'] = '';
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
            $method_lcr = $port->lcr_method;
            $object_lc = $this->objectService->getObjectByMethod($method_lc);
            $object_lcr = $this->objectService->getObjectByMethod($method_lcr);
            $methods_lc = $this->objectService->getMethodsByObjectIdToArray($object_lc);
            $methods_lcr = $this->objectService->getMethodsByObjectIdToArray($object_lcr);

            if ($port->lc_method) {
                $params_lc['value'] = $port->lc_method_params;
                $params_lc['name'] = $this->objectService->getParamsByMethodId($port->lc_method);
            }

            if ($port->lcr_method) {
                $params_lcr['value'] = $port->lcr_method_params;
                $params_lcr['name'] = $this->objectService->getParamsByMethodId($port->lcr_method);
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
            $method_lcr = null;
            $object_lcr = null;
            $methods_lcr = [];

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

        $scripts = $this->scriptRep->getAllToArray();
        $can = gates('devices.show-object');

        $availableEvents = DeviceSwitch::getEvents();
        $properties = DeviceSwitch::getProperties();

        //Если тип = кнопка, то выводим в устройствах хит-про
        if ($switch->type == 'button') {
            $devices = $this->deviceRep->getAllWithoutTypesToArray();
        }

        return view('switches.edit', compact(
            'switch', 'types', 'tab', 'events', 'allEvents',
            'object', 'method', 'methods', 'object_dc', 'method_dc', 'methods_dc', 'availableEvents', 'properties',
            'object_lc', 'method_lc', 'methods_lc', 'object_lcr', 'method_lcr', 'methods_lcr','idDevice', 'idPort', 'devices', 'ports', 'sounds', 'views',
            'messages', 'messagePoint', 'hp_device', 'hp_devices', 'params', 'params_dc', 'params_lc', 'params_lcr',
            'objects', 'object_types', 'scripts', 'place', 'can'
        ));
    }

    public function update(UpdateRequest $r, int $id)
    {
        $switch = DeviceSwitch::findOrFail($id);

        try {
            if ($this->service->update($switch, $r->except('_token'))) {
                return redirect()
                    ->route('switches.edit', [$switch->id])
                    ->with('success', 'Выключатель успешно изменен');
            }
        } catch (\Throwable $e) {
            Log::error(
                'Ошибка при изменении выключателя '.$switch->id
                .' '.json_encode($r->all()).' '.$e->getMessage()
            );
        }

        return back()->withInput($r->all())
            ->with('error', 'Ошибка при изменении выключателя');
    }
}

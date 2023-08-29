<?php

namespace App\Http\Controllers;

use App\Models\Motionsensor;
use App\Repositories\LightstatRepository;
use App\Repositories\MethodRepository;
use App\Services\MessageService;
use App\Services\MotionsensorService;
use App\Services\Service;
use Illuminate\Http\Request;
use App\Repositories\MotionsensorRepository;
use App\Repositories\ObjectRepository;
use App\Repositories\DeviceRepository;
use App\Models\HomeObject;
use App\Http\Requests\Motionsensor\CreateRequest;
use App\Repositories\ScriptRepository;
use App\Http\Requests\Motionsensor\UpdateRequest;
use App\Services\PortService;


class MotionsensorsController extends Controller
{
    public function __construct(
        private MotionsensorRepository $motionsens_rep,
        private ObjectRepository $object_rep,
        private DeviceRepository $device_rep,
        private MotionsensorService $service,
        private MethodRepository $methods_rep,
        private LightstatRepository $lightstat_rep,
        private ScriptRepository $script_rep,
        private PortService $portsService,
        private MessageService $messageService,
    )
    {}

    public function index()
    {
        $motionsensors = $this->motionsens_rep->getAll();

        return view('motionsensors.index', compact('motionsensors'));
    }

    public function create()
    {

        $objects = $this->object_rep->getAllToArray();
        $object_types =  HomeObject::getFullTypeIds();
        $devices = $this->device_rep->getAllWithoutTypesToArray(['Hite-pro']);
        $lightstats = $this->lightstat_rep->getAllToArray();
        $can = gates('devices.show-object');
        $equality = ['>' => 'Больше', '<' => 'Меньше'];

        return view('motionsensors.create', compact('objects', 'object_types', 'devices', 'lightstats', 'can', 'equality'));
    }

    public function store(CreateRequest $r)
    {
        try {
            if ($id = $this->service->store($r->except('_token'))) {
                return redirect()->route('motionsensors.edit', [$id])
                    ->with('success', 'Датчик движения успешно добавлен');
            }
        } catch (\Throwable $e) {
            \Log::error('Ошибка при добавлении датчика движения ' .
                json_encode($r->all()).' '.$e->getMessage());
        }

        return back()->withInput($r->all())->with('error', 'Ошибка при добавлении датчика движения');
    }


    public function edit(int $id, $tab=1)
    {
        $motionsensor = Motionsensor::findOrFail($id);

        $objects = $this->object_rep->getAllToArray();
        $object_types =  HomeObject::getFullTypeIds();
        $lightstats = $this->lightstat_rep->getAllToArray();
        $equality = ['>' => 'Больше', '<' => 'Меньше'];
        $devices = $this->device_rep->getAllWithoutTypesToArray(['Hite-pro']);


        $object_normal = $this->methods_rep->getObjectByMethod($motionsensor->method_normal);
        $methods_normal = $this->methods_rep->getAllMethodsByObjectToArray($object_normal);
        $object_eco = $this->methods_rep->getObjectByMethod($motionsensor->method_eco);
        $methods_eco = $this->methods_rep->getAllMethodsByObjectToArray($object_eco);
        $object_night = $this->methods_rep->getObjectByMethod($motionsensor->method_night);
        $methods_night = $this->methods_rep->getAllMethodsByObjectToArray($object_night);
        $object_evening = $this->methods_rep->getObjectByMethod($motionsensor->method_evening);
        $methods_evening = $this->methods_rep->getAllMethodsByObjectToArray($object_evening);
        $object_morning = $this->methods_rep->getObjectByMethod($motionsensor->method_morning);
        $methods_morning = $this->methods_rep->getAllMethodsByObjectToArray($object_morning);
        $object_guard = $this->methods_rep->getObjectByMethod($motionsensor->method_guard);
        $methods_guard = $this->methods_rep->getAllMethodsByObjectToArray($object_guard);
        $object_light = $this->methods_rep->getObjectByMethod($motionsensor->method_light);
        $methods_light = $this->methods_rep->getAllMethodsByObjectToArray($object_light);

        $deviceAndPort = $this->portsService->getIdDeviceAndPortId($motionsensor->id_object);
        $deviceId = $deviceAndPort['id_device'];
        $portId = $deviceAndPort['id_port'];
        $ports = $this->portsService->getPortsIntoList($deviceId, 'IN,I2C,1WIRE,1W-BUS,ADC');

        $messagePoint['first'] = 'При любом срабатывании';
        $messagePoint['second'] = 'Срабатывание в реж. охраны';

        $can = gates('motionsensors.show-object');

        list($messages, $events, $sounds, $views, $rooms, $scripts, $objects, $object_types, $alice, $allEvents) =
            Service::getListElements($motionsensor->id_object);

        $availableEvents = Motionsensor::getEvents();
        $properties = Motionsensor::getProperties();

        return view('motionsensors.edit', compact('motionsensor', 'events', 'allEvents', 'sounds',
            'objects', 'object_types', 'scripts', 'lightstats', 'can', 'equality', 'views', 'rooms',
            'object_normal', 'methods_normal', 'object_eco', 'methods_eco', 'messages', 'availableEvents',
            'object_night', 'methods_night', 'object_evening', 'methods_evening', 'properties',
            'object_morning', 'methods_morning', 'object_guard', 'methods_guard',
            'object_light', 'methods_light', 'deviceId', 'portId', 'devices', 'messagePoint', 'ports', 'tab'));
    }


    public function update(UpdateRequest $r, Motionsensor $motionsensor)
    {
        try {
            if ($this->service->update($motionsensor, $r->except('_token'))) {
                return redirect()->route('motionsensors.edit', [$motionsensor->id])->with('success','Датчик движения успешно изменен');
            }
        } catch (\Throwable $e) {
            \Log::error('Ошибка при изменении датчика движения '.$motionsensor->id.' ' .json_encode($r->all()).' '.$e->getMessage());
        }

        return back()->withInput($r->all())->with('error', 'Ошибка при изменении датчика движения');
    }

}

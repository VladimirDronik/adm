<?php

namespace App\Http\Controllers;

use App\Services\Service;
use App\Models\HomeObject;
use App\Models\Motionsensor;
use App\Services\PortService;
use App\Services\MessageService;
use Illuminate\Support\Facades\Log;
use App\Services\MotionsensorService;
use App\Repositories\DeviceRepository;
use App\Repositories\MethodRepository;
use App\Repositories\ObjectRepository;
use App\Repositories\LightstatRepository;
use App\Repositories\MotionsensorRepository;
use App\Http\Requests\Motionsensor\CreateRequest;
use App\Http\Requests\Motionsensor\UpdateRequest;

class MotionsensorsController extends Controller
{
    public function __construct(
        private MotionsensorRepository $motionsensRep,
        private ObjectRepository $objectRep,
        private DeviceRepository $deviceRep,
        private MotionsensorService $service,
        private MethodRepository $methodsRep,
        private LightstatRepository $lightstatRep,
        private PortService $portsService,
        private MessageService $messageService,
    ) {
    }

    public function index()
    {
        $motionsensors = $this->motionsensRep->getAll();

        return view('motionsensors.index', compact('motionsensors'));
    }

    public function create()
    {

        $objects = $this->objectRep->getAllToArray();
        $object_types = HomeObject::getFullTypeIds();
        $devices = $this->deviceRep->getAllWithoutTypesToArray(['Hite-pro']);
        $lightstats = $this->lightstatRep->getAllToArray();
        $can = gates('devices.show-object');
        $equality = ['>' => 'Больше', '<' => 'Меньше'];

        return view('motionsensors.create', compact(
            'objects', 'object_types', 'devices', 'lightstats', 'can', 'equality'
        ));
    }

    public function store(CreateRequest $r)
    {
        try {
            if ($id = $this->service->store($r->except('_token'))) {
                return redirect()
                    ->route('motionsensors.edit', [$id])
                    ->with('success', 'Датчик движения успешно добавлен');
            }
        } catch (\Throwable $e) {
            Log::error(
                'Ошибка при добавлении датчика движения '
                .json_encode($r->all()).' '.$e->getMessage()
            );
        }

        return back()->withInput($r->all())
            ->with('error', 'Ошибка при добавлении датчика движения');
    }

    public function edit(int $id, int $tab = 1)
    {
        $motionsensor = Motionsensor::findOrFail($id);

        $objects = $this->objectRep->getAllToArray();
        $object_types = HomeObject::getFullTypeIds();
        $lightstats = $this->lightstatRep->getAllToArray();
        $equality = ['>' => 'Больше', '<' => 'Меньше'];
        $devices = $this->deviceRep->getAllWithoutTypesToArray(['Hite-pro']);

        $object_normal = $this->methodsRep->getObjectByMethod($motionsensor->method_normal);
        $methods_normal = $this->methodsRep->getAllMethodsByObjectToArray($object_normal);
        $object_eco = $this->methodsRep->getObjectByMethod($motionsensor->method_eco);
        $methods_eco = $this->methodsRep->getAllMethodsByObjectToArray($object_eco);
        $object_night = $this->methodsRep->getObjectByMethod($motionsensor->method_night);
        $methods_night = $this->methodsRep->getAllMethodsByObjectToArray($object_night);
        $object_evening = $this->methodsRep->getObjectByMethod($motionsensor->method_evening);
        $methods_evening = $this->methodsRep->getAllMethodsByObjectToArray($object_evening);
        $object_morning = $this->methodsRep->getObjectByMethod($motionsensor->method_morning);
        $methods_morning = $this->methodsRep->getAllMethodsByObjectToArray($object_morning);
        $object_guard = $this->methodsRep->getObjectByMethod($motionsensor->method_guard);
        $methods_guard = $this->methodsRep->getAllMethodsByObjectToArray($object_guard);
        $object_light = $this->methodsRep->getObjectByMethod($motionsensor->method_light);
        $methods_light = $this->methodsRep->getAllMethodsByObjectToArray($object_light);

        $deviceAndPort = $this->portsService->getIdDeviceAndPortId($motionsensor->id_object);
        $deviceId = $deviceAndPort['id_device'];
        $portId = $deviceAndPort['id_port'];
        $ports = $this->portsService->getPortsIntoList($deviceId, 'IN,I2C,1WIRE,1W-BUS,ADC');

        $messagePoint['first'] = 'При любом срабатывании';
        $messagePoint['second'] = 'Срабатывание в реж. охраны';

        $can = gates('motionsensors.show-object');

        [
            $messages, $events, $sounds, $views, $rooms,
            $scripts, $objects, $object_types, $alice, $allEvents
        ] = Service::getListElements($motionsensor->id_object);

        $availableEvents = Motionsensor::getEvents();
        $properties = Motionsensor::getProperties();

        return view('motionsensors.edit', compact(
            'motionsensor', 'events', 'allEvents', 'sounds', 'rooms', 'availableEvents',
            'objects', 'object_types', 'scripts', 'lightstats', 'can', 'equality', 'views',
            'object_normal', 'methods_normal', 'object_eco', 'methods_eco', 'messages', 'tab',
            'object_night', 'methods_night', 'object_evening', 'methods_evening', 'properties',
            'object_morning', 'methods_morning', 'object_guard', 'methods_guard', 'messagePoint',
            'object_light', 'methods_light', 'deviceId', 'portId', 'devices', 'ports'
        ));
    }

    public function update(UpdateRequest $r, Motionsensor $motionsensor)
    {
        try {
            if ($this->service->update($motionsensor, $r->except('_token'))) {
                return redirect()
                    ->route('motionsensors.edit', [$motionsensor->id])
                    ->with('success', 'Датчик движения успешно изменен');
            }
        } catch (\Throwable $e) {
            Log::error(
                'Ошибка при изменении датчика движения '.$motionsensor->id
                .' '.json_encode($r->all()).' '.$e->getMessage()
            );
        }

        return back()->withInput($r->all())
            ->with('error', 'Ошибка при изменении датчика движения');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Motionsensor;
use App\Repositories\LightstatRepository;
use App\Repositories\MethodRepository;
use App\Services\MotionsensorService;
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

    private $motionsens_rep;
    private $object_rep;
    private $device_rep;
    private $service;
    private $methods_rep;
    private $lightstat_rep;


    public function __construct(MotionsensorRepository $motionsens_rep, ObjectRepository $object_rep, DeviceRepository $device_rep,
                                MotionsensorService $service, MethodRepository $methods_rep, LightstatRepository $lightstat_rep)
    {

        $this->motionsens_rep = $motionsens_rep;
        $this->object_rep = $object_rep;
        $this->device_rep = $device_rep;
        $this->service = $service;
        $this->methods_rep = $methods_rep;
        $this->lightstat_rep = $lightstat_rep;
    }

    public function index()
    {
        $motionsensors = $this->motionsens_rep->getAll();

        return view('motionsensors.index', compact('motionsensors'));
    }

    public function create()
    {

        $objects = $this->object_rep->getAllToArray();
        $object_types =  HomeObject::getFullTypeIds();
        $devices = $this->device_rep->getAllToArray();
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


    public function edit(int $id, ScriptRepository $script_rep, PortService $portsService)
    {
        $motionsensor = Motionsensor::findOrFail($id);

        $objects = $this->object_rep->getAllToArray();
        $object_types =  HomeObject::getFullTypeIds();
        $lightstats = $this->lightstat_rep->getAllToArray();
        $equality = ['>' => 'Больше', '<' => 'Меньше'];
        $devices = $this->device_rep->getAllToArray();


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

        $deviceAndPort = $portsService->getIdDeviceAndPortId($motionsensor->id_object);
        $deviceId = $deviceAndPort['id_device'];
        $portId = $deviceAndPort['id_port'];
        $ports = $portsService->getPortsIntoList($deviceId, 'IN');

        $scripts = $script_rep->getAllToArray();
        $can = gates('motionsensors.show-object');

        return view('motionsensors.edit', compact('motionsensor',
            'objects', 'object_types', 'scripts', 'lightstats', 'can', 'equality',
            'object_normal', 'methods_normal', 'object_eco', 'methods_eco',
            'object_night', 'methods_night', 'object_evening', 'methods_evening',
            'object_morning', 'methods_morning', 'object_guard', 'methods_guard',
            'object_light', 'methods_light', 'deviceId', 'portId', 'devices', 'ports'));
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

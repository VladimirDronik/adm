<?php

namespace App\Http\Controllers;

use App\Http\Requests\Count\CreateRequest;
use App\Http\Requests\Count\UpdateRequest;
use App\Models\Count;
use App\Models\HomeObject;
use App\Models\Port;
use App\Repositories\CountRepository;
use App\Repositories\DeviceRepository;
use App\Repositories\ModbusRepository;
use App\Repositories\ScriptRepository;
use App\Services\CountService;
use App\Services\Service;

class CountController extends Controller
{
    public function __construct(
        private CountRepository $countRepository,
        private DeviceRepository $deviceRepository,
        private CountService $service,
        private ScriptRepository $scriptRepository,
        private ModbusRepository $modbusRepository,
    ) {
    }

    public function index()
    {
        $counts = $this->countRepository->getAll();

        return view('counts.index', compact('counts'));
    }

    public function create()
    {
        $types = Count::getTypes(true);
        $devices = $this->deviceRepository->getAllToArray();
        $gatewayTypes = HomeObject::getGatewayTypes();
        $modbusSlavers = $this->modbusRepository->getAllSlaversToArray();

        return view('counts.create', compact('types', 'devices', 'modbusSlavers', 'gatewayTypes'));
    }

    public function store(CreateRequest $r)
    {
        try {
            if ($id = $this->service->store($r->except('_token'))) {
                return redirect()->route('counts.edit', [$id])
                    ->with('success', 'Счетчик успешно добавлен');
            }
        } catch (\Throwable $e) {
            \Log::error('Ошибка при добавлении счетчика '.
                json_encode($r->all()).' '.$e->getMessage());
        }

        return back()->withInput($r->all())->with('error', 'Ошибка при добавлении счетчика');
    }

    public function edit(Count $count, $tab = 1)
    {
        $types = Count::getTypes(true);

        $scripts = $this->scriptRepository->getAllToArray();
        $can = gates('devices.show-object');

        [$messages, $events, $sounds, $views, $rooms, $scripts, $objects, $object_types, $alice, $allEvents] =
            Service::getListElements($count->id_object);

        $availableEvents = Count::getEvents();
        $properties = Count::getProperties();

        $modbusSlavers = null;
        $devices = null;
        $currentPort = null;
        $methodsIdWithRegisters = [];

        if ($count->gateway_type == HomeObject::GATEWAY_MODBUS) {
            $modbusSlavers = $this->modbusRepository->getAllSlaversToArray();

            if ($count->object->methods->isNotEmpty()) {
                foreach ($count->object->methods as $method) {
                    $methodsIdWithRegisters[$method->id] = $method->register ? $method->register->id : 0;
                }
            }
        } else {
            $devices = $this->deviceRepository->getAllToArray();
            $currentPort = Port::where('object', $count->id_object)->first();
        }

        return view('counts.edit', compact(
            'count', 'types', 'tab', 'properties', 'sounds',
            'devices', 'events', 'allEvents', 'availableEvents', 'currentPort',
            'views', 'scripts', 'can', 'modbusSlavers', 'methodsIdWithRegisters'
        ));
    }

    public function update(UpdateRequest $r, Count $count)
    {
        try {
            if ($this->service->update($count, $r->except('_token'))) {
                return redirect()->route('counts.edit', [$count->id])
                    ->with('success', 'Счетчик успешно изменен');
            }
        } catch (\Throwable $e) {
            \Log::error('Ошибка при изменении счетчика '.$count->id
                .' '.json_encode($r->all()).' '.$e->getMessage());
        }

        return back()->withInput($r->all())->with('error', 'Ошибка при изменении счетчика');
    }
}

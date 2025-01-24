<?php

namespace App\Http\Controllers;

use App\Models\Port;
use App\Models\Regulator;
use App\Services\ObjectService;
use App\Services\RegulatorService;
use Illuminate\Support\Facades\Log;
use App\Repositories\RoomRepository;
use App\Repositories\DeviceRepository;
use App\Repositories\ModbusRepository;
use App\Repositories\ObjectRepository;
use App\Repositories\SensorRepository;
use App\Repositories\RegulatorRepository;
use App\Http\Requests\Regulator\CreateRequest;
use App\Http\Requests\Regulator\UpdateRequest;

class RegulatorController extends Controller
{
    public function __construct(
        private RegulatorRepository $regulatorRep,
        private RegulatorService $service,
        private SensorRepository $sensorRep,
        private ObjectRepository $objectRep,
        private ModbusRepository $modbusRep,
        private DeviceRepository $deviceRep,
        private ObjectService $objectService,
        private RoomRepository $roomRep,
    ) {
    }

    public function index()
    {
        $regulators = $this->regulatorRep->getAll();

        return view('regulators.index', compact('regulators'));
    }

    public function create()
    {
        $rooms = $this->roomRep->getAllWithoutCommonToArray();
        $sensors = $this->sensorRep->getAllToArray();
        $objects = $this->objectRep->getAllToArray();
        $slavers = $this->modbusRep->getAllByTypePurpose(['thermostat', 'hygrostat']);
        $devices = $this->deviceRep->getAllByTypesToArray(['MegaD-2561', 'Monoblock 14IN/14OUT']);

        return view('regulators.create', compact(
            'rooms', 'sensors', 'objects', 'slavers', 'devices'
        ));
    }

    public function store(CreateRequest $r)
    {
        try {
            $result = $this->service->store($r->except('_token'));

            if ($result['redirect_to_edit']) {
                return redirect()
                    ->route('regulators.edit', [$result['regulator']->id])
                    ->with('success', 'Регулятор успешно добавлен');
            } else {
                return redirect()
                    ->route('regulators.index')
                    ->with(
                        'error',
                        'Регулятор № '.$result['regulator']->object_id.' «'.$result['regulator']->object->name.'» недоступен'
                    );
            }
        } catch (\Throwable $e) {
            Log::error(
                'Ошибка при добавлении регулятора '
                .json_encode($r->all()).' '.$e->getMessage()
            );
        }

        return back()->withInput($r->all())
            ->with('error', 'Ошибка при добавлении регулятора');
    }

    public function edit(Regulator $regulator)
    {
        if ($regulator->source) {
            $getScriptData = $this->service->regulatorGetScript($regulator->object_id);

            if ($getScriptData['code'] !== 0) {
                return back()->with(
                    'error',
                    'Регулятор № '.$regulator->object_id.' «'.$regulator->object->name.'» недоступен'
                );
            }
        }

        $rooms = $this->roomRep->getAllWithoutCommonToArray();
        $objects = $this->objectRep->getAllToArray();
        $slavers = $this->modbusRep->getAllByTypePurpose(['thermostat', 'hygrostat']);
        $devices = $this->deviceRep->getAllByTypesToArray(['MegaD-2561', 'Monoblock 14IN/14OUT']);
        $device = null;

        if ($regulator->source == 'megad') {
            $device = Port::find($regulator->source_id)?->device;
        }

        $higherMethods = [];
        $lowerMethods = [];
        $fallbackMethods = [];

        if (!$regulator->source) {
            $higherMethods = $this->objectService
            ->getMethodsByObjectIdToArray($regulator->higherMethod->id_object);

            $lowerMethods = $this->objectService
                ->getMethodsByObjectIdToArray($regulator->lowerMethod->id_object);

            $fallbackMethods = $this->objectService
                ->getMethodsByObjectIdToArray($regulator->fallbackMethod?->id_object);
        }

        return view('regulators.edit', compact(
            'rooms', 'regulator', 'objects', 'slavers', 'devices',
            'higherMethods', 'lowerMethods', 'fallbackMethods', 'device'
        ));
    }

    public function update(UpdateRequest $r, Regulator $regulator)
    {
        try {
            $result = $this->service->update($regulator, $r->except('_token'));

            if ($result['redirect_to_edit']) {
                return redirect()
                    ->route('regulators.edit', [$regulator->id])
                    ->with('success', 'Регулятор успешно изменен');
            } else {
                return redirect()
                    ->route('regulators.index')
                    ->with(
                        'error',
                        'Регулятор № '.$result['regulator']->object_id.' «'.$result['regulator']->object->name.'» недоступен'
                    );
            }
        } catch (\Throwable $e) {
            Log::error(
                'Ошибка при изменении регулятора '.$regulator->id
                .' '.json_encode($r->all()).' '.$e->getMessage()
            );
        }

        return back()->withInput($r->all())
            ->with('error', 'Ошибка при изменении регулятора');
    }
}

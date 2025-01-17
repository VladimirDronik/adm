<?php

namespace App\Http\Controllers;

use App\Models\Regulator;
use App\Models\Termostat;
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
            if ($id = $this->service->store($r->except('_token'))) {
                return redirect()
                    ->route('regulators.edit', [$id])
                    ->with('success', 'Регулятор успешно добавлен');
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
        $rooms = $this->roomRep->getAllWithoutCommonToArray();

        return view('regulators.edit', compact('rooms', 'regulator'));
    }

    public function update(UpdateRequest $r, Regulator $regulator)
    {
        try {
            if ($this->service->update($regulator, $r->except('_token'))) {
                return redirect()
                    ->route('regulators.edit', [$regulator->id])
                    ->with('success', 'Регулятор успешно изменен');
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

<?php

namespace App\Http\Controllers;

use App\Models\Sensor;
use App\Models\HomeObject;
use App\Services\SensorService;
use Illuminate\Support\Facades\Log;
use App\Repositories\RoomRepository;
use App\Repositories\DeviceRepository;
use App\Repositories\ModbusRepository;
use App\Repositories\SensorRepository;
use App\Http\Requests\Sensor\CreateRequest;
use App\Http\Requests\Sensor\UpdateRequest;

class SensorController extends Controller
{
    public function __construct(
        private SensorRepository $sensorRepository,
        private RoomRepository $roomRepository,
        private SensorService $service,
        private DeviceRepository $deviceRepository,
        private ModbusRepository $modbusRepository,
    ) {
    }

    public function index()
    {
        $sensorObjects = $this->sensorRepository->getAll();

        return view('sensors.index', compact('sensorObjects'));
    }

    public function edit(int $id)
    {
        $sensorObject = HomeObject::findOrFail($id);
        $sensorSettings = $sensorObject->sensors;
        $rooms = $this->roomRepository->getAllWithoutCommonToArray();
        $sources = [];

        if ($sensorSettings->where('name', 'source')->first()?->value == 'megad') {
            $sources = $this->deviceRepository->getAllByTypesToArray([
                'MegaD-2561', 'Monoblock 14IN/14OUT'
            ]);
        } elseif ($sensorSettings->where('name', 'source')->first()?->value == 'modbus') {
            $sources = $this->modbusRepository->getAllSlaversToArray();
        }

        return view('sensors.edit', compact(
            'rooms', 'sensorObject', 'sensorSettings', 'sources'
        ));
    }

    public function update(UpdateRequest $request, HomeObject $sensorObject)
    {
        try {
            if ($this->service->update($sensorObject, $request->except('_token'))) {
                return redirect()
                    ->route('sensors.edit', [$sensorObject->id])
                    ->with('success', 'Датчик успешно изменен');
            }
        } catch (\Throwable $e) {
            Log::error(
                'Ошибка при изменении датчика '
                .json_encode($request->all()).' '.$e->getMessage()
            );
        }

        return back()->withInput($request->all())
            ->with('error', 'Ошибка при изменении датчика');
    }

    public function create()
    {
        $types = Sensor::getTypes();
        $sources = Sensor::getSources();
        $rooms = $this->roomRepository->getAllWithoutCommonToArray();

        return view('sensors.create', compact(
            'rooms', 'types', 'sources',
        ));
    }

    public function store(CreateRequest $request)
    {
        try {
            if ($id = $this->service->store($request->except('_token'))) {
                return redirect()
                    ->route('sensors.edit', [$id])
                    ->with('success', 'Датчик успешно добавлен');
            }
        } catch (\Throwable $e) {
            Log::error(
                'Ошибка при добавлении датчика '
                .json_encode($request->all()).' '.$e->getMessage()
            );
        }

        return back()->withInput($request->all())
            ->with('error', 'Ошибка при добавлении датчика');
    }
}

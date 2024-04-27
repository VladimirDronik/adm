<?php

namespace App\Http\Controllers;

use App\Models\Usensor;
use App\Services\PortService;
use App\Services\UsensorService;
use Illuminate\Support\Facades\Log;
use App\Repositories\RoomRepository;
use App\Repositories\DeviceRepository;
use App\Repositories\UsensorRepository;
use App\Http\Requests\Usensor\UsensorRequest;

class UsensorController extends Controller
{
    public function __construct(
        private RoomRepository $roomRep,
        private UsensorService $service,
        private PortService $portService,
        private DeviceRepository $deviceRep,
        private UsensorRepository $usensoRep
    ) {
    }

    public function index()
    {
        $usensors = $this->usensoRep->getAll();

        return view('usensors.index', compact('usensors'));
    }

    private function getLists()
    {
        $types = Usensor::getTypes(true);
        $rooms = $this->roomRep->getAllWithoutCommonToArray();
        $devices = $this->deviceRep->getAllWithoutTypesToArray(['Hite-pro']);

        return [$types, $rooms, $devices];
    }

    public function create()
    {
        [$types, $rooms, $devices] = $this->getLists();

        return view('usensors.create', compact('rooms', 'devices', 'types'));
    }

    public function store(UsensorRequest $r)
    {
        try {
            if ($id = $this->service->store($r->except('_token'))) {
                return redirect()->route('usensors.edit', [$id])
                    ->with('success', 'I2C датчик успешно добавлен');
            }
        } catch (\Throwable $e) {
            Log::error('Ошибка при добавлении I2C датчика ' . json_encode($r->all()) . ' ' . $e->getMessage());
        }

        return back()->withInput($r->all())->with('error', 'Ошибка при добавлении I2C датчика');
    }

    public function edit(Usensor $usensor)
    {
        [$types, $rooms, $devices] = $this->getLists();

        $ports = $this->portService->getPortsIntoList($usensor->device_id, 'IN,I2C,1WIRE,1W-BUS,ADC');

        return view('usensors.edit', compact('usensor', 'rooms', 'devices', 'ports', 'types'));
    }

    public function update(UsensorRequest $r, Usensor $usensor)
    {
        try {
            if ($this->service->update($usensor, $r->except('_token'))) {
                return redirect()->route('usensors.edit', [$usensor->id])
                    ->with('success', 'I2C датчик успешно изменен');
            }
        } catch (\Throwable $e) {
            Log::error('Ошибка при изменении I2C датчика ' . $usensor->id . ' ' . json_encode($r->all()) . ' ' . $e->getMessage());
        }

        return back()->withInput($r->all())->with('error', 'Ошибка при изменении I2C датчика');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\ModbusBus;
use App\Services\Modbus\BusService;
use Illuminate\Support\Facades\Log;
use App\Repositories\ModbusRepository;
use App\Http\Requests\Modbus\Bus\CreateRequest;
use App\Http\Requests\Modbus\Bus\UpdateRequest;

class ModbusBusController extends Controller
{
    public function __construct(
        private ModbusRepository $modbusRep,
        private BusService $service
    ) {
    }

    public function index()
    {
        $tab = request()->input('tab') ?? ModbusBus::TYPE_RTU;

        $rtuBuses = $this->modbusRep->getAllBusesByType(ModbusBus::TYPE_RTU);
        $tcpBuses = $this->modbusRep->getAllBusesByType(ModbusBus::TYPE_TCP);

        return view('mod_bus.bus.index', compact(
            'rtuBuses', 'tcpBuses', 'tab'
        ));
    }

    public function edit($id)
    {
        $bus = ModbusBus::findOrFail($id);
        $baudrates = ModbusBus::getSelectableBaudrate();
        $parities = ModbusBus::getSelectableParity();
        $stopbits = ModbusBus::getSelectableStopbits();

        return view('mod_bus.bus.edit', compact(
            'baudrates', 'parities', 'stopbits', 'bus'
        ));
    }

    public function update(UpdateRequest $r, ModbusBus $bus)
    {
        try {
            if ($this->service->update($bus, $r->except('_token'))) {
                return redirect()
                    ->route('mod_bus.buses.edit', [$bus->id])
                    ->with('success', 'Шина успешно изменена');
            }
        } catch (\Throwable $e) {
            Log::error(
                'Ошибка при изменении шины '
                .json_encode($r->all()).' '.$e->getMessage()
            );
        }

        return back()->withInput($r->all())
            ->with('error', 'Ошибка при изменении шины');
    }

    public function create()
    {
        $types = ModbusBus::getTypes();
        $baudrates = ModbusBus::getSelectableBaudrate();
        $parities = ModbusBus::getSelectableParity();
        $stopbits = ModbusBus::getSelectableStopbits();
        $devices = ModbusBus::getSelectableDevice();

        return view('mod_bus.bus.create', compact(
            'types', 'baudrates', 'parities', 'stopbits', 'devices'
        ));
    }

    public function store(CreateRequest $r)
    {
        try {
            if ($id = $this->service->store($r->except('_token'))) {
                return redirect()
                    ->route('mod_bus.buses.edit', [$id])
                    ->with('success', 'Шина успешно добавлена');
            }
        } catch (\Throwable $e) {
            Log::error(
                'Ошибка при добавлении шины '
                .json_encode($r->all()).' '.$e->getMessage()
            );
        }

        return back()->withInput($r->all())
            ->with('error', 'Ошибка при добавлении шины');
    }
}

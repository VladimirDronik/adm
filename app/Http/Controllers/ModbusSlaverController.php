<?php

namespace App\Http\Controllers;

use App\Http\Requests\Modbus\Slaver\CreateRequest;
use App\Http\Requests\Modbus\Slaver\UpdateRequest;
use App\Models\ModbusSlaver;
use App\Models\ModbusSlaversType;
use App\Repositories\ModbusRepository;
use App\Services\Modbus\SlaverService;
use Illuminate\Http\Request;

class ModbusSlaverController extends Controller
{
    public function __construct(
        private ModbusRepository $modbusRep,
        private SlaverService $service
    ) {
    }

    public function index(Request $r)
    {
        $filterBus = $r->input('bus');

        $buses = $this->modbusRep->getBusesWhereHasSlaversToArray();
        $slavers = $this->modbusRep->getAllSlavers($filterBus);

        return view('mod_bus.slaver.index', compact('slavers', 'buses', 'filterBus'));
    }

    public function edit($id)
    {
        $slaver = ModbusSlaver::findOrFail($id);
        $types = $this->modbusRep->getAllSlaversTypesToArray();
        $buses = $this->modbusRep->getAllBusesToArray();
        $wbLedOperModes = ModbusSlaver::getWbLedOperModes();
        $wbLedModeRegisterId = null;

        if ($slaver->relatedType->type == 'wb-led') {
            $wbLedModeRegister = $slaver->registers()->where('alias', 'wb_led_mode')->first();
            $wbLedModeRegisterId = $wbLedModeRegister ? $wbLedModeRegister->id : null;
        }

        return view('mod_bus.slaver.edit', compact('slaver', 'types', 'buses', 'wbLedOperModes', 'wbLedModeRegisterId'));
    }

    public function update(UpdateRequest $r, ModbusSlaver $slaver)
    {
        try {
            if ($this->service->update($slaver, $r->except('_token'))) {
                return redirect()->route('mod_bus.slavers.edit', [$slaver->id])
                    ->with('success', 'Устройство успешно изменено');
            }
        } catch (\Throwable $e) {
            \Log::error('Ошибка при изменении устройства '.
                json_encode($r->all()).' '.$e->getMessage());
        }

        return back()->withInput($r->all())->with('error', 'Ошибка при изменении устройства');
    }

    public function create()
    {
        $types = ModbusSlaversType::get();
        $buses = $this->modbusRep->getAllBusesToArray();
        $wbLedOperModes = ModbusSlaver::getWbLedOperModes();

        return view('mod_bus.slaver.create', compact('types', 'buses', 'wbLedOperModes'));
    }

    public function store(CreateRequest $r)
    {
        try {
            if ($id = $this->service->store($r->except('_token'))) {
                return redirect()->route('mod_bus.slavers.edit', [$id])
                    ->with('success', 'Устройство успешно добавлено');
            }
        } catch (\Throwable $e) {
            \Log::error('Ошибка при добавлении устройства '.
                json_encode($r->all()).' '.$e->getMessage());
        }

        return back()->withInput($r->all())->with('error', 'Ошибка при добавлении устройства');
    }
}

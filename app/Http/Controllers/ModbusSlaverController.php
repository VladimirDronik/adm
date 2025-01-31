<?php

namespace App\Http\Controllers;

use App\Models\ModbusSlaver;
use Illuminate\Http\Request;
use App\Models\ModbusSlaversType;
use Illuminate\Support\Facades\Log;
use App\Repositories\ModbusRepository;
use App\Services\Modbus\SlaverService;
use App\Http\Requests\Modbus\Slaver\CreateRequest;
use App\Http\Requests\Modbus\Slaver\UpdateRequest;

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

        return view('mod_bus.slaver.index', compact(
            'slavers', 'buses', 'filterBus'
        ));
    }

    public function edit(int $id)
    {
        $slaver = ModbusSlaver::findOrFail($id);
        $buses = $this->modbusRep->getAllBusesToArray();
        $wbLedOperModes = ModbusSlaver::getWbLedOperModes();
        $wbLedModeRegisterId = null;
        $daliDeviceGroups = null;
        $daliDeviceGroupsSelection = [];
        $purposes = ModbusSlaversType::getPurposes();

        if ($slaver->relatedType->type == 'ecodim-dali-gw2') {
            $daliDeviceGroups = $slaver->daliDevices()->where('is_group', 1)->get();
            $existingGroupsAddress = $daliDeviceGroups->pluck('address')->toArray();

            $daliDeviceGroupsSelection = array_filter(
                [
                    'Группа 0', 'Группа 1',
                    'Группа 2', 'Группа 3',
                    'Группа 4', 'Группа 5',
                    'Группа 6', 'Группа 7',
                    'Группа 8', 'Группа 9',
                    'Группа 10', 'Группа 11',
                    'Группа 12', 'Группа 13',
                    'Группа 14', 'Группа 15',
                ],
                function ($key) use ($existingGroupsAddress) {
                    return !in_array($key, $existingGroupsAddress);
                },
                ARRAY_FILTER_USE_KEY
            );
        }

        if ($slaver->relatedType->type == 'wb-led') {
            $wbLedModeRegister = $slaver->registers()->where('alias', 'wb_led_mode')->first();
            $wbLedModeRegisterId = $wbLedModeRegister ? $wbLedModeRegister->id : null;
        }

        return view('mod_bus.slaver.edit', compact(
            'slaver', 'buses', 'wbLedOperModes', 'purposes',
            'wbLedModeRegisterId', 'daliDeviceGroups', 'daliDeviceGroupsSelection'
        ));
    }

    public function update(UpdateRequest $r, ModbusSlaver $slaver)
    {
        try {
            if ($this->service->update($slaver, $r->except('_token'))) {
                return redirect()
                    ->route('mod_bus.slavers.edit', [$slaver->id])
                    ->with('success', 'Устройство успешно изменено');
            }
        } catch (\Throwable $e) {
            Log::error(
                'Ошибка при изменении устройства '
                .json_encode($r->all()).' '.$e->getMessage()
            );
        }

        return back()->withInput($r->all())
            ->with('error', 'Ошибка при изменении устройства');
    }

    public function create()
    {
        $purposes = ModbusSlaversType::getPurposes();
        $types = ModbusSlaversType::where('type', '!=', 'custom')->get();
        $buses = $this->modbusRep->getAllBusesToArray();
        $wbLedOperModes = ModbusSlaver::getWbLedOperModes();

        return view('mod_bus.slaver.create', compact(
            'types', 'buses', 'wbLedOperModes', 'purposes'
        ));
    }

    public function store(CreateRequest $r)
    {
        try {
            if ($id = $this->service->store($r->except('_token'))) {
                return redirect()
                    ->route('mod_bus.slavers.edit', [$id])
                    ->with('success', 'Устройство успешно добавлено');
            }
        } catch (\Throwable $e) {
            Log::error(
                'Ошибка при добавлении устройства '
                .json_encode($r->all()).' '.$e->getMessage()
            );
        }

        return back()->withInput($r->all())
            ->with('error', 'Ошибка при добавлении устройства');
    }
}

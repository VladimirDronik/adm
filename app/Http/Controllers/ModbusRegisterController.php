<?php

namespace App\Http\Controllers;

use App\Http\Requests\Modbus\Register\CreateRequest;
use App\Http\Requests\Modbus\Register\UpdateRequest;
use App\Models\ModbusRegister;
use App\Repositories\ModbusRepository;
use App\Services\Modbus\RegisterService;
use Illuminate\Http\Request;

class ModbusRegisterController extends Controller
{
    public function __construct(
        private ModbusRepository $modbusRep,
        private RegisterService $service
    ) {
    }

    public function index(Request $r)
    {
        $filterSlaver = $r->input('slaver');
        $filterSystem = $r->input('is_system');

        $slavers = $this->modbusRep->getSlaversWhereHasRegistersToArray();
        $registers = $this->modbusRep->getAllRegisters($filterSlaver, $filterSystem);

        return view('mod_bus.register.index', compact('registers', 'slavers', 'filterSlaver', 'filterSystem'));
    }

    public function edit($id)
    {
        $register = ModbusRegister::findOrFail($id);
        $slavers = $this->modbusRep->getAllSlaversToArray();
        $types = ModbusRegister::getTypes();
        $dataFormats = ModbusRegister::getSelectableDataFormat();
        $accesses = ModbusRegister::getSelectableAccess();
        $pollingCycles = ModbusRegister::getSelectablePollingCycle();

        return view('mod_bus.register.edit', compact('register', 'slavers', 'types', 'dataFormats', 'accesses', 'pollingCycles'));
    }

    public function update(UpdateRequest $r, ModbusRegister $register)
    {
        try {
            if ($this->service->update($register, $r->except('_token'))) {
                return redirect()->route('mod_bus.registers.edit', [$register->id])
                    ->with('success', 'Регистр успешно изменен');
            }
        } catch (\Throwable $e) {
            \Log::error('Ошибка при изменении регистра '.
                json_encode($r->all()).' '.$e->getMessage());
        }

        return back()->withInput($r->all())->with('error', 'Ошибка при изменении регистра');
    }

    public function create()
    {
        $slavers = $this->modbusRep->getAllSlaversToArray();
        $types = ModbusRegister::getTypes();
        $dataFormats = ModbusRegister::getSelectableDataFormat();
        $accesses = ModbusRegister::getSelectableAccess();
        $pollingCycles = ModbusRegister::getSelectablePollingCycle();

        return view('mod_bus.register.create', compact('slavers', 'types', 'dataFormats', 'accesses', 'pollingCycles'));
    }

    public function store(CreateRequest $r)
    {
        try {
            if ($id = $this->service->store($r->except('_token'))) {
                return redirect()->route('mod_bus.registers.edit', [$id])
                    ->with('success', 'Регистр успешно добавлен');
            }
        } catch (\Throwable $e) {
            \Log::error('Ошибка при добавлении регистра '.
                json_encode($r->all()).' '.$e->getMessage());
        }

        return back()->withInput($r->all())->with('error', 'Ошибка при добавлении регистра');
    }
}

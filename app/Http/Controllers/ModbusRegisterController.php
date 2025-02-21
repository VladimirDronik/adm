<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ModbusRegister;
use Illuminate\Support\Facades\Log;
use App\Repositories\ModbusRepository;
use App\Services\Modbus\RegisterService;
use App\Http\Requests\Modbus\Register\CreateRequest;
use App\Http\Requests\Modbus\Register\UpdateRequest;

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

        $slavers = $this->modbusRep->getSlaversWhereHasRegistersToArray();
        $registers = $this->modbusRep->getAllRegisters($filterSlaver, 1);

        return view('mod_bus.register.index', compact(
            'registers', 'slavers', 'filterSlaver'
        ));
    }

    public function edit($id)
    {
        $register = ModbusRegister::findOrFail($id);
        $slavers = $this->modbusRep->getAllSlaversToArray();
        $dataFormats = ModbusRegister::getSelectableDataFormat();
        $accesses = ModbusRegister::getSelectableAccess();

        return view('mod_bus.register.edit', compact(
            'register', 'slavers', 'dataFormats', 'accesses'
        ));
    }

    public function update(UpdateRequest $r, ModbusRegister $register)
    {
        try {
            if ($this->service->update($register, $r->except('_token'))) {
                return redirect()
                    ->route('mod_bus.registers.edit', [$register->id])
                    ->with('success', 'Регистр успешно изменен');
            }
        } catch (\Throwable $e) {
            Log::error(
                'Ошибка при изменении регистра '
                .json_encode($r->all()).' '.$e->getMessage()
            );
        }

        return back()->withInput($r->all())
            ->with('error', 'Ошибка при изменении регистра');
    }

    public function create()
    {
        $slavers = $this->modbusRep->getAllSlaversToArray();
        $dataFormats = ModbusRegister::getSelectableDataFormat();
        $accesses = ModbusRegister::getSelectableAccess();

        return view('mod_bus.register.create', compact(
            'slavers', 'dataFormats', 'accesses'
        ));
    }

    public function store(CreateRequest $r)
    {
        try {
            if ($id = $this->service->store($r->except('_token'))) {
                return redirect()
                    ->route('mod_bus.registers.edit', [$id])
                    ->with('success', 'Регистр успешно добавлен');
            }
        } catch (\Throwable $e) {
            Log::error(
                'Ошибка при добавлении регистра '
                .json_encode($r->all()).' '.$e->getMessage()
            );
        }

        return back()->withInput($r->all())
            ->with('error', 'Ошибка при добавлении регистра');
    }
}

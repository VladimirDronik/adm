<?php

namespace App\Http\Controllers;

use App\Models\Boiler;
use App\Models\HomeObject;
use App\Services\BoilerService;
use Illuminate\Support\Facades\Log;
use App\Repositories\BoilerRepository;
use App\Repositories\DeviceRepository;
use App\Repositories\ModbusRepository;
use App\Repositories\ScriptRepository;
use App\Repositories\TermostatRepository;
use App\Http\Requests\Boiler\CreateRequest;
use App\Http\Requests\Boiler\UpdateRequest;

class BoilerController extends Controller
{
    public function __construct(
        private BoilerRepository $boilerRepository,
        private BoilerService $service,
        private TermostatRepository $termostatRepository,
        private ModbusRepository $modbusRepository,
        private DeviceRepository $deviceRepository,
        private ScriptRepository $scriptRepository,
    ) {
    }

    public function edit(int $boilerIdObject)
    {
        $boiler = $this->boilerRepository->getBoiler($boilerIdObject);
        $termostats = $this->termostatRepository->getAllWithIdObjectToArray();
        $scripts = $this->scriptRepository->getAllToArray();
        $modbusSlavers = null;
        $devices = null;
        $methodsIdWithRegisters = [];
        $modes = Boiler::getModes();
        $heatingModes = Boiler::getHeatingModes();

        if ($boiler->gateway_type == HomeObject::GATEWAY_MODBUS) {
            $modbusSlavers = $this->modbusRepository->getFilteredSlaversToArray(['heat']);

            foreach ($boiler->object->methods as $method) {
                $methodsIdWithRegisters[$method->id] = $method->register ? $method->register->id : 0;
            }
        } else {
            $devices = $this->deviceRepository->getAllToArray();
        }

        return view('engineering.boiler.edit', compact(
            'modes', 'heatingModes', 'boiler', 'termostats',
            'modbusSlavers', 'devices', 'methodsIdWithRegisters', 'scripts'
        ));
    }

    public function update(UpdateRequest $r, int $idObject)
    {
        $boiler = $this->boilerRepository->getBoiler($idObject);

        try {
            if ($this->service->update($boiler, $r->except('_token'))) {
                return redirect()
                    ->route('boiler.edit', [$boiler->id_object])
                    ->with('success', 'Настройки успешно изменены');
            }
        } catch (\Throwable $e) {
            Log::error(
                'Ошибка при изменении настроек котла '.$boiler->id_object
                .' '.json_encode($r->all()).' '.$e->getMessage()
            );
        }

        return back()->withInput($r->all())
            ->with('error', 'Ошибка при изменении настроек котла');
    }

    public function store(CreateRequest $r)
    {
        try {
            if ($id = $this->service->store($r->except('_token'))) {
                return redirect()->route('engineering.index')
                    ->with('success', 'Котёл успешно добавлен')
                    ->with('idObject', $id);
            }
        } catch (\Throwable $e) {
            Log::error(
                'Ошибка при добавлении котла '
                .json_encode($r->all()).' '.$e->getMessage()
            );
        }

        return back()->withInput($r->all())
            ->with('error', 'Ошибка при добавлении котла');
    }

    public function create()
    {
        $typesBoiler = Boiler::getExchangeProtocols();
        $types = Boiler::getTypes();
        $modes = Boiler::getModes();
        $termostats = $this->termostatRepository->getAllWithIdObjectToArray();
        $modbusSlavers = $this->modbusRepository->getFilteredSlaversToArray(['heat']);
        $devices = $this->deviceRepository->getAllToArray();
        $gatewayTypes = HomeObject::getGatewayTypes();

        return view('engineering.boiler.create', compact(
            'typesBoiler', 'types', 'modes', 'termostats',
            'modbusSlavers', 'devices', 'gatewayTypes'
        ));
    }
}

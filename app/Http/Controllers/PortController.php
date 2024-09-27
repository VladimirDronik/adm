<?php

namespace App\Http\Controllers;

use App\Models\Port;
use App\Models\Device;
use App\Services\PortService;
use Illuminate\Support\Facades\Log;
use App\Http\Requests\Port\UpdateRequest;

class PortController extends Controller
{
    public function __construct(
        private PortService $service
    ) {
    }

    public function store(UpdateRequest $r)
    {
        $id_tab = 'tab='.$r->tab;

        try {
            if ($this->service->store($r->except('_token'))) {
                return redirect()
                    ->route('devices.edit', [$r->id_controller, $id_tab]);
            }
        } catch (\Throwable $e) {
            Log::error(
                'Ошибка при изменении порта '
                .json_encode($r->all()).' '.$e->getMessage()
            );
        }

        return back()->withInput($r->all())
            ->with('error', 'Ошибка при изменении порта. '.$e->getMessage());
    }

    /**
     * Редактирование настроек порта
     */
    public function edit(int $idPort)
    {
        $port = Port::where('id', $idPort)->first();

        $device = Device::where('id', $port->id_device)
            ->with(
                'ports', 'ports.eobject', 'ports.emethod', 'ports.dcmethod', 'ports.lcmethod',
                'ports.emethod.eobject', 'ports.dcmethod.eobject', 'ports.lcmethod.eobject'
            )->first();

        return view('devices.editport', compact('port', 'device'));
    }
}

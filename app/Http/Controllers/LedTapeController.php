<?php

namespace App\Http\Controllers;

use App\Models\LedTape;
use App\Repositories\DeviceRepository;
use App\Services\DeviceService;
use App\Services\LedTapeService;
use App\Services\PortService;
use Illuminate\Http\Request;

class LedTapeController extends Controller
{
    public function __construct(
        private LedTapeService $service,
        private DeviceRepository $deviceRep,
        private PortService $portService,
        private DeviceService $deviceService
    ) {
    }

    public function edit($id)
    {
        $ledTape = LedTape::findOrFail($id);
        $devices = $this->deviceRep->getAllByTypesToArray(['WB-LED']);
        list($deviceId, $portIds) = $this->portService->getCurrentDeviceAndPortsForLedTape($ledTape);

        $deviceData = $this->deviceService
            ->getAllPortsDataForWbLed($deviceId, 'out', $ledTape->type, $ledTape->id_object);

        return view('led_tapes.edit', compact('ledTape', 'devices', 'deviceId', 'portIds', 'deviceData'));
    }

    public function create()
    {
        $types = LedTape::getTypes(true);
        $devices = $this->deviceRep->getAllByTypesToArray(['WB-LED']);

        return view('led_tapes.create', compact('types', 'devices'));
    }

    public function store(Request $r)
    {
        try {
            if ($id = $this->service->store($r->except('_token'))) {
                return redirect()->route('led_tapes.edit', [$id])
                    ->with('success', 'Led лента успешно добавлена');
            }
        } catch (\Throwable $e) {
            \Log::error('Ошибка при добавлении led ленты ' .
                json_encode($r->all()).' '.$e->getMessage());
        }

        return back()->withInput($r->all())->with('error', 'Ошибка при добавлении led ленты');
    }

    public function update(Request $r, LedTape $ledTape)
    {
        try {
            if ($this->service->update($ledTape, $r->except('_token'))) {
                return redirect()->route('led_tapes.edit', [$ledTape->id])
                    ->with('success', 'Led лента успешно изменена');
            }
        } catch (\Throwable $e) {
            \Log::error('Ошибка при изменении Led ленты ' .
                json_encode($r->all()).' '.$e->getMessage());
        }

        return back()->withInput($r->all())->with('error', 'Ошибка при изменении Led ленты');
    }
}

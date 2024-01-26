<?php

namespace App\Http\Controllers;

use App\Models\DaliDevice;
use App\Services\Modbus\SlaverService;
use Illuminate\Http\Request;

class DaliDeviceController extends Controller
{
    public function __construct(
        private SlaverService $service
    ) {
    }

    public function edit($id)
    {
        $daliDevice = DaliDevice::findOrFail($id);

        return view('mod_bus.dali_device.edit', compact('daliDevice'));
    }

    public function update(Request $r, DaliDevice $daliDevice)
    {
        try {
            if ($this->service->updateDaliDevice($daliDevice, $r->except('_token'))) {
                return redirect()->route('mod_bus.dali_devices.edit', [$daliDevice->id])
                    ->with('success', 'Устройство DALI успешно изменено');
            }
        } catch (\Throwable $e) {
            \Log::error('Ошибка при изменении устройства DALI '.
                json_encode($r->all()).' '.$e->getMessage());
        }

        return back()->withInput($r->all())->with('error', 'Ошибка при изменении устройства DALI');
    }
}

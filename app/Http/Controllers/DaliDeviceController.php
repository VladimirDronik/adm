<?php

namespace App\Http\Controllers;

use App\Models\DaliDevice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Repositories\RoomRepository;
use App\Repositories\ModbusRepository;
use App\Services\Modbus\SlaverService;
use App\Repositories\AliceDevicesRepository;

class DaliDeviceController extends Controller
{
    public function __construct(
        private SlaverService $service,
        private RoomRepository $roomRep,
        private ModbusRepository $modbusRep,
        private AliceDevicesRepository $aliceRepository,
    ) {
    }

    public function edit($id, int $tab = 1)
    {
        $daliDevice = DaliDevice::findOrFail($id);
        $alice = null;

        if ($daliDevice->id_object) {
            $alice = $this->aliceRepository
                ->getNameAndRoomByObject($daliDevice->id_object);
        }

        if ($daliDevice->is_group) {
            $daliDevices = $this->modbusRep
                ->getDaliDevicesNotRelatedToCurrentGroupToArray($daliDevice->id);
        } else {
            $daliDevices = [];
        }

        $rooms = $this->roomRep->getAllWithoutCommonToArray();

        return view('mod_bus.dali_device.edit', compact(
            'daliDevice', 'rooms', 'daliDevices', 'tab', 'alice'
        ));
    }

    public function update(Request $r, DaliDevice $daliDevice)
    {
        try {
            if ($this->service->updateDaliDevice($daliDevice, $r->except('_token'))) {
                return redirect()
                    ->route('mod_bus.dali_devices.edit', [$daliDevice->id])
                    ->with('success', 'Устройство DALI успешно изменено');
            }
        } catch (\Throwable $e) {
            Log::error(
                'Ошибка при изменении устройства DALI '
                .json_encode($r->all()).' '.$e->getMessage()
            );
        }

        return back()->withInput($r->all())
            ->with('error', 'Ошибка при изменении устройства DALI');
    }
}

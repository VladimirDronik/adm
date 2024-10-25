<?php

namespace App\Http\Controllers;

use App\Models\LedTape;
use Illuminate\Http\Request;
use App\Services\PortService;
use App\Services\DeviceService;
use App\Services\LedTapeService;
use Illuminate\Support\Facades\Log;
use App\Repositories\RoomRepository;
use App\Repositories\DeviceRepository;
use App\Repositories\AliceDevicesRepository;

class LedTapeController extends Controller
{
    public function __construct(
        private LedTapeService $service,
        private DeviceRepository $deviceRep,
        private PortService $portService,
        private DeviceService $deviceService,
        private RoomRepository $roomRep,
        private AliceDevicesRepository $aliceRepository
    ) {
    }

    public function edit(int $id, int $tab = 1)
    {
        $ledTape = LedTape::findOrFail($id);
        $rooms = $this->roomRep->getAllWithoutCommonToArray();
        $alice = $this->aliceRepository
            ->getNameAndRoomByObject($ledTape->id_object);

        return view('led_tapes.edit', compact('ledTape', 'rooms', 'tab', 'alice'));
    }

    public function create()
    {
        $types = LedTape::getTypes(true);
        $rooms = $this->roomRep->getAllWithoutCommonToArray();

        return view('led_tapes.create', compact('types', 'rooms'));
    }

    public function store(Request $r)
    {
        try {
            if ($id = $this->service->store($r->except('_token'))) {
                return redirect()
                    ->route('led_tapes.edit', [$id])
                    ->with('success', 'Led лента успешно добавлена');
            }
        } catch (\Throwable $e) {
            Log::error(
                'Ошибка при добавлении led ленты '
                .json_encode($r->all()).' '.$e->getMessage()
            );
        }

        return back()->withInput($r->all())
            ->with('error', 'Ошибка при добавлении led ленты');
    }

    public function update(Request $r, LedTape $ledTape)
    {
        try {
            if ($this->service->update($ledTape, $r->except('_token'))) {
                return redirect()
                    ->route('led_tapes.edit', [$ledTape->id])
                    ->with('success', 'Led лента успешно изменена');
            }
        } catch (\Throwable $e) {
            Log::error(
                'Ошибка при изменении Led ленты '
                .json_encode($r->all()).' '.$e->getMessage()
            );
        }

        return back()->withInput($r->all())
            ->with('error', 'Ошибка при изменении Led ленты');
    }
}

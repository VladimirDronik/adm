<?php

namespace App\Http\Controllers;

use App\Models\LedTape;
use App\Repositories\DeviceRepository;
use App\Repositories\RoomRepository;
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
        private DeviceService $deviceService,
        private RoomRepository $roomRep
    ) {
    }

    public function edit($id)
    {
        $ledTape = LedTape::findOrFail($id);
        $rooms = $this->roomRep->getAllWithoutCommonToArray();

        return view('led_tapes.edit', compact('ledTape', 'rooms'));
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

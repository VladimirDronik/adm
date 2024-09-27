<?php

namespace App\Http\Controllers;

use App\Models\YandexStation;
use App\Repositories\RoomRepository;
use App\Services\YandexStationService;
use App\Repositories\YandexStationRepository;
use App\Http\Requests\YandexStation\UpdateRequest;
use Illuminate\Support\Facades\Log;

class YandexStationController extends Controller
{
    public function __construct(
        private YandexStationRepository $yandexstationRep,
        private RoomRepository $roomRep,
        private YandexStationService $service
    ) {
    }

    public function index()
    {
        $yandexstations = $this->yandexstationRep->getAll();

        return view('yandexstations.index', compact('yandexstations'));
    }

    public function edit(YandexStation $yandexstation)
    {
        $rooms = $this->roomRep->getAllToArray();

        return view('yandexstations.edit', compact(
            'rooms', 'yandexstation'
        ));
    }

    public function update(UpdateRequest $r, YandexStation $yandexstation)
    {
        try {
            if ($this->service->update($yandexstation, $r->except('_token'))) {
                return redirect()
                    ->route('yandexstations.edit', [$yandexstation->id])
                    ->with('success', 'Станция успешно изменена');
            }
        } catch (\Throwable $e) {
            Log::error(
                'Ошибка при изменении станции '.$yandexstation->id
                .' '.json_encode($r->all()).' '.$e->getMessage()
            );
        }

        return back()->withInput($r->all())
            ->with('error', 'Ошибка при изменении станции');
    }

    public function resetUser()
    {
        $cookie = base_path(config('yandex.cookie_file'));
        $token = base_path(config('yandex.token_file'));

        if (file_exists($cookie)) {
            unlink($cookie);
        }

        if (file_exists($token)) {
            unlink($token);
        }

        return redirect()->route('yandexstations.index')
            ->with('success', 'Пользователь отвязан');
    }
}

<?php

namespace App\Http\Controllers;

use App\Http\Requests\YandexStation\UpdateRequest;
use App\Models\YandexStation;
use App\Repositories\RoomRepository;
use App\Repositories\YandexStationRepository;
use App\Services\YandexStationService;

class YandexStationController extends Controller
{

    private $yandexstation_rep;
    private $room_rep;
    private $service;

    public function __construct(YandexStationRepository $yandexstationRepository, RoomRepository $roomRepository,
    YandexStationService $yandexStationService)
    {
        $this->yandexstation_rep = $yandexstationRepository;
        $this->room_rep = $roomRepository;
        $this->service = $yandexStationService;
    }

    public function index()
    {
        $yandexstations = $this->yandexstation_rep->getAll();

        return view('yandexstations.index', compact('yandexstations'));
    }

    public function edit(YandexStation $yandexstation)
    {
        $rooms = $this->room_rep->getAllToArray();

        return view('yandexstations.edit', compact( 'rooms', 'yandexstation' ));
    }

    public function update(UpdateRequest $r, YandexStation $yandexstation)
    {
        try {
            if ($this->service->update($yandexstation, $r->except('_token'))) {
                return redirect()->route('yandexstations.edit',[$yandexstation->id])
                    ->with('success', 'Станция успешно изменена');
            }
        } catch (\Throwable $e) {
            \Log::error('Ошибка при изменении станции '.$yandexstation->id
                .' ' .json_encode($r->all()).' '.$e->getMessage());
        }

        return back()->withInput($r->all())->with('error', 'Ошибка при изменении станции');
    }

    public function resetUser()
    {
        if (file_exists(base_path('yandex_token.json'))) {
            unlink(base_path('yandex_token.json'));

            return redirect()->route('yandexstations.index')
                ->with('success', 'Пользователь отвязан');
        }
    }
}

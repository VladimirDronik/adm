<?php

namespace App\Services;

use App\Models\YandexStation;
use App\Repositories\RoomRepository;

class YandexStationService
{
    private $roomRepository;

    public function __construct(RoomRepository $roomRepository)
    {
        $this->roomRepository = $roomRepository;
    }

    public function store(array $data): bool
    {
        $station = YandexStation::where('speaker_id', $data['iot_id'])->first();

        if (!$station) {
            $station = new YandexStation();
        }

        $station->name = $data['name'];
        $station->platform = $data['platform'];
        $station->device_id = $data['device_id'];
        $station->speaker_id = $data['iot_id'];
        $station->scenario_id = $data['scenario_id'];
        $station->active = 1;

        $room = $this->roomRepository->getByName($data['room']);
        if ($room) {
            $station->room = $room->id;
        } else {
            $station->room = null;
        }

        $station->save();

        return true;
    }

    public function update(YandexStation $station, array $data)
    {
        if (($data['room'] ?? 0) == 0) {
            $data['room'] = null;
        }

        $station->fill($data);
        $station->save();

        return $station->id;
    }

    public function delete(int $idStation)
    {
        $station = YandexStation::findOrFail($idStation);
        $station->delete();

        return true;
    }
}
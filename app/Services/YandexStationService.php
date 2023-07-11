<?php

namespace App\Services;

use App\Models\YandexStation;

class YandexStationService
{
    public function store(array $devices): bool
    {
        if (!empty($devices)) {
            foreach ($devices as $device) {
                $station = YandexStation::where('speaker_id', $device['id'])->first();
                if (!$station) {
                    $station = new YandexStation();
                }

                $station->speaker_id = $device['id'];
                $station->name = $device['name'];
                $station->active = 1;

                $station->save();
            }
        }

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
<?php

namespace App\Repositories;

use App\Models\YandexStation;

class YandexStationRepository
{
    public function getAll($pagination_count = 30)
    {
        return YandexStation::with('iroom')
            ->orderBy('id', 'desc')
            ->paginate($pagination_count);
    }

    public function getStationsToArray()
    {
        return YandexStation::orderBy('name')->get();
    }
}

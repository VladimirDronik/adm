<?php

namespace App\Repositories;

use App\Models\YandexStation;
use Illuminate\Database\Eloquent\Collection;

class YandexStationRepository
{
    public function getAll(int $perPage = 30)
    {
        return YandexStation::with('iroom')
            ->orderBy('id', 'desc')
            ->paginate($perPage);
    }

    public function getStationsToArray(): Collection
    {
        return YandexStation::orderBy('name')->get();
    }
}

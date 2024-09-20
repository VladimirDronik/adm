<?php

namespace App\Repositories;

use App\Models\Events;
use Illuminate\Database\Eloquent\Collection;

class EventRepository
{
    /**
     * Получить все события объекта
     */
    public function getAllById(int $idObject): Collection
    {
        return Events::where('id_object', $idObject)
            ->orderBy('name')
            ->get();
    }

    /**
     * Получить данные выбранного события
     */
    public function getEvent(int $idEvent): ?Events
    {
        return Events::where('id', $idEvent)->first();
    }
}

<?php

namespace App\Repositories;

use App\Models\Action;
use Illuminate\Database\Eloquent\Collection;

class ActionRepository
{
    /**
     * Отдает все доступные действия для выбранного события
     */
    public function getAllActionsByEvent(int $idEvent): Collection
    {
        return Action::where('id_event', $idEvent)->get();
    }
}

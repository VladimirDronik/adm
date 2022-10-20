<?php

namespace App\Repositories;

use App\Models\Dimmer;

class DimmerRepository {

    public function getAll(int $pagination_count = 30)
    {
        return Dimmer::with('object')->orderBy('id', 'desc')
            ->paginate($pagination_count);
    }
}
<?php

namespace App\Repositories;

use App\Models\Relay;

class RelayRepository
{
    public function getAll($pagination_count = 30)
    {
        return Relay::with('object')->orderBy('id', 'desc')->paginate($pagination_count);
    }
}

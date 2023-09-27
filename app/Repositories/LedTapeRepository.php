<?php

namespace App\Repositories;

use App\Models\LedTape;

class LedTapeRepository
{
    public function getAll($pagination_count = 30)
    {
        return LedTape::paginate($pagination_count);
    }
}

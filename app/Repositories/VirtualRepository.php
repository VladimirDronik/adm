<?php

namespace App\Repositories;

use App\Models\Virtual;

class VirtualRepository
{
    public function getAll($pagination_count = 30)
    {
        return Virtual::with('object')->orderBy('id', 'desc')->paginate($pagination_count);
    }
}

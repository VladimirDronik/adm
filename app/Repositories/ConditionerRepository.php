<?php

namespace App\Repositories;

use App\Models\Conditioner;

class ConditionerRepository
{
    public function getAll(int $perPage = 30)
    {
        return Conditioner::paginate($perPage);
    }
}

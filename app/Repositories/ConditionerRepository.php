<?php

namespace App\Repositories;

use App\Models\Conditioner;

class ConditionerRepository
{
    public function getAll($perPage = 30)
    {
        return Conditioner::paginate($perPage);
    }
}

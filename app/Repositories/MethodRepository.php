<?php

namespace App\Repositories;

use App\Models\Method;

class MethodRepository
{
    public function getAllToArray()
    {
        return Method::select('id','name')->orderBy('id')->pluck('name', 'id')->toArray();
    }
}
<?php

namespace App\Repositories;

use App\Models\DevUser;

class DevuserRepository
{
    public function getAll(int $perPage = 30)
    {
        return DevUser::orderBy('dev_id')->paginate($perPage);
    }
}

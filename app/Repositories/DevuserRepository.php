<?php

namespace App\Repositories;

use App\Models\DevUser;

class DevuserRepository
{
    public function getAll($pagination_count = 30)
    {
        return DevUser::orderBy('dev_id')->paginate($pagination_count);
    }
}

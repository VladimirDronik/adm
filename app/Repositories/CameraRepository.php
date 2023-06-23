<?php

namespace App\Repositories;

use App\Models\Camera;

class CameraRepository
{
    public function getAll($pagination_count = 30)
    {
        return Camera::paginate($pagination_count);
    }
}

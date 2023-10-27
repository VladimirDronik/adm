<?php

namespace App\Repositories;

use App\Models\Camera;
use App\Models\Recorder;

class CctvRepository
{
    public function getAllCameras($pagination_count = 30)
    {
        return Camera::orderBy('sort')->paginate($pagination_count);
    }

    public function getAllRecorders($pagination_count = 30)
    {
        return Recorder::orderBy('sort')->paginate($pagination_count);
    }
}

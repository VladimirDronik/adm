<?php

namespace App\Repositories;

use App\Models\Camera;
use App\Models\Recorder;

class CctvRepository
{
    public function getAllCameras($elementsPerPage = 15)
    {
        return Camera::orderBy('sort')->paginate($elementsPerPage);
    }

    public function getAllRecorders($elementsPerPage = 15)
    {
        return Recorder::orderBy('sort')->paginate($elementsPerPage);
    }

    public function getAllRecordersCameras()
    {
        return Camera::whereNotNull('recorder_id')->get();
    }
}

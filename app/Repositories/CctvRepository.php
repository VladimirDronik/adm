<?php

namespace App\Repositories;

use App\Models\Camera;
use App\Models\Recorder;

class CctvRepository
{
    public function getAllCamerasWithoutRecorder()
    {
        return Camera::whereNull('recorder_id')->orderBy('sort')->get();
    }

    public function getAllRecorders()
    {
        return Recorder::orderBy('sort')->get();
    }

    public function getAllRecordersCameras()
    {
        return Camera::whereNotNull('recorder_id')->get();
    }
}

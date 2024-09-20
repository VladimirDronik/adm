<?php

namespace App\Repositories;

use App\Models\Camera;
use App\Models\Recorder;
use Illuminate\Database\Eloquent\Collection;

class CctvRepository
{
    public function getAllCameras(int $perPage = 15)
    {
        return Camera::orderBy('sort')->paginate($perPage);
    }

    public function getAllRecorders(int $perPage = 15)
    {
        return Recorder::orderBy('sort')->paginate($perPage);
    }

    public function getAllRecordersCameras(): Collection
    {
        return Camera::whereNotNull('recorder_id')->get();
    }
}

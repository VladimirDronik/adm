<?php

namespace App\Repositories;

use App\Models\Motionsensor;

class MotionsensorRepository {


    public function getAll($pagination_count = 30)
    {
        return Motionsensor::with('object')->orderBy('id', 'desc')->paginate($pagination_count);
    }

}
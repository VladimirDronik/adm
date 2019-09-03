<?php

namespace App\Repositories;

use App\Models\SchedulerTask;

class EventRepository {

    public function getAll($pagination_count = 30)
    {
        return SchedulerTask::with('points','emethod')->orderBy('name')->paginate($pagination_count);
    }

}
<?php

namespace App\Repositories;

use App\Models\Cron;
use App\Models\SchedulerTask;

class EventRepository {

    public function getAll()
    {
        $tasks = SchedulerTask::with('points','eobject','emethod')->orderBy('name')->get();
        $crons = Cron::with('eobject','emethod')->orderBy('name')->get();

        $events = $tasks->concat($crons)->sortBy('name');

        return $events;
    }
}
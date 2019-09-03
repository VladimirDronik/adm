<?php

namespace App\Services;

use App\Models\SchedulerPoint;
use App\Models\SchedulerTask;
use Illuminate\Support\Facades\DB;

class EventService {

    public function prepare(SchedulerTask $task, array $data)
    {
        $task->fill($data);
    }

    public function store(array $data)
    {
        $task = new SchedulerTask();
        $this->prepare($task, $data);
        $task->save();

        return $task->id;
    }

    public function update(int $id, array $data)
    {
        $task = SchedulerTask::find($id);

        if (!$task) {
            return false;
        }

        $this->prepare($task, $data);
        $task->save();

        return $task->id;
    }

    public function delete(int $id)
    {
        DB::transaction(function () use ($id) {
            SchedulerPoint::where('id_task', $id)->delete();
            SchedulerTask::destroy($id);
        });

        return true;
    }
}
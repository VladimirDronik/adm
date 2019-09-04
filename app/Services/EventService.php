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

    public function update($task, array $data)
    {
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

    public function validateName(int $id, string $name)
    {
        $event = SchedulerTask::find($id);

        if (!$event) {
            return ['result' => false, 'message' => 'Событие не найдено'];
        }

        $result = !SchedulerTask::where('id', '!=', $id)
            ->where('name', trim($name))->exists();
        $message = $result ? '' : 'Событие с таким названием уже существует. Выберите другое название';

        return compact('result', 'message');
    }
}
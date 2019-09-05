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

    private function getPointToArray(SchedulerPoint $point)
    {
        return [
            'id' => $point->id,
            'type' => $point->type,
            'time' => $point->time,
            'days' => $point->days,
            'close' => $point->close,
            'system' => $point->system,
            'single_rus_type' => $point->single_rus_type,
            'description' => $point->description
        ];
    }

    private function storePointType(SchedulerPoint $point, array $data)
    {
        $point->type = $data['type'];
        $point->time = trim($data['time']);

        switch ($data['type']) {
            case SchedulerPoint::TYPE_CRON:
                if (!SchedulerPoint::isInCronPeriods((int)$point->time)) {
                    throw new \Exception();
                }
                $point->days = '';
                break;
            default:
                $point->days = implode(",",$data['days']);
                break;
        }
    }

    public function storePoint(array $data)
    {\Log::alert($data);
        $task = SchedulerTask::findOrFail((int)$data['event_id']);

        $point = new SchedulerPoint();

        $point->id_task = $task->id;
        $point->close = 0;
        $point->system = 0;

        $this->storePointType($point, $data);

        $point->save();

        return $this->getPointToArray($point);
    }

    public function updatePoint(array $data)
    {
        $point = SchedulerPoint::where('id_task', (int)$data['event_id'])
            ->where('id', (int)$data['id'])->firstOrFail();


    }

    public function storeOrUpdatePoint(array $data)
    {
        return ['data' => empty($data['id']) ? $this->storePoint($data) : $this->updatePoint($data)];
    }

    public function deletePoint(int $id)
    {
        SchedulerPoint::where('close','!=',1)->where('id', $id)->delete();

        return true;
    }
}
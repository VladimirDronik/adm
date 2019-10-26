<?php

namespace App\Repositories;

use App\Models\SchedulerTask;

class EventRepository {

    public function getAll($pagination_count = 30)
    {
        return SchedulerTask::with('points','emethod')->orderBy('name')->paginate($pagination_count);
    }

    public function getByNameAndType(array $filter, $pagination_count = 30)
    {
        $name = trim($filter['name']);
        $type = trim($filter['type']);

        $query = SchedulerTask::with('points', 'emethod', 'escript');

        if ($name !== '') {
            $query->where('name', 'like', '%'.$name.'%');
        }

        if ($type !== '') {
            $query->whereHas('points', function ($q) use ($type) {
                $q->where('type', $type);
            });
        }

        $query->withCount(['points' => function ($q) {
            $q->where('system', SchedulerTask::SYSTEM);
        }]);

        $query->orderBy('points_count', 'desc');

        $query->orderBy('name');

        return $query->paginate($pagination_count);
    }
}
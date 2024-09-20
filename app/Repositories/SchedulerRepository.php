<?php

namespace App\Repositories;

use App\Models\SchedulerTask;

class SchedulerRepository
{
    public function getAll(int $perPage = 30)
    {
        return SchedulerTask::with('points', 'emethod')
            ->orderBy('name')
            ->paginate($perPage);
    }

    public function getByNameAndType(array $filter, bool $withSystem = true, bool $withHidden = true, int $perPage = 30)
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

        if (! $withSystem) {
            $query->notSystem();
        }

        if (! $withHidden) {
            $query->notHidden();
        }

        $query->orderBy('points_count', 'desc');

        $query->orderBy('name');

        return $query->paginate($perPage);
    }
}

<?php

namespace App\Repositories;

use App\Models\Log;

class LogRepository {

    public function getTypes()
    {
        return Log::select('type')->distinct()->orderBy('type')->get()->toArray();
    }

    public function getByFilter(array $filter, $pagination_count = 30)
    {
        $start = trim($filter['start']);
        $end = trim($filter['end']);
        $type = trim($filter['type']);

        $query = Log::query();

        if ($start !== '') {
            $query->where('name', 'like', '%'.$name.'%');
        }

        if ($type !== '') {
            $query->whereHas('points', function ($q) use ($type) {
                $q->where('type', $type);
            });
        }

        $query->orderBy('date', 'desc');

        return $query->paginate($pagination_count);
    }
}
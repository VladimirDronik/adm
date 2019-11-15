<?php

namespace App\Repositories;

use App\Models\Log;
use Carbon\Carbon;

class LogRepository {

    public function getTypes()
    {
        return Log::select('type')->distinct()->orderBy('type')->get()->pluck('type')->toArray();
    }

    public function getByFilter(array $filter, $pagination_count = 30)
    {
        $start = trim($filter['start']);
        $end = trim($filter['end']);
        $type = trim($filter['type']);

        $query = Log::query();

        if ($start !== '') {
            $query->where('date', '>=',
                Carbon::createFromFormat('d.m.Y', $start)->format('Y-m-d 00:00:00'));
        }

        if ($end !== '') {
            $query->where('date', '<=',
                Carbon::createFromFormat('d.m.Y', $end)->format('Y-m-d 23:59:59'));
        }

        if ($type !== '') {
            $query->where('type', $type);
        }

        $query->orderBy('date', 'desc');

        return $query->paginate($pagination_count);
    }
}
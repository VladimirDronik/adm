<?php

namespace App\Repositories;

use Carbon\Carbon;
use App\Models\Log;

class LogRepository
{
    public function getTypes()
    {
        $types = Log::select('type')
            ->distinct()
            ->orderBy('type')
            ->get()
            ->pluck('type')
            ->toArray();

        foreach ($types as &$type) {
            if (empty(trim($type))) {
                $type = Log::NO_TYPE_NAME;
            }
        }

        return $types;
    }

    public function getByFilter(array $filter, int $perPage = 30)
    {
        $start = trim($filter['start']);
        $end = trim($filter['end']);
        $type = trim($filter['type']);

        $query = Log::query();

        if ($start !== '') {
            $query->where(
                'date', '>=',
                Carbon::createFromFormat('d.m.Y', $start)->format('Y-m-d 00:00:00')
            );
        }

        if ($end !== '') {
            $query->where(
                'date', '<=',
                Carbon::createFromFormat('d.m.Y', $end)->format('Y-m-d 23:59:59')
            );
        }

        if ($type !== '') {
            if ($type === Log::NO_TYPE_NAME) {
                $query->where('type', '');
            } else {
                $query->where('type', $type);
            }
        }

        $query->orderBy('date', 'desc');

        return $query->paginate($perPage);
    }
}

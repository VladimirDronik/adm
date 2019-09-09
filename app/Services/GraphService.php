<?php

namespace App\Services;

use App\Models\Graph;
use App\Models\Room;
use App\Models\Termostat;
use Carbon\Carbon;

class GraphService {

    public function getPeriods()
    {
        $periods = [];
        $min_date = Graph::min('datetime');
        if (empty($min_date)) {
            return $periods;
        }
        $min_date = Carbon::createFromFormat('Y-m-d H:i:s', $min_date);
        $cur_date = Carbon::now();
        while ($min_date->lte($cur_date)) {
            $periods[$min_date->month.'-'.$min_date->year] = 'за '.getRusMonth($min_date->month).' '.$min_date->year;
            $min_date->addMonth();
        }

        return array_reverse($periods);
    }

    public function getGraphData()
    {
        $termostat_ids = Graph::select('id_termostat')
            ->distinct()->pluck('id_termostat')->toArray();
        $rooms_ids = Termostat::select('room')->whereIn('id', $termostat_ids)
            ->distinct()->pluck('room')->toArray();

        $data['rooms'] = Room::whereIn('id', $rooms_ids)
            ->with('termostats', 'termostats.last_graphs')->orderBy('id')->get();

        $data['other_termostats'] = Termostat::with('last_graphs')->whereNull('room')->orderBy('id')->get();

        return $data;
    }

    public function getGraphPeriodData(int $termostat_id, string $period)
    {
        $query = Graph::where('id_termostat', $termostat_id)
            ->select('value', 'datetime')->orderBy('datetime');

        if ($period === '7') {
            $week_ago_date = Carbon::now()->subDays(7)->format('Y-m-d 00:00:00');
            $graphs = $query->where('datetime','>=',$week_ago_date)->get();
        } else {
            $period_parts = explode("-", $period);
            $month = (int)$period_parts[0];
            $year = (int)$period_parts[1];
            $graphs = $query->whereMonth('datetime', '=', $month)
                ->whereYear('datetime', '=', $year)->get();
        }

        $data['values'] = $graphs->pluck('value')->toArray();
        $data['dates'] = $graphs->pluck('datetime')->toArray();

        return [true, $data];
    }
}

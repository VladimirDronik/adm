<?php

namespace App\Services;

use App\Models\Count;
use App\Models\GraphCount;
use App\Models\GraphHumidity;
use App\Models\GraphLight;
use App\Models\GraphTermostat;
use App\Models\Room;
use App\Models\Termostat;
use Carbon\Carbon;

class GraphService {

    public function getTermostatsPeriods()
    {
        $periods = [];
        $min_date = GraphTermostat::min('datetime');
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

    public function getGraphTermostatsData()
    {
        $termostat_ids = GraphTermostat::select('id_termostat')
            ->distinct()->pluck('id_termostat')->toArray();
        $rooms_ids = Termostat::select('room')->whereIn('id', $termostat_ids)
            ->distinct()->pluck('room')->toArray();

        $data['rooms'] = Room::whereIn('id', $rooms_ids)
            ->with('termostats', 'termostats.last_graphs')->orderBy('id')->get();

        $data['other_termostats'] = Termostat::with('last_graphs')->whereNull('room')->orderBy('id')->get();

        return $data;
    }

    public function getGraphTermostatsPeriodData(int $termostat_id, string $period)
    {
        $query = GraphTermostat::where('id_termostat', $termostat_id)
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

    /* humidities */

    public function getHumiditiesPeriods()
    {
        $periods = [];
        $min_date = GraphHumidity::min('datetime');
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

    public function getGraphHumiditiesData()
    {
        $count_ids = GraphHumidity::select('id_count')
            ->distinct()->pluck('id_count')->toArray();

        $data['counts'] = $count_ids; // todo

        return $data;
    }

    public function getGraphHumiditiesPeriodData(int $count_id, string $period)
    {
        $query = GraphHumidity::where('id_count', $count_id)
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

    /* lights */

    public function getLightsPeriods()
    {
        $periods = [];
        $min_date = GraphLight::min('datetime');
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

    public function getGraphLightsData()
    {
        $count_ids = GraphLight::select('id_count')
            ->distinct()->pluck('id_count')->toArray();

        $data['counts'] = $count_ids; // todo

        return $data;
    }

    public function getGraphLightsPeriodData(int $count_id, string $period)
    {
        $query = GraphLight::where('id_count', $count_id)
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

    /* counts */

    public function getCountsPeriods()
    {
        $periods = [];
        $min_date = GraphCount::min('datetime');
        if (empty($min_date)) {
            return $periods;
        }
        $min_date = Carbon::createFromFormat('Y-m-d', $min_date);
        $cur_date = Carbon::now();
        while ($min_date->lte($cur_date)) {
            $periods[$min_date->month.'-'.$min_date->year] = 'за '.getRusMonth($min_date->month).' '.$min_date->year;
            $min_date->addMonth();
        }

        return array_reverse($periods);
    }

    public function getGraphCountsData()
    {
        $count_ids = GraphCount::select('id_count')
            ->distinct()->pluck('id_count')->toArray();

        $data['counts'] = Count::whereIn('id', $count_ids)->orderBy('name')->get();

        return $data;
    }

    public function getGraphCountsPeriodData(int $count_id, string $period)
    {
        $query = GraphCount::where('id_count', $count_id)
            ->select('value', 'datetime')->orderBy('datetime');

        if ($period === '7') {
            $week_ago_date = Carbon::now()->subDays(7)->format('Y-m-d');
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

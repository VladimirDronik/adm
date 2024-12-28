<?php

namespace App\Services;

use Carbon\Carbon;
use App\Models\Room;
use App\Models\Count;
use App\Models\Hygrostat;
use App\Models\Lightstat;
use App\Models\Termostat;
use App\Models\GraphCount;
use App\Models\GraphLight;
use App\Models\Carbdioxide;
use App\Models\SensorGraph;
use App\Models\Pressurestat;
use App\Models\SensorsParam;
use App\Models\GraphHumidity;
use App\Models\GraphPressure;
use App\Models\GraphTermostat;
use App\Models\GraphCarbdioxide;

class GraphService
{
    public function getTermostatsPeriods()
    {
        $periods = [];
        $minDate = GraphTermostat::min('datetime');
        if (empty($minDate)) {
            return $periods;
        }
        $minDate = Carbon::createFromFormat('Y-m-d H:i:s', $minDate);
        $curDate = Carbon::now();
        while ($minDate->lte($curDate)) {
            $periods[$minDate->month.'-'.$minDate->year] = 'за '.getRusMonth($minDate->month).' '.$minDate->year;
            $minDate->addMonth();
        }

        return array_reverse($periods);
    }

    public function getGraphTermostatsData()
    {
        $roomsIds = Termostat::whereNotNull('room')->select('room')
            ->distinct()->pluck('room')->toArray();

        $data['rooms'] = Room::whereIn('id', $roomsIds)
            ->with('termostats', 'termostats.last_graphs')->orderBy('id')->get();

        $data['other_termostats'] = Termostat::with('last_graphs')->whereNull('room')->orderBy('id')->get();

        return $data;
    }

    public function getGraphTermostatsPeriodData(int $termostat_id, string $period)
    {
        $query = GraphTermostat::where('id_termostat', $termostat_id)
            ->select('value', 'datetime')->orderBy('datetime');

        if ($period === '7') {
            $weekAgoDate = Carbon::now()->subDays(7)->format('Y-m-d 00:00:00');
            $graphs = $query->where('datetime', '>=', $weekAgoDate)->get();
        } else {
            $periodParts = explode('-', $period);
            $month = (int) $periodParts[0];
            $year = (int) $periodParts[1];
            $graphs = $query->whereMonth('datetime', '=', $month)
                ->whereYear('datetime', '=', $year)->get();
        }

        $data['values'] = $graphs->pluck('value')->toArray();
        $data['dates'] = $graphs->pluck('datetime')->toArray();

        return [true, $data];
    }

    public function getHumiditiesPeriods()
    {
        $periods = [];
        $minDate = GraphHumidity::min('datetime');
        if (empty($minDate)) {
            return $periods;
        }
        $minDate = Carbon::createFromFormat('Y-m-d H:i:s', $minDate);
        $curDate = Carbon::now();
        while ($minDate->lte($curDate)) {
            $periods[$minDate->month.'-'.$minDate->year] = 'за '.getRusMonth($minDate->month).' '.$minDate->year;
            $minDate->addMonth();
        }

        return array_reverse($periods);
    }

    public function getGraphHumiditiesData()
    {
        $roomsIds = Hygrostat::whereNotNull('room')
            ->select('room')
            ->distinct()
            ->pluck('room')
            ->toArray();

        $data['rooms'] = Room::whereIn('id', $roomsIds)
            ->with('hygrostats', 'hygrostats.last_graphs')
            ->orderBy('id')
            ->get();

        $data['other_hygrostats'] = Hygrostat::with('last_graphs')
            ->whereNull('room')
            ->orderBy('id')
            ->get();

        return $data;
    }

    public function getGraphHumiditiesPeriodData(int $hygrostatId, string $period)
    {
        $query = GraphHumidity::where('id_hygrostat', $hygrostatId)
            ->select('value', 'datetime')
            ->orderBy('datetime');

        if ($period === '7') {
            $weekAgoDate = Carbon::now()->subDays(7)->format('Y-m-d 00:00:00');
            $graphs = $query->where('datetime', '>=', $weekAgoDate)->get();
        } else {
            $periodParts = explode('-', $period);
            $month = (int) $periodParts[0];
            $year = (int) $periodParts[1];
            $graphs = $query->whereMonth('datetime', '=', $month)
                ->whereYear('datetime', '=', $year)
                ->get();
        }

        $data['values'] = $graphs->pluck('value')->toArray();
        $data['dates'] = $graphs->pluck('datetime')->toArray();

        return [true, $data];
    }

    public function getLightsPeriods()
    {
        $periods = [];
        $minDate = GraphLight::min('datetime');
        if (empty($minDate)) {
            return $periods;
        }
        $minDate = Carbon::createFromFormat('Y-m-d H:i:s', $minDate);
        $curDate = Carbon::now();
        while ($minDate->lte($curDate)) {
            $periods[$minDate->month.'-'.$minDate->year] = 'за '.getRusMonth($minDate->month).' '.$minDate->year;
            $minDate->addMonth();
        }

        return array_reverse($periods);
    }

    public function getGraphLightsData()
    {
        $roomsIds = Lightstat::whereNotNull('room')
            ->select('room')
            ->distinct()
            ->pluck('room')
            ->toArray();

        $data['rooms'] = Room::whereIn('id', $roomsIds)
            ->with('lightstats', 'lightstats.last_graphs')
            ->orderBy('id')
            ->get();

        $data['other_lightstats'] = Lightstat::with('last_graphs')
            ->whereNull('room')
            ->orderBy('id')
            ->get();

        return $data;
    }

    public function getGraphLightsPeriodData(int $countId, string $period)
    {
        $query = GraphLight::where('id_count', $countId)
            ->select('value', 'datetime')
            ->orderBy('datetime');

        if ($period === '7') {
            $weekAgoDate = Carbon::now()->subDays(7)->format('Y-m-d 00:00:00');
            $graphs = $query->where('datetime', '>=', $weekAgoDate)->get();
        } else {
            $periodParts = explode('-', $period);
            $month = (int) $periodParts[0];
            $year = (int) $periodParts[1];
            $graphs = $query->whereMonth('datetime', '=', $month)
                ->whereYear('datetime', '=', $year)
                ->get();
        }

        $data['values'] = $graphs->pluck('value')->toArray();
        $data['dates'] = $graphs->pluck('datetime')->toArray();

        return [true, $data];
    }

    public function getPressuresPeriods()
    {
        $periods = [];
        $minDate = GraphPressure::min('datetime');
        if (empty($minDate)) {
            return $periods;
        }
        $minDate = Carbon::createFromFormat('Y-m-d H:i:s', $minDate);
        $curDate = Carbon::now();
        while ($minDate->lte($curDate)) {
            $periods[$minDate->month.'-'.$minDate->year] = 'за '.getRusMonth($minDate->month).' '.$minDate->year;
            $minDate->addMonth();
        }

        return array_reverse($periods);
    }

    public function getGraphPressuresData()
    {
        $roomsIds = Pressurestat::whereNotNull('room')
            ->select('room')
            ->distinct()
            ->pluck('room')
            ->toArray();

        $data['rooms'] = Room::whereIn('id', $roomsIds)
            ->with('pressurestats', 'pressurestats.lastGraphs')
            ->orderBy('id')
            ->get();

        $data['other_pressurestats'] = Pressurestat::with('lastGraphs')
            ->whereNull('room')
            ->orderBy('id')
            ->get();

        return $data;
    }

    public function getGraphPressuresPeriodData(int $countId, string $period)
    {
        $query = GraphPressure::where('id_count', $countId)
            ->select('value', 'datetime')
            ->orderBy('datetime');

        if ($period === '7') {
            $weekAgoDate = Carbon::now()->subDays(7)->format('Y-m-d 00:00:00');
            $graphs = $query->where('datetime', '>=', $weekAgoDate)->get();
        } else {
            $periodParts = explode('-', $period);
            $month = (int) $periodParts[0];
            $year = (int) $periodParts[1];
            $graphs = $query->whereMonth('datetime', '=', $month)
                ->whereYear('datetime', '=', $year)
                ->get();
        }

        $data['values'] = $graphs->pluck('value')->toArray();
        $data['dates'] = $graphs->pluck('datetime')->toArray();

        return [true, $data];
    }

    public function getCountsPeriods()
    {
        $periods = [];
        $minDate = GraphCount::min('datetime');
        if (empty($minDate)) {
            return $periods;
        }
        $minDate = Carbon::createFromFormat('Y-m-d', $minDate);
        $curDate = Carbon::now();
        while ($minDate->lte($curDate)) {
            $periods[$minDate->month.'-'.$minDate->year] = 'за '.getRusMonth($minDate->month).' '.$minDate->year;
            $minDate->addMonth();
        }

        return array_reverse($periods);
    }

    public function getGraphCountsData()
    {
        $countIds = GraphCount::select('id_count')
            ->distinct()
            ->pluck('id_count')
            ->toArray();

        $data['counts'] = Count::whereIn('id', $countIds)
            ->orderBy('name')
            ->get();

        return $data;
    }

    public function getGraphCountsPeriodData(int $countId, string $period)
    {
        $query = GraphCount::where('id_count', $countId)
            ->select('value', 'datetime')
            ->orderBy('datetime');

        if ($period === '7') {
            $weekAgoDate = Carbon::now()->subDays(7)->format('Y-m-d');
            $graphs = $query->where('datetime', '>=', $weekAgoDate)->get();
        } else {
            $periodParts = explode('-', $period);
            $month = (int) $periodParts[0];
            $year = (int) $periodParts[1];
            $graphs = $query->whereMonth('datetime', '=', $month)
                ->whereYear('datetime', '=', $year)
                ->get();
        }

        $data['values'] = $graphs->pluck('value')->toArray();
        $data['dates'] = $graphs->pluck('datetime')->toArray();

        return [true, $data];
    }

    public function getCarbdioxidesPeriods()
    {
        $periods = [];
        $minDate = GraphCarbdioxide::min('datetime');
        if (empty($minDate)) {
            return $periods;
        }
        $minDate = Carbon::createFromFormat('Y-m-d H:i:s', $minDate);
        $curDate = Carbon::now();
        while ($minDate->lte($curDate)) {
            $periods[$minDate->month.'-'.$minDate->year] = 'за '.getRusMonth($minDate->month).' '.$minDate->year;
            $minDate->addMonth();
        }

        return array_reverse($periods);
    }

    public function getGraphCarbdioxidesData()
    {
        $roomsIds = Carbdioxide::whereNotNull('room')
            ->select('room')
            ->distinct()
            ->pluck('room')
            ->toArray();

        $data['rooms'] = Room::whereIn('id', $roomsIds)
            ->with('carbdioxides', 'carbdioxides.lastGraphs')
            ->orderBy('id')
            ->get();

        $data['other_carbdioxides'] = Carbdioxide::with('lastGraphs')
            ->whereNull('room')
            ->orderBy('id')
            ->get();

        return $data;
    }

    public function getGraphCarbdioxidesPeriodData(int $carbdioxideId, string $period)
    {
        $query = GraphCarbdioxide::where('id_carbdioxide', $carbdioxideId)
            ->select('value', 'datetime')
            ->orderBy('datetime');

        if ($period === '7') {
            $weekAgoDate = Carbon::now()->subDays(7)->format('Y-m-d 00:00:00');
            $graphs = $query->where('datetime', '>=', $weekAgoDate)->get();
        } else {
            $periodParts = explode('-', $period);
            $month = (int) $periodParts[0];
            $year = (int) $periodParts[1];
            $graphs = $query->whereMonth('datetime', '=', $month)
                ->whereYear('datetime', '=', $year)
                ->get();
        }

        $data['values'] = $graphs->pluck('value')->toArray();
        $data['dates'] = $graphs->pluck('datetime')->toArray();

        return [true, $data];
    }

    public function getSensorsParamsPeriods()
    {
        $periods = [];
        $minDate = SensorGraph::min('datetime');
        if (empty($minDate)) {
            return $periods;
        }
        $minDate = Carbon::createFromFormat('Y-m-d H:i:s', $minDate);
        $curDate = Carbon::now();
        while ($minDate->lte($curDate)) {
            $periods[$minDate->month.'-'.$minDate->year] = 'за '.getRusMonth($minDate->month).' '.$minDate->year;
            $minDate->addMonth();
        }

        return array_reverse($periods);
    }

    public function getSensorGraphsPeriodData(int $sensorParamId, string $period)
    {
        $sensorsParam = SensorsParam::find($sensorParamId);
        $query = SensorGraph::where('param_id', $sensorParamId)
            ->select('value', 'datetime')->orderBy('datetime');

        if ($period === '7') {
            $weekAgoDate = Carbon::now()->subDays(7)->format('Y-m-d 00:00:00');
            $graphs = $query->where('datetime', '>=', $weekAgoDate)->get();
        } else {
            $periodParts = explode('-', $period);
            $month = (int) $periodParts[0];
            $year = (int) $periodParts[1];
            $graphs = $query->whereMonth('datetime', '=', $month)
                ->whereYear('datetime', '=', $year)->get();
        }

        $data['values'] = $graphs->pluck('value')->toArray();
        $data['dates'] = $graphs->pluck('datetime')->toArray();
        $data['unit_name'] = $sensorsParam->unit_name;

        return [true, $data];
    }
}

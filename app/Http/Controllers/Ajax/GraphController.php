<?php

namespace App\Http\Controllers\Ajax;

use Illuminate\Http\Request;
use App\Services\GraphService;
use App\Http\Controllers\Controller;

class GraphController extends Controller
{
    public function __construct(
        private GraphService $service
    ) {
    }

    public function getTermostatsPeriodData(Request $r)
    {
        abort_if(! ajaxHas($r, ['termostat_id', 'period']), 400);

        [$result, $data] = $this->service
            ->getGraphTermostatsPeriodData($r->termostat_id, $r->period);

        return response()->json(compact('result', 'data'));
    }

    public function getHumiditiesPeriodData(Request $r)
    {
        abort_if(! ajaxHas($r, ['hygrostat_id', 'period']), 400);

        [$result, $data] = $this->service
            ->getGraphHumiditiesPeriodData($r->hygrostat_id, $r->period);

        return response()->json(compact('result', 'data'));
    }

    public function getCountsPeriodData(Request $r)
    {
        abort_if(! ajaxHas($r, ['count_id', 'period']), 400);

        [$result, $data] = $this->service
            ->getGraphCountsPeriodData($r->count_id, $r->period);

        return response()->json(compact('result', 'data'));
    }

    public function getLightsPeriodData(Request $r)
    {
        abort_if(! ajaxHas($r, ['count_id', 'period']), 400);

        [$result, $data] = $this->service
            ->getGraphLightsPeriodData($r->count_id, $r->period);

        return response()->json(compact('result', 'data'));
    }

    public function getPressuresPeriodData(Request $r)
    {
        abort_if(! ajaxHas($r, ['count_id', 'period']), 400);

        [$result, $data] = $this->service
            ->getGraphPressuresPeriodData($r->count_id, $r->period);

        return response()->json(compact('result', 'data'));
    }

    public function getCarbdioxidesPeriodData(Request $r)
    {
        abort_if(! ajaxHas($r, ['count_id', 'period']), 400);

        [$result, $data] = $this->service
            ->getGraphCarbdioxidesPeriodData($r->count_id, $r->period);

        return response()->json(compact('result', 'data'));
    }
}

<?php


namespace App\Http\Controllers\Ajax;

use App\Http\Controllers\Controller;
use App\Services\GraphService;
use Illuminate\Http\Request;

class GraphController extends Controller
{
    private $service;

    public function __construct(GraphService $service)
    {
        $this->service = $service;
    }

    public function getPeriodData(Request $r)
    {
        abort_if(!ajaxHas($r, ['termostat_id','period']), 400);

        list($result, $data) = $this->service->getGraphPeriodData($r->termostat_id, $r->period);

        return response()->json(compact('result','data'));
    }
}
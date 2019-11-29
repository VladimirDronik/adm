<?php

namespace App\Http\Controllers;

use App\Services\GraphService;

class GraphController extends Controller
{
    private $service;

    public function __construct(GraphService $service)
    {
        $this->service = $service;
    }

    public function termostats()
    {
        $data = $this->service->getGraphData();
        $periods = $this->service->getPeriods();

        return view('graphs.index', compact('data', 'periods'));
    }
}

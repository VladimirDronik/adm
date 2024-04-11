<?php

namespace App\Http\Controllers;

use App\Services\GraphService;

class GraphController extends Controller
{
    public function __construct(
        private GraphService $service
    ) {
    }

    public function termostats()
    {
        $data = $this->service->getGraphTermostatsData();
        $periods = $this->service->getTermostatsPeriods();

        return view('graphs.termostats.index', compact('data', 'periods'));
    }

    public function humidities()
    {
        $data = $this->service->getGraphHumiditiesData();
        $periods = $this->service->getHumiditiesPeriods();

        return view('graphs.humidities.index', compact('data', 'periods'));
    }

    public function lights()
    {
        $data = $this->service->getGraphLightsData();
        $periods = $this->service->getLightsPeriods();

        return view('graphs.lights.index', compact('data', 'periods'));
    }

    public function counts()
    {
        $data = $this->service->getGraphCountsData();
        $periods = $this->service->getCountsPeriods();

        return view('graphs.counts.index', compact('data', 'periods'));
    }

    public function pressures()
    {
        $data = $this->service->getGraphPressuresData();
        $periods = $this->service->getPressuresPeriods();

        return view('graphs.pressures.index', compact('data', 'periods'));
    }

    public function carbdioxides()
    {
        $data = $this->service->getGraphCarbdioxidesData();
        $periods = $this->service->getCarbdioxidesPeriods();

        return view('graphs.carbdioxides.index', compact('data', 'periods'));
    }
}

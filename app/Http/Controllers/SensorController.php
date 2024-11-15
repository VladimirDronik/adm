<?php

namespace App\Http\Controllers;

use App\Repositories\SensorRepository;

class SensorController extends Controller
{
    public function __construct(
        private SensorRepository $sensorRepository,
    ) {
    }

    public function index()
    {
        $sensorObjects = $this->sensorRepository->getAll();

        return view('sensors.index', compact('sensorObjects'));
    }
}

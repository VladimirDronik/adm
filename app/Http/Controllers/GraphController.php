<?php

namespace App\Http\Controllers;

use App\Services\GraphService;
use Illuminate\Http\Request;

class GraphController extends Controller
{
    private $service;

    public function __construct(GraphService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $data = $this->service->getGraphData();

        return view('graphs.index', compact('data'));
    }
}

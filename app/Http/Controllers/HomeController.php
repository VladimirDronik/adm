<?php

namespace App\Http\Controllers;

use App\Models\Device;
use App\Models\HomeObject;
use App\Models\Room;
use App\Models\Scene;
use App\Models\SchedulerTask;
use App\Models\Script;
use App\Models\Termostat;
use App\Models\View;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $counts = [
            'devices' => Device::count(),
            'objects' => HomeObject::count(),
            'rooms' => Room::count(),
            'views' => View::count(),
            'scenes' => Scene::count(),
            'termostats' => Termostat::count(),
            'events' => SchedulerTask::count(),
            'scripts' => Script::count()
        ];

        return view('home', compact('counts'));
    }
}

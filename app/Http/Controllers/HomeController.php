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
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;

class HomeController extends Controller
{
    public function index()
    {
        $counts = [
            'devices' => Device::count(),
            'objects' => HomeObject::count(),
            'rooms' => Room::count(),
            'views' => View::count(),
            'scenes' => Scene::count(),
            'termostats' => Termostat::count(),
            'scheduler' => SchedulerTask::count(),
            'scripts' => Script::count(),
        ];

        return view('home', compact('counts'));
    }

    public function generateFake()
    {
        if (App::environment('local')) {
            Artisan::call('migrate:refresh');
            Artisan::call('db:seed');
            Artisan::call('fake');
        }

        return back();
    }

    public function accessError()
    {
        return 'Доступ запрещен. <a href="'.route('home').'">На главную</a>';
    }
}

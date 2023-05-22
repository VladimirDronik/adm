<?php

namespace App\Http\Controllers;

use App\Models\ClientInfo;
use App\Models\Device;
use App\Models\HomeObject;
use App\Models\Room;
use App\Models\Scene;
use App\Models\SchedulerTask;
use App\Models\Script;
use App\Models\Termostat;
use App\Models\View;
use App\Services\ClientAppInfoService;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Artisan;

class HomeController extends Controller
{
    private $clientAppInfoService;

    public function __construct(ClientAppInfoService $clientAppInfoService)
    {
        $this->clientAppInfoService = $clientAppInfoService;
    }

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
            'scripts' => Script::count()
        ];

        $name = '';
        $address = '';

        $clientInfo = ClientInfo::getInfo();
        if ($clientInfo) {
            $name = $clientInfo->name;
            $address = $clientInfo->address;
        }

        $adminAppV = $this->clientAppInfoService->getAdminVersion();
        $coreV = $this->clientAppInfoService->getCoreVersion();

        return view('home', compact('counts', 'name', 'address', 'adminAppV', 'coreV'));
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

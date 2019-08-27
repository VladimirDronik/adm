<?php

namespace App\Http\Controllers\Ajax;

use App\Services\DeviceService;
use Illuminate\Http\Request;
use App\Models\Device;
use App\Models\Devtype;
use App\Http\Controllers\Controller;

class DeviceController extends Controller
{
    private $service;

    public function __construct(DeviceService $service)
    {
        $this->service = $service;
    }

    public function delete(Request $r)
    {
        abort_if(!$r->ajax() || !$r->has('id'), 400);

        return response()->json(['result' => (bool)$this->service->delete((int)$r->id)]);
    }

    // todo refactoring below
    /**
     * Сохранение настроек контроллера
     *
     */
    public function save_device_settings()
    {
        Device::save_device_settings($_POST['id_device'], $_POST['description'], $_POST['ip_device']);
    }
}



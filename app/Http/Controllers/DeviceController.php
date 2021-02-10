<?php

namespace App\Http\Controllers;

use App\Http\Requests\Device\CreateRequest;
use App\Models\Device;
use App\Models\Port;
use App\Repositories\DeviceRepository;
use App\Services\DeviceService;
use Illuminate\Http\Request;
use App\Services\ConfigMegaService;


class DeviceController extends Controller
{
    private $device_rep;
    private $service;
    private $megaService;

    public function __construct(DeviceRepository $device_rep, DeviceService $service, ConfigMegaService $megaService)
    {
        $this->device_rep = $device_rep;
        $this->service = $service;
        $this->megaService = $megaService;
    }

    public function index()
    {
        $devices = $this->device_rep->getByName();

        return view('devices.index', compact('devices'));
    }

    public function create()
    {
        $devtypes = $this->device_rep->getDevTypesToArray();

        return view('devices.create', compact('devtypes'));
    }

    /**
     * Отправка конфига на выбранный контроллер
     *
     * @param int $id
     * @param Request $request
     * @return $this|\Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function sendConfig(int $id, Request $request)
    {
        $error = ConfigMegaService::sendConfigToDevice($id);

        if(!$error) {
         $devices = $this->device_rep->getByName();

         return view('devices.index', compact('devices'));
        } else
        return back()->withErrors($error);
    }

    /**
     * Отправка конфига на все доступные контроллеры
     */
    public function sendAllConfigs()
    {
        $error = ConfigMegaService::sendAllConfig();

        if(!$error) {
            $devices = $this->device_rep->getByName();

            return view('devices.index', compact('devices'));
        } else
            return back()->withErrors($error);
    }

    public function store(CreateRequest $r)
    {
        try {
            if ($id = $this->service->store($r->except('_token'))) {
                return redirect()->route('devices.edit',[$id])->with('success', 'Устройство успешно добавлено');
            }
        } catch (\Throwable $e) {
            \Log::error('Ошибка при добавлении устройства ' .json_encode($r->all()).' '.$e->getMessage());
        }

        return back()->withInput($r->all())->with('error', 'Ошибка при добавлении устройства');
    }

    public function edit(int $id, Request $request)
    {
        $tab = $request['tab'];
        if(!$tab) $tab=1;


        $device = Device::where('id', $id)
            ->with('ports', 'ports.eobject', 'ports.emethod', 'ports.dcmethod', 'ports.lcmethod',
                'ports.emethod.eobject', 'ports.dcmethod.eobject', 'ports.lcmethod.eobject'
            )->first();

        if (!$device) {
            return redirect()->route('devices.index')->with('error', 'Устройство не найдено');
        }


        if($id)
        $controller = DeviceRepository::getDevByIdDevice($id);


        if ($controller['type'] == 'Hite-pro') {

            if(DeviceService::getStatus($id))
            $devstorage = DeviceService::readHiteproDevices($id, $controller['address'], $controller['password']);
            else $devstorage = null;

            return view('devices.edit_hitepro', compact('device', 'devstorage', 'tab'));
        }
        else
            return view('devices.edit', compact('device', 'tab'));
    }


}

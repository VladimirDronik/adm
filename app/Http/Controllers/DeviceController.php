<?php

namespace App\Http\Controllers;

use App\Http\Requests\Device\CreateRequest;
use App\Models\Device;
use App\Models\Port;
use App\Repositories\DeviceRepository;
use App\Services\DeviceService;
use Illuminate\Http\Request;

class DeviceController extends Controller
{
    private $device_rep;
    private $service;

    public function __construct(DeviceRepository $device_rep, DeviceService $service)
    {
        $this->device_rep = $device_rep;
        $this->service = $service;
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

    public function store(CreateRequest $r)
    {
        try {
            if ($id = $this->service->store($r->except('_token'))) {
                return redirect()->route('devices.edit',[$id])->with('success', 'Устройство успешно добавлено');
            }
        } catch (\Throwable $e) {
            \Log::error('Ошибка при добавлении устройства '
                .json_encode($r->all()).' '.$e->getMessage());
        }

        return back()->withInput($r->all())->with('error', 'Ошибка при добавлении устройства');
    }

    /**
     * Выбор определенного устройства по id
     *
     * @param int $id - ид устройсва, коотрое выбираем
     * @return void
     */
    public function edit($id)
    {
        //получение инормации об устройстве по овыбранному id
        $device = Device::where('id', $id)->first();

        //получение портов устройства и связанных с ними свойств
        $ports = Port::select('*', 'ports.id AS id', 'ports.status AS type', 'objects.name AS nameobj', 'scripts.name AS namescript')
            ->leftjoin('objects', 'objects.id', '=', 'ports.object')
            ->leftjoin('scripts', 'scripts.id', '=', 'ports.script')
            ->orderBy('num_port', 'ASC')
            ->where('id_device', $id)->get();

        return view('devices.edit', ['device' => $device, 'ports' => $ports]);
    }

}

<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\Port\UpdateRequest;

use App\Models\Device;
use App\Models\Port;
use App\Services\PortService;

class PortController extends Controller
{

    private $service;

    public function __construct(PortService $service)
    {
        $this->service = $service;
    }

    public function store(UpdateRequest $r)
    {

        $id_tab = 'tab='.$r->tab;

        try {
            if ($this->service->store($r->except('_token'))) {
                return redirect()->route('devices.edit',[$r->id_controller, $id_tab]);
            }
        } catch (\Throwable $e) {
            \Log::error('Ошибка при изменении порта ' .json_encode($r->all()).' '.$e->getMessage());
        }

        return back()->withInput($r->all())->with('error', 'Ошибка при изменении порта'.$e->getMessage());



    }


    /**
     * Редактирование настроек порта
     *
     * @param int $idDevice - ИД устройства
     * @param int $idPort - ИД порта
     */
    public function edit(int $idPort)
    {

        $port = Port::where('id', $idPort)->first();


        $device = Device::where('id', $port->id_device)
            ->with('ports', 'ports.eobject', 'ports.emethod', 'ports.dcmethod', 'ports.lcmethod',
                'ports.emethod.eobject', 'ports.dcmethod.eobject', 'ports.lcmethod.eobject'
            )->first();


        return view('devices.editport', compact('port', 'device'));

    }


}

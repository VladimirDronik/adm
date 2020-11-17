<?php

namespace App\Http\Controllers;

use App\Models\Virtual;
use App\Repositories\DeviceRepository;
use App\Repositories\ObjectRepository;
use App\Repositories\VirtualRepository;
use App\Services\VirtualService;
use Illuminate\Http\Request;
use App\Models\HomeObject;
use App\Http\Requests\Virtual\CreateRequest;
use App\Http\Requests\Virtual\UpdateRequest;
use App\Repositories\ScriptRepository;
use App\Services\MessageService;


class VirtualsController extends Controller
{

    private $virt_rep;
    private $object_rep;
    private $device_rep;
    private $service;

    public function __construct(VirtualRepository $virtualRepository, ObjectRepository $objectRepository,
                                DeviceRepository $deviceRepository, VirtualService $virtualService) {

        $this->virt_rep = $virtualRepository;
        $this->object_rep = $objectRepository;
        $this->device_rep = $deviceRepository;
        $this->service = $virtualService;
    }

    public function index()
    {
        $virtuals = $this->virt_rep->getAll();

        return view('virtuals.index', compact('virtuals'));
    }

    public function create()
    {

        $objects = $this->object_rep->getAllToArray();
        $object_types =  HomeObject::getFullTypeIds();
        $devices = $this->device_rep->getAllToArray();

        return view('virtuals.create', compact( 'objects', 'object_types', 'devices'));
    }

    public function store(CreateRequest $r)
    {
        try {
            if ($id = $this->service->store($r->except('_token'))) {
                return redirect()->route('virtuals.edit', [$id])
                    ->with('success', 'Виртуальное устройство успешно добавлено');
            }
        } catch (\Throwable $e) {
            \Log::error('Ошибка при добавлении виртуального устройства ' .
                json_encode($r->all()).' '.$e->getMessage());
        }

        return back()->withInput($r->all())->with('error', 'Ошибка при добавлении виртуального устройства');
    }


    public function edit(Virtual $virtual, ScriptRepository $script_rep, MessageService $messageService)
    {

        $objects = $this->object_rep->getAllToArray();
        $object_types =  HomeObject::getFullTypeIds();

        $scripts = $script_rep->getAllToArray();
        $can = gates('devices.show-object');

        $messages = $messageService->getNotifications($virtual->id_object);

        $messagePoint['first'] = 'При включении';
        $messagePoint['second'] = 'При выключении';

        return view('virtuals.edit', compact('virtual',
             'messagePoint', 'messages', 'objects', 'object_types', 'scripts', 'can'));
    }


    public function update(UpdateRequest $r, Virtual $virtual)
    {
        try {
            if ($this->service->update($virtual, $r->except('_token'))) {
                return redirect()->route('virtuals.edit', [$virtual->id])
                    ->with('success', 'Виртуальное устройство успешно изменено');
            }
        } catch (\Throwable $e) {
            \Log::error('Ошибка при изменении виртуального устройства '.$virtual->id
                .' ' .json_encode($r->all()).' '.$e->getMessage());
        }

        return back()->withInput($r->all())->with('error','Ошибка при изменении виртуального устройсва');
    }
}

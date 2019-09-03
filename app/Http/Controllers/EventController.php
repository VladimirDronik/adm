<?php

namespace App\Http\Controllers;

use App\Http\Requests\Device\CreateRequest;
use App\Models\Device;
use App\Repositories\EventRepository;
use App\Services\EventService;
use Illuminate\Http\Request;

class EventController extends Controller
{
    private $event_rep;
    private $service;

    public function __construct(EventRepository $event_rep, EventService $service)
    {
        $this->event_rep = $event_rep;
        $this->service = $service;
    }

    public function index()
    {
        $events = $this->event_rep->getAll();

        return view('events.index', compact('events'));
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
            \Log::error('Ошибка при добавлении устройства ' .json_encode($r->all()).' '.$e->getMessage());
        }

        return back()->withInput($r->all())->with('error', 'Ошибка при добавлении устройства');
    }

    public function edit(int $id)
    {
        $device = Device::where('id', $id)->with('ports','ports.eobject','ports.escript')->first();

        return view('devices.edit', compact('device'));
    }
}

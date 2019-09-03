<?php

namespace App\Http\Controllers;

use App\Http\Requests\Event\CreateRequest;
use App\Models\SchedulerPoint;
use App\Models\SchedulerTask;
use App\Repositories\EventRepository;
use App\Repositories\ObjectRepository;
use App\Services\EventService;
use Illuminate\Http\Request;

class EventController extends Controller
{
    private $event_rep;
    private $object_rep;
    private $service;

    public function __construct(EventRepository $event_rep, ObjectRepository $object_rep, EventService $service)
    {
        $this->event_rep = $event_rep;
        $this->object_rep = $object_rep;
        $this->service = $service;
    }

    public function index(Request $r)
    {
        $events = $this->event_rep->getAll();
        $types = SchedulerPoint::getFullTypeIds();

        $filter_name =  $r->input('name', '');
        $filter_type = $r->input('type', '');
        $filter_type_name = SchedulerPoint::getTypeById($filter_type);

        return view('events.index', compact('events', 'types',
            'filter_name', 'filter_type', 'filter_type_name'));
    }

    public function create()
    {
        $objects = $this->object_rep->getAllToArray();

        return view('events.create', compact('objects'));
    }

    public function store(CreateRequest $r)
    {
        try {
            if ($id = $this->service->store($r->except('_token'))) {
                return redirect()->route('events.edit',[$id])->with('success', 'Событие успешно добавлено');
            }
        } catch (\Throwable $e) {
            \Log::error('Ошибка при добавлении события ' .json_encode($r->all()).' '.$e->getMessage());
        }

        return back()->withInput($r->all())->with('error', 'Ошибка при добавлении события');
    }

    public function edit(int $id)
    {
        $event = SchedulerTask::where('id', $id)->with('points','eobject','emethod')->first();

        if (!$event) {
            return redirect()->route('events.index')->with('error','Событие не найдено');
        }

        return view('events.edit', compact('event'));
    }
}

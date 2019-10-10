<?php

namespace App\Http\Controllers;

use App\Http\Requests\Event\CreateRequest;
use App\Http\Requests\Event\UpdateRequest;
use App\Models\SchedulerPoint;
use App\Models\SchedulerTask;
use App\Repositories\EventRepository;
use App\Repositories\ObjectRepository;
use App\Repositories\ScriptRepository;
use App\Services\EventService;
use App\Services\ObjectService;
use Illuminate\Http\Request;

class EventController extends Controller
{
    private $event_rep;
    private $object_rep;
    private $script_rep;
    private $service;

    public function __construct(EventRepository $event_rep, ObjectRepository $object_rep,
                                ScriptRepository $script_rep, EventService $service)
    {
        $this->event_rep = $event_rep;
        $this->object_rep = $object_rep;
        $this->script_rep = $script_rep;

        $this->service = $service;
    }

    private function getFilter(Request $r)
    {
        $filter['name'] = $r->input('name', '');
        $filter['type'] = $r->input('type', '');
        $filter['type_name'] = SchedulerPoint::getTypeById($filter['type']);

        return $filter;
    }

    public function index(Request $r)
    {
        $filter = $this->getFilter($r);
        $events = $this->event_rep->getByNameAndType($filter);
        $types = SchedulerPoint::getFullTypeIds();

        return view('events.index', compact('events', 'types', 'filter'));
    }

    public function create()
    {
        $objects = $this->object_rep->getAllToArray();
        $scripts = $this->script_rep->getAllToArray();

        return view('events.create', compact('objects', 'scripts'));
    }

    public function store(CreateRequest $r)
    {
        try {
            if ($id = $this->service->store($r->except('_token'))) {
                return redirect()->route('events.edit', [$id])
                    ->with('success', 'Событие успешно сохранено. Осталось указать расписание');
            }
        } catch (\Throwable $e) {
            \Log::error('Ошибка при добавлении события ' .json_encode($r->all()).' '.$e->getMessage());
        }

        return back()->withInput($r->all())->with('error', 'Ошибка при добавлении события');
    }

    public function edit(int $id, ObjectService $object_service)
    {
        $event = SchedulerTask::where('id', $id)
            ->with('points', 'eobject', 'emethod', 'escript')->first();

        if (!$event) {
            return redirect()->route('events.index')->with('error', 'Событие не найдено');
        }

        $objects = $this->object_rep->getAllToArray();
        $methods = $object_service->getMethodsByObjectIdToArray($event->object);
        $types = SchedulerPoint::getFullTypeIds();
        $cron_periods = SchedulerPoint::CRON_PERIODS;

        return view('events.edit', compact('event', 'objects', 'methods', 'types', 'cron_periods'));
    }

    public function update(UpdateRequest $r, int $id)
    {
        try {
            $event = SchedulerTask::find($id);

            if (!$event) {
                return redirect()->route('events.index')->with('error', 'Событие не найдено');
            }

            if ($this->service->update($event, $r->except('_token'))) {
                return redirect()->route('events.edit', [$event->id])->with('success','Событие успешно изменено');
            }
        } catch (\Throwable $e) {
            \Log::error('Ошибка при изменении события '.$event->id.' ' .json_encode($r->all()).' '.$e->getMessage());
        }

        return back()->withInput($r->all())->with('error','Ошибка при изменении события');
    }
}

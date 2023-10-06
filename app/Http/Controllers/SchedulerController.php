<?php

namespace App\Http\Controllers;

use App\Http\Requests\Scheduler\CreateRequest;
use App\Http\Requests\Scheduler\UpdateRequest;
use App\Models\SchedulerPoint;
use App\Models\SchedulerTask;
use App\Repositories\ObjectRepository;
use App\Repositories\SchedulerRepository;
use App\Repositories\ScriptRepository;
use App\Services\ObjectService;
use App\Services\SchedulerService;
use Illuminate\Http\Request;

class SchedulerController extends Controller
{
    public function __construct(
        private SchedulerRepository $event_rep,
        private ObjectRepository $object_rep,
        private ScriptRepository $script_rep,
        private SchedulerService $service,
        private ObjectService $object_service
    ) {
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
        $can = gates(['events.*-system', 'events.*-hidden', 'objects', 'scripts']);
        $events = $this->event_rep->getByNameAndType($filter, $can['events.show-system'], $can['events.show-hidden']);
        $types = SchedulerPoint::getFullTypeIds();

        return view('scheduler.index', compact('events', 'types', 'filter', 'can'));
    }

    public function create()
    {
        $objects = $this->object_rep->getAllToArray();
        $scripts = $this->script_rep->getAllToArray();

        $can = gates(['events.*-system', 'events.*-hidden']);

        return view('scheduler.create', compact('objects', 'scripts', 'can'));
    }

    public function store(CreateRequest $r)
    {
        try {
            if ($id = $this->service->store($r->except('_token'))) {
                return redirect()->route('scheduler.edit', [$id])
                    ->with('success', 'Событие успешно сохранено. Осталось указать расписание');
            }
        } catch (\Throwable $e) {
            \Log::error('Ошибка при добавлении задачи планировщика '.json_encode($r->all()).' '.$e->getMessage());
        }

        return back()->withInput($r->all())->with('error', 'Ошибка при добавлении задачи планировщика');
    }

    public function edit(int $id)
    {
        $event = SchedulerTask::where('id', $id)
            ->with('points', 'eobject', 'emethod', 'escript', 'eobject')->first();

        if (! $event) {
            return redirect()->route('scheduler.index')->with('error', 'Событие не найдено');
        }

        $objects = $this->object_rep->getAllToArray();
        $methods = $this->object_service->getMethodsByObjectIdToArray($event->object);
        $scripts = $this->script_rep->getAllToArray();

        $types = SchedulerPoint::getFullTypeIds();
        $cron_periods = SchedulerPoint::CRON_PERIODS;

        $can = gates(['events.*-system', 'events.*-hidden']);

        return view('scheduler.edit', compact('event', 'objects', 'methods', 'scripts',
            'types', 'cron_periods', 'can'));
    }

    public function update(UpdateRequest $r, int $id)
    {
        try {
            $event = SchedulerTask::find($id);

            if (! $event) {
                return redirect()->route('events.index')->with('error', 'Событие не найдено');
            }

            if ($this->service->update($event, $r->except('_token'))) {
                return redirect()->route('scheduler.edit', [$event->id])->with('success', 'Событие успешно изменено');
            }
        } catch (\Throwable $e) {
            \Log::error('Ошибка при изменении задачи планировщика '.$event->id.' '.json_encode($r->all()).' '.$e->getMessage());
        }

        return back()->withInput($r->all())->with('error', 'Ошибка при изменении задачи');
    }
}

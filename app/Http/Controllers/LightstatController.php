<?php

namespace App\Http\Controllers;

use App\Models\Lightstat;
use App\Services\ObjectService;
use App\Services\MessageService;
use App\Services\LightstatService;
use App\Repositories\RoomRepository;
use App\Repositories\ObjectRepository;
use App\Repositories\ScriptRepository;
use App\Repositories\UsensorRepository;
use App\Repositories\LightstatRepository;
use App\Http\Requests\Lightstat\LightstatRequest;

class LightstatController extends Controller
{
    public function __construct(
        private LightstatRepository $lightstatRep,
        private ObjectRepository $objectRep,
        private UsensorRepository $usensorRep,
        private RoomRepository $roomRep,
        private LightstatService $service,
        private ObjectService $objectService,
        private ScriptRepository $scriptRep,
        private MessageService $messageService,
    ) {
    }

    public function index()
    {
        $lightstats = $this->lightstatRep->getAll();

        return view('lightstats.index', compact('lightstats'));
    }

    public function store(LightstatRequest $r)
    {
        try {
            if ($id = $this->service->store($r->except('_token'))) {
                return redirect()->route('lightstats.edit', [$id])
                    ->with('success', 'Датчик освещенности успешно добавлен');
            }
        } catch (\Throwable $e) {
            \Log::error('Ошибка при добавлении датчика освещенности '.json_encode($r->all()).' '.$e->getMessage());
        }

        return back()->withInput($r->all())->with('error', 'Ошибка при добавлении датчика освещенности');
    }

    private function getLists()
    {
        $objects = $this->objectRep->getAllToArray();
        $rooms = $this->roomRep->getAllToArray();
        $types = Lightstat::getFullLigtstatIds();
        $usensors = $this->usensorRep->getAllToArray();

        return [$objects, $rooms, $types, $usensors];
    }

    public function edit(Lightstat $lightstat, $tab = 1)
    {
        [$objects, $rooms, $types, $usensors] = $this->getLists();

        $methods = $this->objectService->getMethodsByObjectIdToArray($lightstat->object);
        $scripts = $this->scriptRep->getAllToArray();

        $messages = $this->messageService->getNotifications($lightstat->id_object);
        $messagePoint['first'] = 'При включении';
        $messagePoint['second'] = 'При выключении';

        return view('lightstats.edit', compact(
            'lightstat', 'objects', 'rooms', 'tab', 'messagePoint',
            'types', 'methods', 'messages', 'scripts', 'usensors'
        ));
    }

    public function create()
    {
        [$objects, $rooms, $types, $usensors] = $this->getLists();
        $tab = 1;

        return view('lightstats.create', compact(
            'objects', 'rooms', 'types', 'usensors', 'tab',
        ));
    }

    public function update(LightstatRequest $r, Lightstat $lightstat)
    {
        try {
            if ($this->service->update($lightstat, $r->except('_token'))) {
                return redirect()->route('lightstats.edit', [$lightstat->id])->with('success', 'Датчик освещенности успешно изменен');
            }
        } catch (\Throwable $e) {
            \Log::error('Ошибка при изменении датчика освещенности '.$lightstat->id.' '.json_encode($r->all()).' '.$e->getMessage());
        }

        return back()->withInput($r->all())->with('error', 'Ошибка при изменении датчика освещенности');
    }
}

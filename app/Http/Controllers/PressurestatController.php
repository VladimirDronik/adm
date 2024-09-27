<?php

namespace App\Http\Controllers;

use App\Models\Pressurestat;
use App\Services\PortService;
use App\Services\ObjectService;
use App\Services\MessageService;
use Illuminate\Support\Facades\Log;
use App\Repositories\RoomRepository;
use App\Services\PressurestatService;
use App\Repositories\ObjectRepository;
use App\Repositories\ScriptRepository;
use App\Repositories\UsensorRepository;
use App\Repositories\PressurestatRepository;
use App\Http\Requests\Pressurestat\PressurestatRequest;

class PressurestatController extends Controller
{
    public function __construct(
        private PressurestatRepository $pressurestatRep,
        private UsensorRepository $usensorRep,
        private RoomRepository $roomRep,
        private ObjectRepository $objectRep,
        private PressurestatService $service,
        private ObjectService $objectService,
        private ScriptRepository $scriptRep,
        private PortService $portsService,
        private MessageService $messageService,
    ) {
    }

    public function index()
    {
        $pressurestats = $this->pressurestatRep->getAll();

        return view('pressurestats.index', compact('pressurestats'));
    }

    public function store(PressurestatRequest $r)
    {
        try {
            if ($id = $this->service->store($r->except('_token'))) {
                return redirect()
                    ->route('pressurestats.edit', [$id])
                    ->with('success', 'Датчик давления успешно добавлен');
            }
        } catch (\Throwable $e) {
            Log::error(
                'Ошибка при добавлении датчика давления '
                .json_encode($r->all()).' '.$e->getMessage()
            );
        }

        return back()->withInput($r->all())
            ->with('error', 'Ошибка при добавлении датчика давления');
    }

    private function getLists()
    {
        $rooms = $this->roomRep->getAllToArray();
        $modes = Pressurestat::getFullPressurestatIds();
        $usensors = $this->usensorRep->getAllToArray();
        $objects = $this->objectRep->getAllToArray();
        $sensorTypes = Pressurestat::getSensorTypes();

        return [$rooms, $modes, $usensors, $objects, $sensorTypes];
    }

    public function edit(Pressurestat $pressurestat, int $tab = 1)
    {
        [$rooms, $modes, $usensors, $objects, $sensorTypes] = $this->getLists();

        $methods = $this->objectService->getMethodsByObjectIdToArray($pressurestat->object);
        $scripts = $this->scriptRep->getAllToArray();

        $messages = $this->messageService->getNotifications($pressurestat->id_object);
        $messagePoint['first'] = 'При включении';
        $messagePoint['second'] = 'При выключении';

        return view('pressurestats.edit', compact(
            'pressurestat', 'rooms', 'tab', 'messagePoint', 'usensors',
            'modes', 'methods', 'scripts', 'sensorTypes', 'objects', 'messages'
        ));
    }

    public function create()
    {
        [$rooms, $modes, $usensors, $objects, $sensorTypes] = $this->getLists();
        $tab = 1;

        return view('pressurestats.create', compact(
            'rooms', 'modes', 'usensors', 'tab', 'objects', 'sensorTypes'
        ));
    }

    public function update(PressurestatRequest $r, Pressurestat $pressurestat)
    {
        try {
            if ($this->service->update($pressurestat, $r->except('_token'))) {
                return redirect()
                    ->route('pressurestats.edit', [$pressurestat->id])
                    ->with('success', 'Датчик давления успешно изменен');
            }
        } catch (\Throwable $e) {
            Log::error(
                'Ошибка при изменении датчика давления '.$pressurestat->id
                .' '.json_encode($r->all()).' '.$e->getMessage()
            );
        }

        return back()->withInput($r->all())
            ->with('error', 'Ошибка при изменении датчика давления');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Hygrostat;
use App\Services\ObjectService;
use App\Services\MessageService;
use App\Services\HygrostatService;
use Illuminate\Support\Facades\Log;
use App\Repositories\RoomRepository;
use App\Repositories\ObjectRepository;
use App\Repositories\ScriptRepository;
use App\Repositories\UsensorRepository;
use App\Repositories\HygrostatRepository;
use App\Http\Requests\Hygrostat\HygrostatRequest;

class HygrostatController extends Controller
{
    public function __construct(
        private HygrostatRepository $hygrostatRep,
        private ObjectRepository $objectRep,
        private UsensorRepository $usensorRep,
        private RoomRepository $roomRep,
        private HygrostatService $service,
        private ObjectService $objectService,
        private ScriptRepository $scriptRep,
        private MessageService $messageService,
    ) {
    }

    public function index()
    {
        $hygrostats = $this->hygrostatRep->getAll();

        return view('hygrostats.index', compact('hygrostats'));
    }

    private function getLists()
    {
        $objects = $this->objectRep->getAllToArray();
        $rooms = $this->roomRep->getAllToArray();
        $types = Hygrostat::getFullHygrostatIds();
        $usensors = $this->usensorRep->getAllToArray();

        return [$objects, $rooms, $types, $usensors];
    }

    public function create()
    {
        [$objects, $rooms, $types, $usensors] = $this->getLists();
        $tab = 1;

        return view('hygrostats.create', compact(
            'objects', 'rooms', 'types', 'usensors', 'tab'
        ));
    }

    public function store(HygrostatRequest $r)
    {
        try {
            if ($id = $this->service->store($r->except('_token'))) {
                return redirect()
                    ->route('hygrostats.edit', [$id])
                    ->with('success', 'Датчик влажности успешно добавлен');
            }
        } catch (\Throwable $e) {
            Log::error(
                'Ошибка при добавлении датчика влажности '
                .json_encode($r->all()).' '.$e->getMessage()
            );
        }

        return back()->withInput($r->all())
            ->with('error', 'Ошибка при добавлении датчика влажности');
    }

    public function edit(Hygrostat $hygrostat, $tab = 1)
    {
        [$objects, $rooms, $types, $usensors] = $this->getLists();

        $methods = $this->objectService->getMethodsByObjectIdToArray($hygrostat->object);
        $scripts = $this->scriptRep->getAllToArray();

        $messages = $this->messageService->getNotifications($hygrostat->id_object);
        $messagePoint['first'] = 'При включении';
        $messagePoint['second'] = 'При выключении';

        return view('hygrostats.edit', compact(
            'hygrostat', 'objects', 'rooms', 'types', 'methods',
            'scripts', 'usensors', 'messages', 'messagePoint', 'tab'
        ));
    }

    public function update(HygrostatRequest $r, Hygrostat $hygrostat)
    {
        try {
            if ($this->service->update($hygrostat, $r->except('_token'))) {
                return redirect()
                    ->route('hygrostats.edit', [$hygrostat->id])
                    ->with('success', 'Датчик влажности успешно изменен');
            }
        } catch (\Throwable $e) {
            Log::error(
                'Ошибка при изменении датчика влажности '.$hygrostat->id
                .' '.json_encode($r->all()).' '.$e->getMessage()
            );
        }

        return back()->withInput($r->all())
            ->with('error', 'Ошибка при изменении датчика влажности');
    }
}

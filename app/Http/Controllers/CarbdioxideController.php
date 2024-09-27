<?php

namespace App\Http\Controllers;

use App\Models\Usensor;
use App\Models\Carbdioxide;
use App\Services\PortService;
use App\Services\ObjectService;
use App\Services\MessageService;
use Illuminate\Support\Facades\Log;
use App\Repositories\RoomRepository;
use App\Services\CarbdioxideService;
use App\Repositories\ObjectRepository;
use App\Repositories\ScriptRepository;
use App\Repositories\UsensorRepository;
use App\Repositories\CarbdioxideRepository;
use App\Http\Requests\Carbdioxide\CarbdioxideRequest;

class CarbdioxideController extends Controller
{
    public function __construct(
        private CarbdioxideRepository $carbdioxideRep,
        private UsensorRepository $usensorRep,
        private RoomRepository $roomRep,
        private ObjectRepository $objectRep,
        private CarbdioxideService $service,
        private ObjectService $objectService,
        private ScriptRepository $scriptRep,
        private PortService $portsService,
        private MessageService $messageService,
    ) {
    }

    public function index()
    {
        $carbdioxides = $this->carbdioxideRep->getAll();

        return view('carbdioxides.index', compact('carbdioxides'));
    }

    private function getLists()
    {
        $rooms = $this->roomRep->getAllToArray();
        $modes = Carbdioxide::getFullCarbdioxideIds();
        $usensors = $this->usensorRep->getByTypesToArray(
            [Usensor::TYPE_SCD40, Usensor::TYPE_SCD41]
        );
        $objects = $this->objectRep->getAllToArray();

        return [$rooms, $modes, $usensors, $objects];
    }

    public function create()
    {
        [$rooms, $modes, $usensors, $objects] = $this->getLists();
        $tab = 1;

        return view('carbdioxides.create', compact(
            'objects', 'rooms', 'modes', 'usensors', 'tab'
        ));
    }

    public function store(CarbdioxideRequest $r)
    {
        try {
            if ($id = $this->service->store($r->except('_token'))) {
                return redirect()
                    ->route('carbdioxides.edit', [$id])
                    ->with('success', 'Датчик углекислого газа успешно добавлен');
            }
        } catch (\Throwable $e) {
            Log::error(
                'Ошибка при добавлении датчика углекислого газа '
                .json_encode($r->all()).' '.$e->getMessage()
            );
        }

        return back()->withInput($r->all())
            ->with('error', 'Ошибка при добавлении датчика углекислого газа');
    }

    public function edit(Carbdioxide $carbdioxide, int $tab = 1)
    {
        [$rooms, $modes, $usensors, $objects] = $this->getLists();

        $methods = $this->objectService->getMethodsByObjectIdToArray($carbdioxide->object);
        $scripts = $this->scriptRep->getAllToArray();

        $messages = $this->messageService->getNotifications($carbdioxide->id_object);
        $messagePoint['first'] = 'При включении';
        $messagePoint['second'] = 'При выключении';

        return view('carbdioxides.edit', compact(
            'carbdioxide', 'rooms', 'tab', 'messagePoint', 'messages',
            'modes', 'methods', 'scripts', 'objects', 'usensors'
        ));
    }

    public function update(CarbdioxideRequest $r, Carbdioxide $carbdioxide)
    {
        try {
            if ($this->service->update($carbdioxide, $r->except('_token'))) {
                return redirect()
                    ->route('carbdioxides.edit', [$carbdioxide->id])
                    ->with('success', 'Датчик углекислого газа успешно изменен');
            }
        } catch (\Throwable $e) {
            Log::error(
                'Ошибка при изменении датчика углекислого газа '
                .$carbdioxide->id.' '.json_encode($r->all()).' '.$e->getMessage()
            );
        }

        return back()->withInput($r->all())
            ->with('error', 'Ошибка при изменении датчика углекислого газа');
    }
}

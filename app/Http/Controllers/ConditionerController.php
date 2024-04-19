<?php

namespace App\Http\Controllers;

use App\Models\Conditioner;
use Illuminate\Support\Facades\Log;
use App\Repositories\RoomRepository;
use App\Services\ConditionerService;
use App\Repositories\ModbusRepository;
use App\Repositories\ConditionerRepository;
use App\Http\Requests\Conditioner\CreateRequest;
use App\Http\Requests\Conditioner\UpdateRequest;

class ConditionerController extends Controller
{
    public function __construct(
        private RoomRepository $roomRep,
        private ModbusRepository $modbusRep,
        private ConditionerService $service,
        private ConditionerRepository $conditionersRep
    ) {
    }

    public function index()
    {
        $conditioners = $this->conditionersRep->getAll();

        return view('conditioners.index', compact('conditioners'));
    }

    public function edit($id)
    {
        $conditioner = Conditioner::findOrFail($id);
        $modbusSlavers = $this->modbusRep->getFilteredSlaversToArray(['ac']);
        $rooms = $this->roomRep->getAllWithoutCommonToArray();

        return view('conditioners.edit', compact('conditioner', 'rooms', 'modbusSlavers'));
    }

    public function create()
    {
        $modbusSlavers = $this->modbusRep->getFilteredSlaversToArray(['ac']);
        $rooms = $this->roomRep->getAllWithoutCommonToArray();

        return view('conditioners.create', compact('rooms', 'modbusSlavers'));
    }

    public function store(CreateRequest $r)
    {
        try {
            if ($id = $this->service->store($r->except('_token'))) {
                return redirect()->route('conditioners.edit', [$id])
                    ->with('success', 'Кондиционер успешно добавлен');
            }
        } catch (\Throwable $e) {
            Log::error('Ошибка при добавлении кондиционера ' . json_encode($r->all()) . ' ' . $e->getMessage());
        }

        return back()->withInput($r->all())->with('error', 'Ошибка при добавлении кондиционера');
    }

    public function update(UpdateRequest $r, Conditioner $conditioner)
    {
        try {
            if ($this->service->update($conditioner, $r->except('_token'))) {
                return redirect()->route('conditioners.edit', [$conditioner->id])
                    ->with('success', 'Кондиционер успешно изменен');
            }
        } catch (\Throwable $e) {
            Log::error('Ошибка при изменении кондиционера ' . json_encode($r->all()) . ' ' . $e->getMessage());
        }

        return back()->withInput($r->all())->with('error', 'Ошибка при изменении кондиционера');
    }
}

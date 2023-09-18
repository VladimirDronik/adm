<?php

namespace App\Http\Controllers;

use App\Models\Conditioner;
use App\Repositories\ConditionerRepository;
use App\Repositories\DeviceRepository;
use App\Repositories\ObjectRepository;
use App\Repositories\RoomRepository;
use App\Services\ConditionerService;
use Illuminate\Http\Request;

class ConditionerController extends Controller
{
    private $conditionersRep;

    private $objectRep;

    private $roomRep;

    private $deviceRep;

    private $service;

    public function __construct(
        ConditionerRepository $conditionersRep,
        ObjectRepository $objectRep,
        RoomRepository $roomRep,
        DeviceRepository $deviceRep,
        ConditionerService $service
    ) {
        $this->conditionersRep = $conditionersRep;
        $this->objectRep = $objectRep;
        $this->roomRep = $roomRep;
        $this->deviceRep = $deviceRep;
        $this->service = $service;
    }

    public function index()
    {
        $conditioners = $this->conditionersRep->getAll();

        return view('conditioners.index', compact('conditioners'));
    }

    public function edit($id)
    {
        $conditioner = Conditioner::findOrFail($id);
        $objects = $this->objectRep->getAllToArray();
        $devices = $this->deviceRep->getAllWithoutTypesToArray();
        $rooms = $this->roomRep->getAllWithoutCommonToArray();
        $conditionerKind = $conditioner->conditionerModel->conditionerKind;
        $operationModes = json_decode($conditionerKind->operationModes, true)['modes'];
        $fanModes = json_decode($conditionerKind->fanModes, true)['modes'];
        $temp = range($conditionerKind->min, $conditionerKind->max, $conditionerKind->precision);
        array_push($temp, 'off');

        return view('conditioners.edit', compact('conditioner', 'objects', 'rooms', 'operationModes', 'fanModes', 'temp', 'conditionerKind', 'devices'));
    }

    public function create()
    {
        $vendors = $this->conditionersRep->getAllVendorsToArray();
        $rooms = $this->roomRep->getAllWithoutCommonToArray();
        $devices = $this->deviceRep->getAllWithoutTypesToArray();

        return view('conditioners.create', compact('vendors', 'rooms', 'devices'));
    }

    public function store(Request $r)
    {
        try {
            if ($id = $this->service->store($r->except('_token'))) {
                return redirect()->route('conditioners.edit', [$id])
                    ->with('success', 'Кондиционер успешно добавлен');
            }
        } catch (\Throwable $e) {
            \Log::error('Ошибка при добавлении кондиционера '.
                json_encode($r->all()).' '.$e->getMessage());
        }

        return back()->withInput($r->all())->with('error', 'Ошибка при добавлении кондиционера');
    }

    public function update(Request $r, Conditioner $conditioner)
    {
        try {
            if ($this->service->update($conditioner, $r->except('_token'))) {
                return redirect()->route('conditioners.edit', [$conditioner->id])
                    ->with('success', 'Кондиционер успешно изменен');
            }
        } catch (\Throwable $e) {
            \Log::error('Ошибка при изменении кондиционера '.
                json_encode($r->all()).' '.$e->getMessage());
        }

        return back()->withInput($r->all())->with('error', 'Ошибка при изменении кондиционера');
    }
}

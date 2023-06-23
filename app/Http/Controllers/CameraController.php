<?php

namespace App\Http\Controllers;

use App\Http\Requests\Camera\DataRequest;
use App\Repositories\CameraRepository;
use App\Repositories\RoomRepository;
use App\Services\CameraService;

class CameraController extends Controller
{
    private $cameraRep;
    private $roomRep;
    private $service;

    public function __construct(
        CameraRepository $cameraRep,
        RoomRepository $roomRep,
        CameraService $service
    )
    {
        $this->cameraRep = $cameraRep;
        $this->roomRep = $roomRep;
        $this->service = $service;
    }

    public function index()
    {
        $cameras = $this->cameraRep->getAll();

        return view('cameras.index', compact('cameras'));
    }

    // public function edit($id)
    // {
    //     $conditioner = Conditioner::findOrFail($id);
    //     $objects = $this->objectRep->getAllToArray();
    //     $devices = $this->deviceRep->getAllWithoutTypesToArray();
    //     $rooms = $this->roomRep->getAllWithoutCommonToArray();
    //     $conditionerKind = $conditioner->conditionerModel->conditionerKind;
    //     $operationModes = json_decode($conditionerKind->operationModes, true)['modes'];
    //     $fanModes = json_decode($conditionerKind->fanModes, true)['modes'];
    //     $temp = range($conditionerKind->min, $conditionerKind->max, $conditionerKind->precision);
    //     array_push($temp, 'off');

    //     return view('conditioners.edit', compact('conditioner', 'objects', 'rooms', 'operationModes', 'fanModes', 'temp', 'conditionerKind', 'devices'));
    // }

    // public function update(Request $r, Conditioner $conditioner)
    // {
    //     try {
    //         if ($this->service->update($conditioner, $r->except('_token'))) {
    //             return redirect()->route('conditioners.edit',[$conditioner->id])
    //                 ->with('success', 'Кондиционер успешно изменен');
    //         }
    //     } catch (\Throwable $e) {
    //         \Log::error('Ошибка при изменении кондиционера ' .
    //             json_encode($r->all()).' '.$e->getMessage());
    //     }

    //     return back()->withInput($r->all())->with('error', 'Ошибка при изменении кондиционера');
    // }

    public function create()
    {
        $rooms = $this->roomRep->getAllWithoutCommonToArray();

        return view('cameras.create', compact('rooms'));
    }

    public function store(DataRequest $r)
    {
        try {
            $image = $r->file('image');
            if ($id = $this->service->store($r->except('_token'), $image)) {
                // return redirect()->route('cameras.edit', [$id])
                //     ->with('success', 'Камера успешно добавлена');
            }
        } catch (\Throwable $e) {
            \Log::error('Ошибка при добавлении камеры ' .
                json_encode($r->all()).' '.$e->getMessage());
        }

        return back()->withInput($r->all())->with('error', 'Ошибка при добавлении камеры');
    }
}

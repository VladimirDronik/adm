<?php

namespace App\Http\Controllers;

use App\Http\Requests\Camera\CreateRequest;
use App\Http\Requests\Camera\UpdateRequest;
use App\Models\Camera;
use App\Repositories\RoomRepository;
use App\Services\CameraService;

class CameraController extends Controller
{
    public function __construct(
        private RoomRepository $roomRep,
        private CameraService $service
    ) {
    }

    public function edit($id)
    {
        $camera = Camera::findOrFail($id);
        $rooms = $this->roomRep->getAllWithoutCommonToArray();

        return view('cctv.camera.edit', compact('camera', 'rooms'));
    }

    public function update(UpdateRequest $r, Camera $camera)
    {
        try {
            if ($this->service->update($camera, $r->except('_token'))) {
                return redirect()->route('cameras.edit', [$camera->id])
                    ->with('success', 'Камера успешно изменена');
            }
        } catch (\Throwable $e) {
            \Log::error('Ошибка при изменении камеры '.
                json_encode($r->all()).' '.$e->getMessage());
        }

        return back()->withInput($r->all())->with('error', 'Ошибка при изменении камеры');
    }

    public function create()
    {
        $rooms = $this->roomRep->getAllWithoutCommonToArray();

        return view('cctv.camera.create', compact('rooms'));
    }

    public function store(CreateRequest $r)
    {
        try {
            if ($id = $this->service->store($r->except('_token'))) {
                return redirect()->route('cameras.edit', [$id])
                    ->with('success', 'Камера успешно добавлена');
            }
        } catch (\Throwable $e) {
            \Log::error('Ошибка при добавлении камеры '.
                json_encode($r->all()).' '.$e->getMessage());
        }

        return back()->withInput($r->all())->with('error', 'Ошибка при добавлении камеры');
    }
}

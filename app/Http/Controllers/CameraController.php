<?php

namespace App\Http\Controllers;

use App\Http\Requests\Camera\CreateRequest;
use App\Http\Requests\Camera\UpdateRequest;
use App\Models\Camera;
use App\Repositories\RoomRepository;
use App\Services\CameraService;
use Illuminate\Support\Facades\Http;

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

        return view('cctv.camera.edit', compact('camera'));
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
        $vendors = Camera::getVendors();
        $types = Camera::getTypes();

        return view('cctv.camera.create', compact('vendors', 'types'));
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

    public function getStream(Camera $camera)
    {
        $recorder = $camera->recorder;

        if (! $recorder) {
            return back()->with('error', 'Ошибка. Камера без видеорегистратора');
        }

        $link = str_replace(
            ['$login', '$password', '$ip_address'],
            [$recorder->login, customDecrypt($recorder->password, config('secret.password_key')), $recorder->ip_address],
            $camera->link
        );

        try {
            Http::post('http://localhost:9997/v3/config/paths/add/camera'.$camera->id, [
                'source' => $link,
            ]);
        } catch (\Throwable $th) {
            return back()->with('error', 'Ошибка при создании пути камеры');
        }

        return redirect('http://'.request()->host().':8888/camera'.$camera->id.'?muted=1&controls=0&autoplay=1');
    }
}

<?php

namespace App\Http\Controllers;

use App\Models\Scene;
use App\Services\ImageService;
use App\Services\SceneService;
use Illuminate\Support\Facades\Log;
use App\Repositories\SceneRepository;
use App\Http\Requests\Scene\CreateRequest;
use App\Http\Requests\Scene\UpdateRequest;

class SceneController extends Controller
{
    public function __construct(
        private SceneRepository $sceneRep,
        private SceneService $service
    ) {
    }

    public function index()
    {
        $scenes = $this->sceneRep->getAll();

        return view('scenes.index', compact('scenes'));
    }

    public function create()
    {
        $images = ImageService::getSceneImages();

        return view('scenes.create', compact('images'));
    }

    public function store(CreateRequest $r)
    {
        try {
            if ($id = $this->service->store($r->except('_token'))) {
                return redirect()
                    ->route('scenes.edit', [$id])
                    ->with('success', 'Сцена успешно добавлена');
            }
        } catch (\Throwable $e) {
            Log::error(
                'Ошибка при добавлении сцены '
                .json_encode($r->all()).' '.$e->getMessage()
            );
        }

        return back()->withInput($r->all())
            ->with('error', 'Ошибка при добавлении сцены');
    }

    public function edit(Scene $scene)
    {
        $images = ImageService::getSceneImages();

        return view('scenes.edit', compact('scene', 'images'));
    }

    public function update(UpdateRequest $r, Scene $scene)
    {
        try {
            if ($this->service->update($scene, $r->except('_token'))) {
                return redirect()
                    ->route('scenes.edit', [$scene->id])
                    ->with('success', 'Сцена успешно изменена');
            }
        } catch (\Throwable $e) {
            Log::error(
                'Ошибка при изменении сцены '.$scene->id
                .' '.json_encode($r->all()).' '.$e->getMessage()
            );
        }

        return back()->withInput($r->all())
            ->with('error', 'Ошибка при изменении сцены');
    }
}

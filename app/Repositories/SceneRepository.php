<?php

namespace App\Repositories;

use App\Models\Scene;

class SceneRepository
{
    public function getAll(int $perPage = 30)
    {
        return Scene::orderBy('sort')
            ->paginate($perPage);
    }
}

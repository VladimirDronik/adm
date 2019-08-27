<?php

namespace App\Repositories;

use App\Models\Scene;

class SceneRepository {

    public function getAll($pagination_count = 30)
    {
        return Scene::orderBy('sort')->paginate($pagination_count);
    }
}
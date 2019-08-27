<?php

namespace App\Repositories;

use App\Models\Scene;

class SceneRepository {

    public function getAll()
    {
        return Scene::orderBy('sort')->get();
    }
}
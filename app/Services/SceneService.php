<?php

namespace App\Services;

use App\Models\Scene;
use Illuminate\Support\Facades\DB;

class SceneService {

    public function delete(int $id)
    {
        $scene = Scene::find($id);

        if (!$scene) {
            return false;
        }

        DB::transaction(function () use ($scene) {
            Scene::where('sort','>', max($scene->sort, 0))->update([
                'sort' => DB::raw('sort-1'),
            ]);
            $scene->delete();
        });

        return true;
    }

    public function sort(array $data)
    {
        $scene = Scene::find($data['id']);

        if (!$scene) {
            return false;
        }

        $min = Scene::min('sort');
        $max = Scene::max('sort');

        if (($scene->sort === $min && $data['direction'] === 'up')
            || ($scene->sort === $max && $data['direction'] === 'down')) {
            return true;
        }

        $previous_sort = $scene->sort;
        $scene->sort += $data['direction'] === 'up' ? -1 : 1;

        DB::transaction(function () use ($scene, $previous_sort) {
            Scene::where('sort', $scene->sort)->update(['sort' => $previous_sort]);
            $scene->save();
        });

        return true;
    }

    public function changeActive(int $id, int $active)
    {
        Scene::where('id', $id)->update(['active' => $active]);

        return true;
    }

    public function prepareScene(Scene $scene, array $data)
    {
        $scene->name = '';
        $scene->label = trim($data['label']);
        $scene->active = $data['active'] ?? 0;
        $scene->image = basename($data['_image']);;
        $scene->backgroung_color = trim($data['backgroung_color']);
    }

    public function store(array $data)
    {
        $scene = new Scene();

        $scene->id = Scene::max('id') + 1; // todo
        $scene->sort = Scene::max('sort') + 1;
        $this->prepareScene($scene, $data);

        $scene_id = $scene->id; // todo

        $scene->save();

        return $scene_id;
    }

    public function update(Scene $scene, array $data)
    {
        $this->prepareScene($scene, $data);
        $scene->save();

        return $scene->id;
    }
}
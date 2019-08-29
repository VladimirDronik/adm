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

    // todo
    public function prepareObject(HomeObject $object, array $data)
    {
        $object->type = trim($data['type']);
        $object->name = trim($data['name']);
        $object->view = $data['view'] ?? null;
        $object->status = 'off';
    }

    public function store(array $data)
    {
        $object = new HomeObject();
        $this->prepareObject($object, $data);
        $object->save();

        return $object->id;
    }

    public function update(HomeObject $object, array $data)
    {
        $this->prepareObject($object, $data);
        $object->save();

        return $object->id;
    }
}
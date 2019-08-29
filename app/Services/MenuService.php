<?php

namespace App\Services;

use App\Models\View;

class MenuService {

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
        View::where('id', $id)->update(['active' => $active]);

        return true;
    }
}
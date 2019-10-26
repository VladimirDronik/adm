<?php

namespace App\Services;

use App\Models\Menu;
use Illuminate\Support\Facades\DB;

class MenuService {

    public function sort(array $data)
    {
        $menu = Menu::find($data['id']);

        if (!$menu) {
            return false;
        }

        $min = Menu::min('sort');
        $max = Menu::max('sort');

        if (($menu->sort === $min && $data['direction'] === 'up')
            || ($menu->sort === $max && $data['direction'] === 'down')) {
            return true;
        }

        $previous_sort = $menu->sort;
        $menu->sort += $data['direction'] === 'up' ? -1 : 1;

        DB::transaction(function () use ($menu, $previous_sort) {
            Menu::where('sort', $menu->sort)->update(['sort' => $previous_sort]);
            $menu->save();
        });

        return true;
    }

    public function changeActive(int $id, int $active)
    {
        Menu::where('id', $id)->update(['active' => $active]);

        return true;
    }
}
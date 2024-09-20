<?php

namespace App\Repositories;

use App\Models\Menu;
use Illuminate\Database\Eloquent\Collection;

class MenuRepository
{
    public function getMenuGroups(): Collection
    {
        return Menu::where('parent', '=', 0)
            ->orderBy('sort')
            ->get();
    }

    public function getParents(int $perPage = 30)
    {
        return Menu::where('parent', '=', 0)
            ->orderBy('sort')
            ->paginate($perPage);
    }

    public function getChildren(int $groupId, int $perPage = 30)
    {
        return Menu::where('parent', '=', $groupId)
            ->orderBy('sort')
            ->paginate($perPage);
    }

    public function getAll(int $perPage = 30)
    {
        return Menu::orderBy('sort')->paginate($perPage);
    }

    public function getGroup(int $id): ?Menu
    {
        return Menu::where('id', $id)->first();
    }

    public function getByName(string $name): ?Menu
    {
        return Menu::where('name', $name)->first();
    }
}

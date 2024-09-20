<?php

namespace App\Repositories;

use App\Models\Script;

class ScriptRepository
{
    public function getAllToArray(): array
    {
        return Script::orderBy('name')
            ->select('id', 'name')
            ->pluck('name', 'id')
            ->toArray();
    }

    public function getByName(?string $name, bool $withSystem = true, int $perPage = 30)
    {
        $query = Script::withCount(['systemMethods']);

        if (! empty($name)) {
            $query->where('name', 'like', '%'.$name.'%');
        }

        if (! $withSystem) {
            $query->where('system', 0);
        }

        return $query->orderBy('id')->paginate($perPage);
    }

    public static function getNameById(int $idScript): ?Script
    {
        return Script::select('name')
            ->where('id', '=', $idScript)
            ->first();
    }

    public static function getIdByLink(string $link): ?Script
    {
        return Script::select('id')
            ->where('link', '=', $link)
            ->first();
    }
}

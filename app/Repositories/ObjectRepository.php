<?php

namespace App\Repositories;

use App\Models\HomeObject;
use Illuminate\Database\Eloquent\Collection;

class ObjectRepository
{
    public function getAll(): Collection
    {
        return HomeObject::orderBy('name')->get();
    }

    public function getAllToArray(): array
    {
        return HomeObject::select('id', 'name')
            ->orderBy('name')
            ->pluck('name', 'id')
            ->toArray();
    }

    public function getAllExcludeGivenType(string $type): Collection
    {
        return HomeObject::where('type', '!=', $type)
            ->orderBy('name')
            ->get();
    }

    public static function getAllByTypes(string|array $types): array
    {
        $objects = HomeObject::query()
            ->select('id', 'name')
            ->orderBy('name');

        if (is_array($types)) {
            $objects->whereIn('type', $types);
        } else {
            $objects->where('type', $types);
        }

        return $objects->pluck('name', 'id')->toArray();
    }

    /**
     * Отдать всё инженерное оборудование
     */
    public function getAllEngineering(int $perPage = 30)
    {
        $engEquipments = ['boiler', 'boiler_gvs'];

        $queryEquipments = HomeObject::query();

        foreach ($engEquipments as $equipment) {
            $queryEquipments->orwhere('objects.type', $equipment);
        }

        return $queryEquipments->orderBy('objects.name')->paginate($perPage);
    }

    public function getByName(?string $name, int $perPage = 30)
    {
        $query = HomeObject::query();

        if (! empty($name)) {
            $query->where('name', 'like', '%'.$name.'%');
        }

        return $query->orderBy('id')->paginate($perPage);
    }

    public static function getNameById(int $idObject): ?HomeObject
    {
        return HomeObject::select('name')
            ->where('id', $idObject)
            ->first();
    }

    public static function getById(int $idObject): ?HomeObject
    {
        return HomeObject::find($idObject);
    }
}

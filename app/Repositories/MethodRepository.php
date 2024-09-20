<?php

namespace App\Repositories;

use App\Models\Method;

class MethodRepository
{
    public function getAllToArray(): array
    {
        return Method::select('id', 'name')
            ->orderBy('id')
            ->pluck('name', 'id')
            ->toArray();
    }

    public function getAllMethodsByObjectToArray(int $objectId): array
    {
        return Method::select('id', 'name')
            ->where('id_object', $objectId)
            ->orderBy('id')
            ->pluck('name', 'id')
            ->toArray();
    }

    public function getObjectByMethod(?int $idMethod): ?int
    {
        if ($idMethod) {
            $method = Method::select('id_object')
                ->where('id', $idMethod)
                ->orderBy('id')
                ->first();

            return $method?->id_object;
        } else {
            return null;
        }
    }

    public static function getMethodByID(int $idMethod): ?Method
    {
        return Method::select('name', 'id_object')
            ->where('id', $idMethod)
            ->orderBy('id')
            ->first();
    }
}

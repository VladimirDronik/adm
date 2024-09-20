<?php

namespace App\Repositories;

use App\Models\Sound;

class SoundRepository
{
    public static function getAllToArray(): array
    {
        return Sound::orderBy('name')
            ->get()
            ->pluck('name', 'id')
            ->toArray();
    }

    public static function getNameById(int $idSound): ?Sound
    {
        return Sound::find($idSound);
    }
}

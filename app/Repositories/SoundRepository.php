<?php
/**
 * Created by PhpStorm.
 * User: kinord
 * Date: 13.06.20
 * Time: 15:39
 */

namespace App\Repositories;

use App\Models\Sound;

class SoundRepository
{
    public static function getAllToArray(): array
    {
        return Sound::orderBy('name')->get()->pluck('name', 'id')->toArray();
    }

    public static function getNameById($idSound)
    {
        return Sound::where('id', $idSound)->first();
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: kinord
 * Date: 22.04.21
 * Time: 12:14
 */

namespace App\Repositories;
use App\Models\Events;

class EventRepository
{

    public function getAllById($idObject)
    {
       return Events::where('id_object', $idObject)->orderBy('name')->get();
    }
}
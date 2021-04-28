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

    /**
     * Получить все события объекта
     *
     * @param $idObject
     * @return mixed
     */
    public function getAllById($idObject)
    {

        return Events::where('id_object', $idObject)->orderBy('name')->get();
    }

    /**
     * Получить данные выбранного события
    */
    public function getEvent($idEvent)
    {
        return Events::where('id', $idEvent)->first();
    }

}
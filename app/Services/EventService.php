<?php
/**
 * Created by PhpStorm.
 * User: kinord
 * Date: 27.04.21
 * Time: 15:39
 */

namespace App\Services;

use App\Models\Events;

class EventService
{
    public function update($idEvent, $data)
    {
        $event = Events::findorfail($idEvent);

        $event->name = $data->name;
        $event->event = $data->event;
        $event->property = $data->property;
        $event->comparison = $data->comparison;
        $event->value = $data->value;

        $event->save();

        return true;
    }

    public function delete($idEvent)
    {
        $event = Events::find($idEvent);

        if (!$event) {
            return false;
        }

        $event->delete();
        return true;
    }

}
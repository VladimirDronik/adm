<?php

namespace App\Services;

use App\Models\Events;

class EventService
{
    public function __construct(
        private ActionService $actionService
    ) {
    }

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

        if (! $event) {
            return false;
        }

        $event->delete();

        return true;
    }

    public function create($data)
    {
        $event = new Events();
        $event->name = $data->name;
        $event->event = $data->event;
        $event->property = $data->property;
        $event->comparison = $data->comparison;
        $event->value = $data->value;
        $event->id_object = $data->id_object;

        $event->save();

        if (isset($data->tempActions)) {
            //Создание Actions из массива временных actions, которые были созданы ввиду отсутвия события
            $this->actionService
                ->createActionsByTempActions($data->tempActions, $event->id);
        }

        return $this->getEventData($event);
    }

    private function getEventData($event)
    {
        return [
            'id' => $event->id,
            'name' => $event->name,
            'id_object' => $event->id_object,
            'event' => $event->event,
            'property' => $event->property,
            'comparison' => $event->comparison,
            'value' => $event->value,
        ];
    }
}

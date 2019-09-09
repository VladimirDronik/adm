<?php

namespace App\Services;

use App\Models\Graph;
use App\Models\Room;
use App\Models\Termostat;

class GraphService {

    const LAST_COUNT = 10000;

    private function getTermostatData($termostat)
    {
        $values = array_slice($termostat->last_graphs->pluck('value')->toArray(), -self::LAST_COUNT);
        $labels = array_slice($termostat->last_graphs->pluck('datetime')->toArray(), -self::LAST_COUNT);

        return compact('values', 'labels');
    }

    public function getGraphData()
    {
        $termostat_ids = Graph::select('id_termostat')
            ->distinct()->pluck('id_termostat')->toArray();
        $rooms_ids = Termostat::select('room')->whereIn('id', $termostat_ids)
            ->distinct()->pluck('room')->toArray();

        $data['rooms'] = Room::whereIn('id', $rooms_ids)
            ->with('termostats', 'termostats.last_graphs')->orderBy('id')->get();

        foreach ($data['rooms'] as $room) {
            foreach ($room->termostats as $termostat) {
                 $data['termostat_'.$termostat->id] = $this->getTermostatData($termostat);
            }
        }

        $data['other_termostats'] = Termostat::with('last_graphs')->whereNull('room')->orderBy('id')->get();

        foreach ($data['other_termostats'] as $termostat) {
            $data['termostat_'.$termostat->id] = $this->getTermostatData($termostat);
        }

        return $data;
    }
}

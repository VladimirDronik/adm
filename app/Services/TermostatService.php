<?php

namespace App\Services;

use App\Models\Termostat;

class TermostatService {

    public function delete(int $id)
    {
        return Termostat::destroy($id);
    }

    public function prepare(Termostat $termostat, array $data)
    {
        $termostat->fill($data);
    }

    public function store(array $data)
    {
        $termostat = new Termostat();
        $this->prepare($termostat, $data);
        $termostat->current = 0;
        $termostat->save();

        return $termostat->id;
    }

    public function update(Termostat $termostat, array $data)
    {
        $this->prepare($termostat, $data);
        $termostat->save();

        return $termostat->id;
    }
}
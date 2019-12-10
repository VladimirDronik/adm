<?php

namespace App\Services;

use App\Models\Termostat;
use Illuminate\Support\Facades\DB;

class TermostatService {

    private $termostat_object_service;

    public function __construct(TermostatObjectService $termostat_object_service)
    {
        $this->termostat_object_service = $termostat_object_service;
    }

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

        if ($data['object_type'] === 'manual') {
            $termostat->save();
        } else {
            DB::transaction(function () use (&$termostat) {
                $object = $this->termostat_object_service->createTermostatObject($termostat->name);
                $this->termostat_object_service->createTermostatObjectMethodsWithEvents($object->id);
                $termostat->id_object = $object->id;
                $termostat->save();
            });
        }

        return $termostat->id;
    }

    public function update(Termostat $termostat, array $data)
    {
        $this->prepare($termostat, $data);
        $termostat->save();

        return $termostat->id;
    }
}
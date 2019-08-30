<?php

namespace App\Repositories;

use App\Models\Port;

class PortRepository {

    public function updateObject(array $data)
    {
        $id_object = empty($data['id_object']) ? null : $data['id_object'];
        Port::where('id', $data['id_port'])->update(['object' => $id_object]);
    }
}
<?php

namespace App\Repositories;

use App\Models\Port;

class PortRepository {

    public function updateObject(array $data)
    {
        $id_object = empty($data['id_object']) ? null : $data['id_object'];
        Port::where('id', $data['id_port'])->update(['object' => $id_object]);
    }

    public function getOutPortsByDeviceId(int $device_id)
    {
        return Port::where('id_device', $device_id)->where('status', 'out')
            ->orderBy('num_port')->get();
    }

    public function updateEasy($port_id, $easy = '')
    {
        Port::where('id', $port_id)->update(['easy' => $easy, 'object' => null,
            'method' => null, 'script' => null]);
    }

    public function updateScript($port_id, $script_id = null)
    {
        Port::where('id', $port_id)->update(['script' => $script_id, 'object' => null,
            'method' => null, 'easy' => null]);
    }

    public function updateMethod($port_id, $object_id = null, $method_id = null)
    {
        Port::where('id', $port_id)->update(['object' => $object_id, 'method' => $method_id,
            'easy' => null, 'script' => null]);
    }
}
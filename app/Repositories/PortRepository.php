<?php

namespace App\Repositories;

use App\Models\Port;

class PortRepository
{
    public function updateObject(array $data)
    {
        if (empty($data['id_object'])) {
            Port::where('id', $data['id_port'])->update(['object' => null, 'method' => null]);
        } else {
            Port::where('id', $data['id_port'])->update(['object' => $data['id_object']]);
        }
    }

    public function getOutPortsByDeviceId(int $device_id)
    {

        return Port::where('id_device', $device_id)->where('status', 'out')
            ->orderBy('num_port')->get();
    }

    public function getInPortsByDeviceId($device_id)
    {

        if ($device_id) {
            return Port::where('id_device', $device_id)->where('status', 'in')
                ->orderBy('num_port')->get();
        } else {
            return Port::where('num_port', 0)->where('status', 'in')
                    ->orderBy('num_port')->get();
        }
    }

    public function getI2CPortsByDeviceId($device_id)
    {

        if ($device_id) {
            return Port::where('id_device', $device_id)->where('status', 'I2C')
                ->orderBy('num_port')->get();
        } else {
            return Port::where('num_port', 0)->where('status', 'I2C')
                    ->orderBy('num_port')->get();
        }
    }

    /**
     * Вертнуть порты устройства, которые соответсвуют указанным типам
     */
    public function getPortsByDeviceId($deviceId, $typesPort)
    {

        $typesArr = explode(',', $typesPort);

        $sql = Port::where('id_device', $deviceId)->where(
            function ($sql) use ($typesArr) {
                foreach ($typesArr as $type) {
                    $sql->orwhere('status', trim($type));
                }
            }
        );

        return $sql->orderBy('num_port')->get();

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
        Port::where('id', $port_id)->update(['object' => $object_id, 'method' => $method_id]);
    }

    public function updateMethodByModal(array $data)
    {
        if (empty($data['id_method'])) {
            Port::where('id', $data['id_view'])->update(['method' => null]);
        } else {
            Port::where('id', $data['id_view'])->update(['method' => $data['id_method']]);
        }
    }

    public function getPortByObject($idObject)
    {
        $port = Port::where('object', $idObject)->first();

        if ($port) {
            return $port->id;
        }
    }

    /**
     * Выводит номер реального физического порта по id
     *
     * @return mixed|null
     */
    public function getNumPortByID($idPort)
    {

        if (! is_null($idPort) && $idPort != 'null') {
            return Port::where('id', $idPort)->first()->num_port;
        } else {
            return null;
        }

    }

    /**
     * Выводит номер реального физического порта по id (статическая функция)
     */
    public static function getNumberPortByID($idPort)
    {
        if (! is_null($idPort) && $idPort != 'null') {
            return Port::where('id', $idPort)->first()->num_port;
        } else {
            return null;
        }
    }
}

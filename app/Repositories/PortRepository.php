<?php

namespace App\Repositories;

use App\Models\Port;
use Illuminate\Database\Eloquent\Collection;

class PortRepository
{
    public function updateObject(array $data)
    {
        if (empty($data['id_object'])) {
            Port::where('id', $data['id_port'])
                ->update(['object' => null, 'method' => null]);
        } else {
            Port::where('id', $data['id_port'])
                ->update(['object' => $data['id_object']]);
        }
    }

    public function getOutPortsByDeviceId(int $deviceId): Collection
    {
        return Port::where('id_device', $deviceId)
            ->where('status', 'out')
            ->orderBy('num_port')
            ->get();
    }

    public function getInPortsByDeviceId(?int $deviceId): Collection
    {
        if ($deviceId) {
            return Port::where('id_device', $deviceId)
                ->where('status', 'in')
                ->orderBy('num_port')
                ->get();
        } else {
            return Port::where('num_port', 0)
                ->where('status', 'in')
                ->orderBy('num_port')
                ->get();
        }
    }

    public function getI2CPortsByDeviceId(?int $deviceId): Collection
    {
        if ($deviceId) {
            return Port::where('id_device', $deviceId)
                ->where('status', 'I2C')
                ->orderBy('num_port')
                ->get();
        } else {
            return Port::where('num_port', 0)
                ->where('status', 'I2C')
                ->orderBy('num_port')
                ->get();
        }
    }

    /**
     * Вертнуть порты устройства, которые соответсвуют указанным типам
     */
    public function getPortsByDeviceId(?int $deviceId, string $typesPort): Collection
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

    /**
     * Вертнуть порты устройства, которые соответсвуют указанным типам
     */
    public function getPortsByTypes(?int $deviceId, string $types): Collection
    {
        $typesArr = explode(',', $types);

        $sql = Port::where('id_device', $deviceId)->where(
            function ($sql) use ($typesArr) {
                foreach ($typesArr as $type) {
                    $sql->orwhere('type', trim($type));
                }
            }
        );

        return $sql->orderBy('num_port')->get();
    }

    public function updateEasy(int $portId, string $easy = '')
    {
        Port::where('id', $portId)
            ->update([
                'easy' => $easy,
                'object' => null,
                'method' => null,
                'script' => null,
            ]);
    }

    public function updateScript(int $portId, ?int $scriptId = null)
    {
        Port::where('id', $portId)
            ->update([
                'script' => $scriptId,
                'object' => null,
                'method' => null,
                'easy' => null,
            ]);
    }

    public function updateMethod(int $portId, ?int $objectId = null, ?int $methodId = null)
    {
        Port::where('id', $portId)
            ->update([
                'object' => $objectId,
                'method' => $methodId,
            ]);
    }

    public function updateMethodByModal(array $data)
    {
        if (empty($data['id_method'])) {
            Port::where('id', $data['id_view'])
                ->update(['method' => null]);
        } else {
            Port::where('id', $data['id_view'])
                ->update(['method' => $data['id_method']]);
        }
    }

    public function getPortByObject(int $idObject): ?int
    {
        $port = Port::where('object', $idObject)->first();

        return $port?->id;
    }

    /**
     * Выводит номер реального физического порта по id
     */
    public function getNumPortByID(mixed $idPort)
    {
        if ($idPort && $idPort != 'null') {
            return Port::where('id', $idPort)->first()?->num_port;
        } else {
            return null;
        }
    }

    /**
     * Выводит номер реального физического порта по id (статическая функция)
     */
    public static function getNumberPortByID(mixed $idPort)
    {
        if (! is_null($idPort) && $idPort != 'null') {
            return Port::where('id', $idPort)->first()?->num_port;
        } else {
            return null;
        }
    }
}

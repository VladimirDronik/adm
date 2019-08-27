<?php

namespace App\Services;

use App\Models\Device;
use App\Models\Port;
use Illuminate\Support\Facades\DB;

class DeviceService {

    private $device;

    public function delete(int $id)
    {
        return Device::destroy($id);
    }

    public function storePorts()
    {
        $ports = [];
        $devtype = $this->device->devtype;

        foreach (['in','out'] as $status) {
            for ($num_port = $devtype->{'start_' . $status}; $num_port <= $devtype->{'end_' . $status}; $num_port++) {
                $ports[] = [
                    'id_device' => $this->device->id,
                    'num_port' => $num_port,
                    'status' => $status,
                    'comment' => ''
                ];
            }
        }

        Port::insert($ports);
    }

    public function storeDevice($data)
    {
        $this->device = new Device();
        $this->device->fill($data);
        $this->device->active = 0;
        $this->device->save();
    }

    public function store(array $data)
    {
        DB::beginTransaction();

        try {

            $this->storeDevice($data);
            $this->storePorts();

            DB::commit();

            return $this->device->id;

        } catch (\Throwable $e) {
            DB::rollback();
            throw $e;
        }
    }

    public function update(Device $device, array $data)
    {
        $device->fill($data);
        $device->save();

        return $device->id;
    }
}
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

    public function storeDevice(array $data)
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

    public function update(array $data)
    {
        // todo validate data

        $device = Device::find($data['id']);

        if (!$device) {
            return false;
        }

        $device->description = trim($data['description']);
        $device->ip_address = trim($data['ip_address']);

        $device->save();

        return true;
    }

    public function updatePort(array $data)
    {
        $port = Port::where('id', $data['port_id'])
            ->where('id_device', $data['id'])->first();
        
        if (!$port) {
            return false;
        }

        $port->{$data['name']} = $data['value'];

        $port->save();

        return true;
    }

    public function getPortsByDeviceId(int $device_id)
    {
        if ($device_id) {
            $ports = Port::where('id_device', $device_id)->orderBy('num_port')
                ->pluck('num_port', 'id')->toArray();

            return array_values($ports);
        }

        return [];
    }
}
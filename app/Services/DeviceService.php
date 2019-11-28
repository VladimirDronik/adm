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
            if ($devtype->{'start_' . $status} === 0 && $devtype->{'end_' . $status} === 0) {
                break;
            }
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

    private function isDoubleDescription(array $data)
    {
        return Device::where('id','!=',$data['id'])
            ->where('description',$data['description'])->exists();
    }

    private function isValidIpAddress($ip)
    {
        return filter_var($ip, FILTER_VALIDATE_IP);
    }

    public function update(array $data)
    {
        trimArray($data);

        if ($this->isDoubleDescription($data)) {
            return [false, 'Устройство с таким название уже существует. Необходимо изменить название'];
        }

        if (!$this->isValidIpAddress($data['ip_address'])) {
            return [false, 'Недопустимый ip адрес'];
        }

        $device = Device::find($data['id']);

        if (!$device) {
            return [false, 'Устройство не найдено'];
        }

        $device->description = $data['description'];
        $device->ip_address = $data['ip_address'];

        $device->save();

        return [true, ''];
    }

    public function updatePort(array $data)
    {
        $port = Port::where('id', $data['port_id'])
            ->where('id_device', $data['id'])->first();
        
        if (!$port) {
            return false;
        }

        $port->{$data['name']} = trim($data['value']);

        $port->save();

        return true;
    }

    public function getPortsByDeviceId(int $device_id)
    {
        if (!$device_id) {
            return [];
        }

        $ports = Port::where('id_device', $device_id)->orderBy('num_port')
            ->pluck('num_port', 'id')->toArray();

        return array_values($ports);
    }
}
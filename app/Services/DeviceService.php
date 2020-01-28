<?php

namespace App\Services;

use App\Models\Device;
use App\Models\Port;
use Illuminate\Support\Facades\DB;

class DeviceService {

    private $device;

    private $networkService;

    public function __construct(NetworkService $networkService)
    {
        $this->networkService = $networkService;
    }

    /**
     * @param int $id
     * @return bool
     * @throws \Throwable
     */
    public function delete(int $id)
    {
        DB::transaction(function () use ($id) {
            Port::where('id_device', $id)->delete();
            Device::destroy($id);
        });

        return true;
    }

    public function storePorts()
    {
        Port::insert($this->device->devtype->getPortsForInserting($this->device->id));
    }

    private function getDeviceIpNotificationParams(string $eip, string $sip): string
    {
        return "cf=1&eip={$eip}&pwd=to1&gw=255.255.255.255&sip={$sip}&sct=md.php&pr=&gsm=&srvt=0";
    }

    /**
     * Передача ip нового устройства на удаленный сервер
     *
     * @param string $ip
     * @throws \Exception
     */
    public function notifyDeviceIp(string $ip)
    {
        $sip = $this->networkService->getIface()[0];

        if (empty($sip)) {
            throw new \Exception('Не указан ip-адрес для подсети устройств в разделе «Сеть и VPN»');
        }

        $answer = file_get_contents($this->getDeviceIpNotificationParams($ip, $sip));

        if ($answer === false) {
            throw new \Exception('Некорректный ответ от удаленного сервера');
        }
    }

    /**
     * Создание устройства с оповещением на удаленный сервер ip и созданием портов
     *
     * @param array $data
     * @throws \Exception
     */
    public function storeDevice(array $data)
    {
        $this->device = new Device();

        $this->device->fill($data);
        $this->device->active = 0;

        $this->device->save();

        $this->notifyDeviceIp($data['ip_address']);
    }

    /**
     * @param array $data
     * @return mixed
     * @throws \Throwable
     */
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

    private function isValidIpAddress(string $ip)
    {
        return filter_var($ip, FILTER_VALIDATE_IP);
    }

    /**
     * Изменение устройства с оповещением ip удаленного сервера
     *
     * @param array $data
     * @return array
     * @throws \Exception
     */
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

        if (trim($data['ip_address']) !== $device->ip_address) {

            $device->ip_address = $data['ip_address'];

            DB::beginTransaction();

            try {

                $device->save();

                $this->notifyDeviceIp($data['ip_address']);

                DB::commit();

                return [true, ''];

            } catch (\Throwable $e) {

                DB::rollback();

                \Log::error('Ошибка при обновлении устройства', [$e->getMessage()]);
            }

        } else {

            $device->save();
            return [true, ''];

        }

        return [false, 'Не удалось изменить данные устройства'];
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
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
        $gw = explode(':', $sip)[0];

        $sip_with_port = strpos($sip, ':') === false ? $sip.'%3A8080' : $sip;

        return "http://192.168.99.50/to1/?cf=1&eip={$eip}&pwd=to1&gw={$gw}&sip={$sip_with_port}&sct=md.php";
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
    public function storeDevice(array $data, bool $is_notify = true)
    {
        $this->device = new Device();

        $this->device->fill($data);
        $this->device->active = 1;

        $this->device->save();

        if ($is_notify) {
            $this->notifyDeviceIp($data['ip_address']);
        }
    }

    /**
     * @param array $data
     * @return mixed
     * @throws \Throwable
     */
    public function store(array $data, bool $is_notify = false) //true для реализации функции настройки устройства с дефолтным адресом
    {
        DB::beginTransaction();

        try {

            $this->storeDevice($data, $is_notify);
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

    public function getPortsWithObjectsByDeviceId(int $device_id, string $status = '')
    {
        if (!$device_id) {
            return [];
        }

        $statuses_array = explode(',',$status);

        $ports = Port::with('eobject')->where('id_device', $device_id);

        if ($status !== '') {

            $cnt=0;
            foreach ($statuses_array as $statusPort) {

                if($cnt==0)
                $ports->where('status', $statusPort);
                else
                    $ports->orWhere('status', $statusPort);

                $cnt++;
            }

        }

        $ports = $ports->orderBy('status')->orderBy('num_port')->get();

        $arrayPorts = [];

        foreach ($ports as $port) {
            $arrayPorts[] = [
                'id' => $port->id,
                'name' => $port->status.' ['.$port->num_port.']'
                    .($port->eobject ? ' ('.optional($port->eobject)->name.')' : '')
            ];
        }

        return $arrayPorts;
    }
}
<?php

namespace App\Services;

use App\Models\Device;
use App\Models\HiteproDev;
use App\Models\Port;
use App\Repositories\DeviceRepository;
use Illuminate\Support\Facades\DB;


class DeviceService {

    private $device;

    private $networkService;
    private $deviceRepository;

    public function __construct(NetworkService $networkService, DeviceRepository $deviceRepository)
    {
        $this->networkService = $networkService;
        $this->deviceRepository = $deviceRepository;
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

    private function getDeviceIpNotificationParams(string $oldIP, string $eip, string $sip): string
    {
        $gw = explode(':', $sip)[0];

        $sip_with_port = strpos($sip, ':') === false ? $sip.'%3A8080' : $sip;

        return "http://{$oldIP}/sec/?cf=1&eip={$eip}&pwd=sec&gw={$gw}&sip={$sip_with_port}&sct=md.php";
    }

    /**
     * Передача ip нового устройства на удаленный сервер
     *
     * @param array $data
     * @throws \Exception
     */
    public function notifyDeviceIp(array $data)
    {
        $sip = $this->networkService->getIface()[0];
        $oldIP = DeviceService::getDeviceIP($data['id']);

        if (empty($sip)) {
            throw new \Exception('Не указан ip-адрес для подсети устройств в разделе «Сеть и VPN»');
        }

        if(self::getStatus($data['id']))
        $answer = file_get_contents($this->getDeviceIpNotificationParams($oldIP, $data['ip_address'], $sip));
        else $answer = false;

        if ($answer === false) {
            throw new \Exception('Некорректный ответ от устройства или устройство недоступно');
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
            $this->notifyDeviceIp($data);
        }
    }

    /**
     * @param array $data
     * @return mixed
     * @throws \Throwable
     */
    public function store(array $data, bool $is_notify = false) //true для реализации функции настройки устройства с дефолтным адресом
    {

        $typeDevice = $this->deviceRepository->getDevTypeById($data['type']);

        DB::beginTransaction();

        try {

            $this->storeDevice($data, $is_notify);
            if($typeDevice != 'Hite-pro')
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

    private function isValidIpAddress(array $data)
    {
        $doubleAddress = Device::where('id','!=',$data['id'])
            ->where('ip_address',$data['ip_address'])->exists();


        $filterAddress = filter_var($data['ip_address'], FILTER_VALIDATE_IP);

        (!$doubleAddress && $filterAddress) ? $return = true : $return = false;

        return $return;
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
            return [false, 'Устройство с таким названием уже существует. Необходимо изменить название', '', ''];
        }


        if (!$this->isValidIpAddress($data)) {
            return [false, 'Недопустимый ip адрес'.$this->isValidIpAddress($data), '', ''];
        }

        $device = Device::find($data['id']);

        if (!$device) {
            return [false, 'Устройство не найдено', '', ''];
        }

        $device->description = $data['description'];

        //Заливаем конфиг на устройство
        $configResult = ConfigMegaService::sendConfigToDevice($data['id']);

        if (trim($data['ip_address']) !== $device->ip_address) {

            $device->ip_address = $data['ip_address'];

            DB::beginTransaction();

            try {

                $device->save();

                //Меняем адрес устойства
                $this->notifyDeviceIp($data);

                DB::commit();

                if($configResult['error'] == '')
                    return [true, '', $configResult['count_all'], $configResult['count_result']];
                else
                    return [false, $configResult['error'], '', ''];


            } catch (\Throwable $e) {

                DB::rollback();

                \Log::error('Ошибка при обновлении устройства', [$e->getMessage()]);
            }

        } else {

            $device->save();

            if($configResult['error'] == '')
                return [true, '', $configResult['count_all'], $configResult['count_result']];
            else
                return [false, $configResult['error'], '', ''];

        }

        return [false, 'Не удалось изменить данные устройства: '.$e->getMessage(), '', ''];
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

    /**
     * Получение статуса устройства, активно или нет
     */
    public static function getStatus($idDevice)
    {
        return Device::where('id', $idDevice)->first()->active;
    }

    /**
     * Выводит ip адрес устройства
     * @param $idDevice
     */
    public static function getDeviceIP($idDevice)
    {
        return Device::where('id', $idDevice)->first()->ip_address;
    }

    /**
     * Загружает устройства, которые висят на контроллере hite-pro
     */
    public static function readHiteproDevices($id, $ipHitepro, $password)
    {
        $url = 'http://'.$ipHitepro.'/rest/devices';

            $options = [
                'http' => [
                    'method'  => 'GET',
                    'header'  => [
                        'Content-type: application/json',
                        'Authorization: Basic ' . $password,
                    ],
                ],
            ];
            $context = stream_context_create($options);

            $contents = file_get_contents($url, false, $context);

            $devicesArray = json_decode($contents);


            HiteproDev::where('id_controller', $id)->delete();

            foreach ($devicesArray AS $device) {

                $HiteProDevice = new HiteproDev();
                $HiteProDevice->id = $device->id;
                $HiteProDevice->id_controller = $id;
                $HiteProDevice->name = $device->name;
                $HiteProDevice->type = $device->type;
                $HiteProDevice->status = $device->status;
                $HiteProDevice->save();
            }

            return json_decode($contents);

    }

    public static function getHPDevices($idDevice) {


        if (!$idDevice) {
            return [];
        }

        $devices = HiteproDev::where('id_controller', $idDevice)->get();


            $arrayDevices = [];

            foreach ($devices as $device) {
                $arrayDevices[] = [
                    'id' => $device->id,
                    'name' => '['.$device->type.'] '.$device->name
                ];
            }

            return $arrayDevices;
    }
}
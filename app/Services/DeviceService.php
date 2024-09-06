<?php

namespace App\Services;

use App\Models\Port;
use App\Models\Device;
use App\Models\ExtensionModule;
use Illuminate\Support\Facades\DB;
use App\Models\ExtensionModuleType;
use App\Repositories\PortRepository;
use App\Repositories\DeviceRepository;

class DeviceService
{
    private $device;

    public function __construct(
        private NetworkService $networkService,
        private DeviceRepository $deviceRepository,
        private PortRepository $portRepository
    ) {
    }

    /**
     * @return bool
     *
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

    /**
     * @return bool
     *
     * @throws \Throwable
     */
    public function extensionModuleDelete(int $extensionModuleId)
    {
        DB::transaction(function () use ($extensionModuleId) {
            Port::where('extension_module_id', $extensionModuleId)->delete();
            ExtensionModule::destroy($extensionModuleId);
        });

        return true;
    }

    public function storePorts()
    {
        Port::insert($this->device->devtype->getPortsForInserting($this->device->id));
    }

    /**
     * Создание портов для модуля расширения
     *
     * @return void
     */
    public function storeExtensionModulePorts(ExtensionModule $extensionModule, int $deviceId)
    {
        Port::insert($extensionModule->extensionModuleType->getPortsForInserting($deviceId, $extensionModule->id));
    }

    private function getDeviceIpNotificationParams(string $oldIP, string $eip, string $sip): string
    {
        $gw = explode(':', $sip)[0];

        $sipWithPort = strpos($sip, ':') === false ? $sip.'%3A8080' : $sip;

        return "http://{$oldIP}/sec/?cf=1&eip={$eip}&pwd=sec&gw={$gw}&sip={$sipWithPort}&sct=md.php";
    }

    /**
     * Передача ip нового устройства на удаленный сервер
     *
     * @throws \Exception
     */
    public function notifyDeviceIp(array $data)
    {
        $sip = $this->networkService->getIface()[0];
        $oldIP = DeviceService::getDeviceIP($data['id']);

        if (empty($sip)) {
            throw new \Exception('Не указан ip-адрес для подсети устройств в разделе «Настройка сети»');
        }

        if (self::getStatus($data['id'])) {
            $answer = file_get_contents($this->getDeviceIpNotificationParams($oldIP, $data['ip_address'], $sip));
        } else {
            $answer = false;
        }

        if ($answer === false) {
            throw new \Exception('Некорректный ответ от устройства или устройство недоступно');
        }
    }

    /**
     * Создание устройства с оповещением на удаленный сервер ip и созданием портов
     *
     * @throws \Exception
     */
    public function storeDevice(array $data, bool $isNotify = true)
    {
        $this->device = new Device();

        $this->device->fill($data);

        $this->device->save();

        if ($isNotify) {
            $this->notifyDeviceIp($data);
        }
    }

    /**
     * @return mixed
     *
     * @throws \Throwable
     */
    public function store(array $data, bool $forcedCreate = true, bool $isNotify = false)
    {
        $typeDevice = $data['type'];

        $data['type'] = $this->deviceRepository->getIdTypeByName($typeDevice);

        DB::beginTransaction();

        try {
            exec("ping -c 1 {$data['ip_address']}", $output, $status);

            if ($status == 0) {
                $data['active'] = 1;
            } else {
                $data['active'] = 0;
            }

            if (($data['active'] == 1) || ($forcedCreate)) {
                $this->storeDevice($data, $isNotify);

                $this->storePorts();

                DB::commit();

                return $this->device->id;
            } else {
                throw new \Exception('Устройство недоступно!');
            }
        } catch (\Throwable $e) {
            DB::rollback();
            throw $e;
        }
    }

    private function isDoubleDescription(array $data)
    {
        return Device::where('id', '!=', $data['id'])
            ->where('description', $data['description'])
            ->exists();
    }

    private function isValidIpAddress(array $data)
    {
        $doubleAddress = Device::where('id', '!=', $data['id'])
            ->where('ip_address', $data['ip_address'])
            ->exists();

        $filterAddress = filter_var($data['ip_address'], FILTER_VALIDATE_IP);

        (! $doubleAddress && $filterAddress) ? $return = true : $return = false;

        return $return;
    }

    /**
     * Добавление модулей расширения
     *
     * @throws \Exception
     */
    private function storeExtensionModules(array $modules, Device $device)
    {
        DB::beginTransaction();

        try {
            foreach ($modules as $module) {
                $moduleType = ExtensionModuleType::find($module['extension_module_type_id']);
                $intPort = null;

                if ($moduleType->name == 'MegaD-16I-XT') {
                    $intPort = $module['int_port'];
                }

                $extensionModule = ExtensionModule::create([
                    'extension_module_type_id' => $module['extension_module_type_id'],
                    'device_id' => $device->id,
                    'sda_port' => $module['sda_port'],
                    'scl_port' => $module['scl_port'],
                    'int_port' => $intPort,
                ]);

                $this->storeExtensionModulePorts($extensionModule, $device->id);
            }
            DB::commit();
        } catch (\Throwable $e) {
            DB::rollback();
            throw $e;
        }
    }

    /**
     * Изменение устройства с оповещением ip удаленного сервера
     *
     * @return array
     *
     * @throws \Exception
     */
    public function update(array $data)
    {
        $extensionModules = null;

        if (array_key_exists('extension_modules', $data)) {
            $extensionModules = $data['extension_modules'];
            unset($data['extension_modules']);
        }

        trimArray($data);

        if ($this->isDoubleDescription($data)) {
            return ['result' => false, 'message' => 'Устройство с таким названием уже существует. Необходимо изменить название', '', ''];
        }

        $device = Device::find($data['id']);

        if (! $device) {
            return ['result' => false, 'message' => 'Устройство не найдено', '', ''];
        }

        if (! $this->isValidIpAddress($data)) {
            return ['result' => false, 'message' => 'Недопустимый ip адрес', '', ''];
        }

        $device->description = $data['description'];
        $device->password = $data['password'] ?: null;

        if ($data['port'] === '0') {
            $device->port = $data['port'];
        } else {
            $device->port = $data['port'] ?: null;
        }

        if (trim($data['ip_address']) !== $device->ip_address) {
            $device->ip_address = $data['ip_address'];

            DB::beginTransaction();

            try {
                $device->save();

                //Меняем адрес устойства
                $this->notifyDeviceIp($data);

                if ($extensionModules) {
                    $this->storeExtensionModules($extensionModules, $device);
                }

                DB::commit();

                return ['result' => true];
            } catch (\Throwable $e) {
                DB::rollback();
                \Log::error('Ошибка при обновлении устройства', [$e->getMessage()]);
            }
        } else {
            $device->save();

            if ($extensionModules) {
                $this->storeExtensionModules($extensionModules, $device);
            }

            return ['result' => true];
        }

        return ['result' => false, 'message' => 'Не удалось изменить данные устройства: '.$e->getMessage(), '', ''];
    }

    public function updatePort(array $data)
    {
        $port = Port::where('id', $data['port_id'])
            ->where('id_device', $data['id'])
            ->first();

        if (! $port) {
            return false;
        }

        $port->{$data['name']} = trim($data['value']);

        $port->save();

        return true;
    }

    public function getPortsByDeviceId(int $deviceId)
    {
        if (! $deviceId) {
            return [];
        }

        $ports = Port::where('id_device', $deviceId)
            ->orderBy('num_port')
            ->pluck('num_port', 'id')
            ->toArray();

        return array_values($ports);
    }

    public function getPortsWithObjectsByDeviceId(int $deviceId, string $status = '')
    {
        if (! $deviceId) {
            return [];
        }

        $ports = $this->portRepository
            ->getPortsByDeviceId($deviceId, $status);

        $arrayPorts = [];

        foreach ($ports as $port) {
            $arrayPorts[] = [
                'id' => $port->id,
                'name' => $port->status.' ['.$port->num_port.']'
                    .($port->extensionModule ? ' EXT_SDA ('.$port->extensionModule->sda_port.')' : '')
                    .($port->eobject ? ' ('.optional($port->eobject)->name.')' : ''),
            ];
        }

        return $arrayPorts;
    }

    /**
     * Вертнуть свободные и занятые порты устройства, которые соответсвуют указанным типам
     */
    public function getAllDevicePortsByPortType(?int $deviceId, string $types = '')
    {
        if (! $deviceId) {
            return [];
        }

        $ports = $this->portRepository->getPortsByTypes($deviceId, $types);
        $arrayPorts = [];

        foreach ($ports as $port) {
            $arrayPorts[] = [
                'name' => 'Порт '.$port->type.' ['.$port->num_port.']'.(
                    $port->eobject ?
                    ': <span style="color:red">занят</span> '.$port->eobject->name.' '.$port->eobject->id :
                    ': <span style="color:green">свободен</span>'
                ),
            ];
        }

        return $arrayPorts;
    }

    /**
     * Вертнуть свободные порты устройства, которые соответсвуют указанным типам
     *
     * @param  null|int  $currentObjectId = null
     */
    public function getFreeDevicePortsByPortType(?int $deviceId, string $types, int $currentObjectId = null)
    {
        if (! $deviceId) {
            return [];
        }

        $ports = $this->portRepository->getPortsByTypes($deviceId, $types);
        $arrayPorts = [];

        foreach ($ports as $port) {
            if (($currentObjectId && $port->eobject && $port->eobject->id == $currentObjectId) || ! $port->eobject) {
                $arrayPorts[] = [
                    'id' => $port->id,
                    'num_port' => $port->num_port,
                    'name' => 'Порт '.$port->type.' ['.$port->num_port.']',
                ];
            }
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
     */
    public static function getDeviceIP($idDevice)
    {
        return Device::where('id', $idDevice)->first()->ip_address;
    }
}

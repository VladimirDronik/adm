<?php

namespace App\Services;

use App\Models\Device;
use App\Models\ExtensionModule;
use App\Models\Port;
use App\Repositories\DeviceRepository;
use App\Repositories\PortRepository;
use Illuminate\Support\Facades\DB;

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

        $sip_with_port = strpos($sip, ':') === false ? $sip.'%3A8080' : $sip;

        return "http://{$oldIP}/sec/?cf=1&eip={$eip}&pwd=sec&gw={$gw}&sip={$sip_with_port}&sct=md.php";
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
    public function storeDevice(array $data, bool $is_notify = true)
    {
        $this->device = new Device();

        $this->device->fill($data);
        //$this->device->active = 1;

        $this->device->save();

        if ($is_notify) {
            $this->notifyDeviceIp($data);
        }
    }

    /**
     * @return mixed
     *
     * @throws \Throwable
     */
    public function store(array $data, bool $forcedCreate = true, bool $is_notify = false) //true для реализации функции настройки устройства с дефолтным адресом
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
                $this->storeDevice($data, $is_notify);

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
                $extensionModule = ExtensionModule::create([
                    'extension_module_type_id' => $module['extension_module_type_id'],
                    'device_id' => $device->id,
                    'sda_port' => $module['sda_port'],
                    'scl_port' => $module['scl_port'],
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
            return [false, 'Устройство с таким названием уже существует. Необходимо изменить название', '', ''];
        }

        if (! $this->isValidIpAddress($data)) {
            return [false, 'Недопустимый ip адрес'.$this->isValidIpAddress($data), '', ''];
        }

        $device = Device::find($data['id']);

        if (! $device) {
            return [false, 'Устройство не найдено', '', ''];
        }

        $device->description = $data['description'];
        $device->password = $data['password'] ?: null;
        $device->port = $data['port'] ?: null;

        $configResult = ConfigMegaService::sendConfigToDevice($data['id']);

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

                if ($configResult['error'] == '') {
                    return [true, '', $configResult['count_all'], $configResult['count_result']];
                } else {
                    return [false, $configResult['error'], '', ''];
                }
            } catch (\Throwable $e) {
                DB::rollback();

                \Log::error('Ошибка при обновлении устройства', [$e->getMessage()]);
            }
        } else {
            $device->save();

            if ($extensionModules) {
                $this->storeExtensionModules($extensionModules, $device);
            }

            if ($configResult['error'] == '') {
                return [true, '', $configResult['count_all'], $configResult['count_result']];
            } else {
                return [false, $configResult['error'], '', ''];
            }
        }

        return [false, 'Не удалось изменить данные устройства: '.$e->getMessage(), '', ''];
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

    public function getPortsByDeviceId(int $device_id)
    {
        if (! $device_id) {
            return [];
        }

        $ports = Port::where('id_device', $device_id)
            ->orderBy('num_port')
            ->pluck('num_port', 'id')
            ->toArray();

        return array_values($ports);
    }

    public function getPortsWithObjectsByDeviceId(int $device_id, string $status = '')
    {
        if (! $device_id) {
            return [];
        }

        $ports = $this->portRepository
            ->getPortsByDeviceId($device_id, $status);

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

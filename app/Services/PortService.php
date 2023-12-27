<?php

namespace App\Services;

use App\Models\Device;
use App\Models\HiteproDev;
use App\Models\LedTape;
use App\Models\Method;
use App\Models\Port;
use App\Models\Script;
use App\Repositories\DeviceRepository;
use App\Repositories\HiteProDevRepository;
use App\Repositories\PortRepository;
use Illuminate\Support\Facades\DB;

class PortService
{
    const NONE = 'отсутствует';

    public function __construct(
        private PortRepository $rep,
        private ObjectService $object_service,
        private DeviceRepository $device_rep,
        private HiteProDevRepository $hiteproDevRep
    ) {
    }

    public function updateComment(array $data)
    {
        $comment = trim($data['comment']);

        if ($comment === 'Отсутствует') {
            $comment = '';
        }

        Port::where('id', $data['port_id'])
            ->where('id_device', $data['device_id'])
            ->update(['comment' => $comment]);
    }

    public function getViewMethod($r)
    {
        $device = $port = $act = '';

        $eport = Port::findOrFail($r->port_id);

        // Выбираемый метод равен существующему методу порта
        $value = $r->cur_method === $r->methodmode ? $r->value : self::NONE;

        if ($r->methodmode === 'easy') {
            // Разбираем значение для простого действия
            if ($value !== self::NONE) {
                $easy = explode(';', $r->value);
                $easy1 = explode(':', $easy[1]);
                $device = $easy[0];
                $port = $easy1[0];
                $act = $easy1[1];
            } else {
                $device = $port = $act = self::NONE;
            }
        } elseif ($r->methodmode === 'method') {
            if ($r->cur_method === $r->methodmode) {
                $value = empty($eport->method) ? self::NONE : optional($eport->emethod)->name;
            }
        }

        $html = (string) view('ajax.actions',
            ['action' => $r->methodmode, 'port_id' => $r->port_id, 'object' => $eport->object]
            + compact('device', 'port', 'act', 'value'));

        return $html;
    }

    public function getViewData($r)
    {
        switch ($r->mode) {
            case 'device':
                $devices = Device::orderBy('description')->get();
                $title_action = 'Выбор контроллера';
                $html = (string) view('ajax.devices', compact('devices'));
                break;

            case 'port':
                //Определяем, если девайс - хитпро, то выводим устройство вместо портов
                if (DeviceRepository::getDevByIdDevice((int) $r->device)['type'] == 'Hite-pro') {
                    $devices = $this->hiteproDevRep->getHPDevByDeviceId((int) $r->device);
                    $title_action = 'Выбор устройства';
                    $html = (string) view('ajax.HPDevices', compact('devices'));
                } else {
                    $ports = $this->rep->getOutPortsByDeviceId((int) $r->device);
                    $title_action = 'Выбор порта';
                    $html = (string) view('ajax.ports', compact('ports'));
                }
                break;

            case 'action':
                $title_action = 'Выбор действия';
                $html = (string) view('ajax.act');
                break;

            case 'script':
                $scripts = Script::orderBy('name')->get();
                $title_action = 'Выбор скрипта';
                $html = (string) view('ajax.scripts', compact('scripts'));
                break;

            case 'method':
                $methods = Method::where('id_object', $r->object_id)
                    ->orderBy('name')
                    ->get();
                $title_action = 'Выбор метода объекта';
                $html = (string) view('ajax.methods', compact('methods'));
                break;
        }

        return compact('html', 'title_action');
    }

    public function storeMethod($r)
    {
        switch ($r->methodmode) {
            case 'easy':
                $this->rep->updateEasy($r->id_port, $r->device.';'.$r->port.':'.$r->act);
                break;

            case 'method':
                $this->rep->updateMethod($r->id_port, $r->id_object, $r->method_id);
                break;

            case 'script':
                $this->rep->updateScript($r->id_port, $r->id_script);
                break;

            case 'none':
                $this->rep->updateMethod($r->id_port);
                break;
        }
    }

    /**
     * @throws \Exception
     */
    public function getPortMethods(array $data): array
    {
        $port = Port::where('id_device', $data['device_id'])
            ->where('id', $data['port_id'])
            ->first();

        if (! $port) {
            throw new \Exception('Порт не найден');
        }

        $portData = $this->getPortMethod($port, $data['type']);

        $portData['type'] = $data['type'];
        $portData['port_id'] = $port->id;

        $objects = $this->object_service->getObjects();
        $portData['objects'] = [];
        foreach ($objects as $object) {
            $portData['objects'][] = [
                'id' => $object->id,
                'name' => $object->name,
                'type_img' => (string) view('objects.type_img', compact('object')),
            ];
        }

        $portData['methods'] = $this->getMethods(
            $portData['object_id'],
            $portData['objects']
        );

        return $portData;
    }

    private function getMethods(int $object_id, array $objects): array
    {
        if ($object_id) {
            return $this->object_service
                ->getMethodsByObjectId($object_id);
        }

        if (count($objects)) {
            return $this->object_service
                ->getMethodsByObjectId($objects[0]['id']);
        }

        return [];
    }

    private function getPortMethod($port, string $type): array
    {
        $data = [];

        if ($type === 'ordinary' && $port->emethod) {
            $data['method_id'] = $port->method;
            $data['object_id'] = $port->emethod->id_object;
            $data['method_name'] = $port->emethod->name;
            $data['object_name'] = optional($port->emethod->eobject)->name;
            $data['params'] = $port->method_params;
        } elseif ($type === 'double' && $port->dcmethod) {
            $data['method_id'] = $port->dc_method;
            $data['object_id'] = $port->dcmethod->id_object;
            $data['method_name'] = $port->dcmethod->name;
            $data['object_name'] = optional($port->dcmethod->eobject)->name;
            $data['params'] = $port->dc_method_params;
        } elseif ($type === 'long' && $port->lcmethod) {
            $data['method_id'] = $port->lc_method;
            $data['object_id'] = $port->lcmethod->id_object;
            $data['method_name'] = $port->lcmethod->name;
            $data['object_name'] = optional($port->lcmethod->eobject)->name;
            $data['params'] = $port->lc_method_params;
        } else {
            $data['method_id'] = 0;
            $data['object_id'] = 0;
        }

        return $data;
    }

    public function deletePortMethod(array $data)
    {
        $methodColumnName = $this->getMethodColumnName($data['type']);

        Port::where('id_device', $data['device_id'])
            ->where('id', $data['port_id'])
            ->update([$methodColumnName => null]);
    }

    /**
     * Удаление всех методов для порта
     */
    public static function deleteAllMethodsForPort($idObject)
    {
        Port::where('object', $idObject)
            ->update([
                'method' => null,
                'dc_method' => null,
                'lc_method' => null,
                'method_params' => null,
                'dc_method_params' => null,
                'lc_method_params' => null,
            ]);
    }

    private function getMethodColumnName(string $type): string
    {
        if ($type === 'ordinary') {
            return 'method';
        } elseif ($type === 'double') {
            return 'dc_method';
        } elseif ($type === 'long') {
            return 'lc_method';
        }

        return '';
    }

    public function getObjectMethods($object_id)
    {
        return $this->object_service
            ->getMethodsByObjectId($object_id);
    }

    public function updatePortMethod(array $data): array
    {
        $methodColumnName = $this->getMethodColumnName($data['type']);

        if ($data['params'] === '') {
            Port::where('id_device', $data['device_id'])
                ->where('id', $data['port_id'])
                ->update([$methodColumnName => $data['method_id']]);
        } else {
            Port::where('id_device', $data['device_id'])
                ->where('id', $data['port_id'])
                ->update([
                    $methodColumnName => $data['method_id'],
                    $methodColumnName.'_params' => $data['params'],
                ]);
        }

        $port = Port::find($data['port_id']);

        return $this->getPortMethod($port, $data['type']);
    }

    /**
     * Добавление портов для вывода в выпадающем списке
     *
     * @param  int  $deviceId - ИД устройства, для которого отбираем порты
     * @param  string  $typePort - тип выбираемых портов
     */
    public function getPortsIntoList($deviceId, $typesPort = 'IN')
    {
        if ($deviceId) {
            $ports = $this->rep->getPortsByDeviceId($deviceId, $typesPort);
            $portsArray = [];

            foreach ($ports as $port) {
                if ($port->comment) {
                    $commentString = ' ('.$port->comment.')';
                } else {
                    $commentString = '';
                }

                $portsArray[$port->id] = $port->status.' ['.$port->num_port.'] '.
                    ($port->extensionModule ? ' EXT_SDA ('.$port->extensionModule->sda_port.')' : '').$commentString;
            }

            return $portsArray;
        } else {
            return [];
        }
    }

    private function getHPDevicesIntoList($deviceID, $hpType = 'switch')
    {
        $hpTypes = explode(',', $hpType);

        if ($deviceID) {
            foreach ($hpTypes as $type) {
                switch (trim($type)) {
                    case 'switch':
                        $HPdevices = $this->hiteproDevRep
                            ->getSwitchByDeviceId($deviceID);
                        break;

                    case 'socket':
                        $HPdevices = $this->hiteproDevRep
                            ->getSocketByDeviceId($deviceID);
                        break;

                    case 'temperature':
                        $HPdevices = $this->hiteproDevRep
                            ->getTermometrsByDeviceId($deviceID);
                        break;

                    case 'transmitter':
                        $HPdevices = $this->hiteproDevRep
                            ->getTransmittersByDeviceId($deviceID);
                        break;

                        // case 'dimmer':
                        //     $HPdevices = $this->hiteproDevRep
                        //         ->getInPortsByDeviceId($deviceID);
                        //     break;

                    default: $HPdevices = [];
                        break;
                }
            }
            $HPdevicesArray = [];

            foreach ($HPdevices as $HPdevice) {
                $HPdevicesArray[$HPdevice->id] = '['.$HPdevice->type.']'.' '.$HPdevice->name;
            }

            return $HPdevicesArray;
        } else {
            return [];
        }
    }

    /**
     * Получаем текущий контроллер и порт, на котором находится объект
     */
    public function getIdDeviceAndPortId($idObject)
    {
        if ($port = Port::where('object', $idObject)->first()) {
            $deviceId = $port->id_device;
            $portId = $port->id;
            $typePort = $port->status;
        } else {
            $deviceId = null;
            $portId = null;
            $typePort = null;
        }

        return [
            'id_device' => $deviceId,
            'id_port' => $portId,
            'type_port' => $typePort,
        ];
    }

    /**
     * Получаем текущий контроллер и порт, на котором находится объект
     */
    public function getIdControllerBySubdevice($subevice, $typeController)
    {
        if ($subevice) {
            if ($typeController == 'Hite-pro') {
                $controller = HiteproDev::where('id', $subevice)
                    ->first()
                    ->id_controller;
            }

            return $controller;
        } else {
            return null;
        }
    }

    /**
     * Получаем устройства для контроллера
     */
    public function getSubdevsForController($idController, $typeController, $typeDevice)
    {
        if ($typeController == 'Hite-pro') {
            return $this->getHPDevicesIntoList(
                $idController,
                $typeDevice
            );
        }
    }

    /**
     * Подготовка данных для изменения у порта
     *
     * @param  array  $data Массив с данными
     * @param  Port  $port - объект модели порта
     */
    private function preparePort(array $data, Port $port)
    {
        $port->id_device = (int) $data['id_controller'];
        $port->status = $data['status'];
        $port->comment = trim($data['comment']);
    }

    /**
     * Сохранение измененых данных на порту
     *
     * @param  array  $data - данные порта с формы
     * @return bool - резульат выполнения
     */
    public function store(array $data)
    {
        $port = Port::where('id', $data['id_port'])->first();

        if (! $port) {
            $result = false;
        }

        $this->preparePort($data, $port);

        DB::transaction(function () use (&$port, &$result) {
            $answer = ConfigMegaService::setPortType(
                $port->id_device,
                $port->num_port,
                $port->status
            );

            if ($answer === false) {
                throw new \Exception('Некорректный ответ от удаленного сервера');
            } else {
                $port->save();
                $result = true;
            }
        });

        return $result;
    }

    /**  Вывод методов для порта
     *
     */
    public function getMethodsByObject($idObject)
    {
        return Port::where('object', $idObject)->first();
    }

    /**
     * Возвращает массив с значениями текущего порта и устройства для выбранного объекта,
     * а также все устройства и порты для выбранного устройства
     *
     * @return array
     */
    public function getCurrentDevPort($idObject, $typesPorts = null)
    {
        $deviceAndPort = $this->getIdDeviceAndPortId($idObject);
        $idDevice = $deviceAndPort['id_device'];
        $idPort = $deviceAndPort['id_port'];

        if ($typesPorts == null) {
            $typePort = $deviceAndPort['type_port'];
        } else {
            $typePort = $typesPorts;
        }

        $hp_device = null;
        $hp_type = null;

        //Если не нашли в портах устройство, пробуем искать на hitepro
        if ($idDevice === null) {
            $controllerAndDevice = $this->getCurrentDevHitepro($idObject);
            $idDevice = $controllerAndDevice['id_device'];
            $hp_device = $controllerAndDevice['hp_device'];
            $hp_type = $controllerAndDevice['hp_type'];
        }

        $devices = $this->device_rep->getAllWithoutTypesToArray(['Hite-pro']);
        $ports = $this->getPortsIntoList($idDevice, $typePort);
        $hp_devices = $this->getHPDevicesIntoList($idDevice, $hp_type);

        if (! $devices || ! $ports) {
            $devices = $this->device_rep
                ->getAllWithoutTypesToArray(['Hite-pro']);
            $ports = [];
        }

        return [$idDevice, $idPort, $devices, $ports, $hp_device, $hp_devices];
    }

    /**
     * Получить текущие контроллер и порты для led ленты
     *
     * @param LedTape $ledTape
     * @return array
     */
    public function getCurrentDeviceAndPortsForLedTape(LedTape $ledTape): array
    {
        if ($ledTape->type == LedTape::TYPE_RGBW || $ledTape->type == LedTape::TYPE_RGB) {
            $ports = Port::where('object', $ledTape->id_object)->get();
            $deviceId = null;
            $portsIds = null;

            if ($ports->isNotEmpty()) {
                $deviceId = $ports->first()->id_device;
                $portsIds = [];
                foreach ($ports as $port) {
                    $portsIds[] = $port->id;
                }
            }

            return [$deviceId, $portsIds];
        } else {
            $port = Port::where('object', $ledTape->id_object)->first();
            $deviceId = null;
            $portsId = null;

            if ($port) {
                $deviceId = $port->id_device;
                $portsId = $port->id;
            }

            return [$deviceId, $portsId];
        }
    }

    /**
     * Возвращает id контроллера hite-pro и устройства к которому привязан объект
     */
    private function getCurrentDevHitepro($idObject)
    {
        if ($hitepro = HiteproDev::where('id_object', $idObject)->first()) {
            $deviceId = $hitepro->id_controller;
            $HPDevice = $hitepro->id;
            $HPType = $hitepro->type;
        } else {
            $deviceId = null;
            $HPDevice = null;
            $HPType = null;
        }

        return [
            'id_device' => $deviceId,
            'hp_device' => $HPDevice,
            'hp_type' => $HPType,
        ];
    }

    /**
     * Установка для порта дефолтных значений в БД (например, если объект ранее был на одном порту, а переносим на другой)
     */
    public static function removeObjectOnPorts($idObject)
    {
        Port::where('object', $idObject)
            ->update([
                'object' => null,
                'method' => null,
                'method_params' => null,
                'dc_method' => null,
                'dc_method_params' => null,
                'lc_method' => null,
                'lc_method_params' => null,
                'comment' => '',
            ]);

        HiteproDev::where('id_object', $idObject)
            ->update(['id_object' => null]);
    }

    /**
     * Настроить порт в БД в соответсвии с параметрами
     *
     * @param  null  $comment
     * @param  null  $method
     * @param  null  $method_params
     * @param  null  $dc_method
     * @param  null  $dc_method_params
     * @param  null  $lc_method
     * @param  null  $lc_method_params
     */
    public static function setObjectOnSelectedPort($idObject, $idPort, $status, $comment,
        $method = null, $method_params = null,
        $dc_method = null, $dc_method_params = null,
        $lc_method = null, $lc_method_params = null)
    {
        Port::where('id', $idPort)
            ->update([
                'object' => $idObject,
                'method' => $method,
                'method_params' => $method_params,
                'dc_method' => $dc_method,
                'dc_method_params' => $dc_method_params,
                'lc_method' => $lc_method,
                'lc_method_params' => $lc_method_params,
                'comment' => $comment,
                'status' => $status,
            ]);
    }

    /**
     * Добавить объект на устройство хитпро
     *
     * @param $idDevice
     */
    public static function setObjectOnHitePro($idObject, $idHPDevice)
    {
        HiteproDev::where('id', $idHPDevice)
            ->update(['id_object' => $idObject]);
    }
}

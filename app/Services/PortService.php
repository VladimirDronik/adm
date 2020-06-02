<?php

namespace App\Services;

use App\Models\Device;
use App\Models\Method;
use App\Models\Port;
use App\Models\Script;
use App\Repositories\PortRepository;
use Illuminate\Support\Facades\DB;

class PortService {

    const NONE = 'отсутствует';

    private $rep;
    private $object_service;

    public function __construct(PortRepository $rep, ObjectService $object_service)
    {
        $this->rep = $rep;
        $this->object_service = $object_service;
    }

    public function updateComment(array $data)
    {
        $comment = trim($data['comment']);

        if ($comment === 'Отсутствует') {
            $comment = '';
        }

        Port::where('id', $data['port_id'])
            ->where('id_device', $data['device_id'])->update([
                'comment' => $comment
            ]);
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

        $html = (String)view('ajax.actions',
            ['action' => $r->methodmode, 'port_id' => $r->port_id, 'object' => $eport->object]
            + compact('device','port', 'act', 'value'));

        return $html;
    }

    public function getViewData($r)
    {
        switch ($r->mode) {
            case 'device':
                $devices = Device::orderBy('description')->get();
                $title_action = 'Выбор контроллера';
                $html = (String)view('ajax.devices', compact('devices'));
                break;
            case 'port':
                $ports = $this->rep->getOutPortsByDeviceId((int)$r->device);
                $title_action = 'Выбор порта';
                $html = (String)view('ajax.ports', compact('ports'));
                break;
            case 'action':
                $title_action = 'Выбор действия';
                $html = (String)view('ajax.act');
                break;
            case 'script':
                $scripts = Script::orderBy('name')->get();
                $title_action = 'Выбор скрипта';
                $html = (String)view('ajax.scripts', compact('scripts'));
                break;
            case 'method' :
                $methods = Method::where('id_object', $r->object_id)->orderBy('name')->get();
                $title_action = 'Выбор метода объекта';
                $html = (String)view('ajax.methods', compact('methods'));
                break;
        }

        return compact('html', 'title_action');
    }

    public function storeMethod($r)
    {
        switch ($r->methodmode) {
            case 'easy':
                $this->rep->updateEasy($r->id_port, $r->device . ';' . $r->port . ':' . $r->act);
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
     * @param array $data
     * @return array
     * @throws \Exception
     */
    public function getPortMethods(array $data): array
    {
        $port = Port::where('id_device', $data['device_id'])->where('id', $data['port_id'])->first();

        if (!$port) {
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
                'type_img' => (string)view('objects.type_img', compact('object'))
            ];
        }

        $portData['methods'] = $this->getMethods($portData['object_id'], $portData['objects']);

        return $portData;
    }

    private function getMethods(int $object_id, array $objects): array
    {
        if ($object_id) {
            return $this->object_service->getMethodsByObjectId($object_id);
        }

        if (count($objects)) {
            return $this->object_service->getMethodsByObjectId($objects[0]['id']);
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
        }  elseif ($type === 'long' && $port->lcmethod) {
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

    /**
     * @param array $data
     */
    public function deletePortMethod(array $data)
    {
        $methodColumnName = $this->getMethodColumnName($data['type']);

        Port::where('id_device', $data['device_id'])->where('id', $data['port_id'])
            ->update([$methodColumnName => null]);
    }

    /**
     * Удаление всех методов для порта
     */
    public static function deleteAllMethodsForPort($idObject)
    {
        Port::where('object', $idObject)
            ->update(['method' => null, 'dc_method' => null, 'lc_method' => null,
                'method_params' => null, 'dc_method_params' => null, 'lc_method_params' => null]);
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
        return $this->object_service->getMethodsByObjectId($object_id);
    }

    public function updatePortMethod(array $data): array
    {
        $methodColumnName = $this->getMethodColumnName($data['type']);

        if ($data['params'] === '') {
            Port::where('id_device', $data['device_id'])->where('id', $data['port_id'])
                ->update([$methodColumnName => $data['method_id']]);
        } else {
            Port::where('id_device', $data['device_id'])->where('id', $data['port_id'])
                ->update([$methodColumnName => $data['method_id'], $methodColumnName.'_params' => $data['params']]);
        }

        $port = Port::find($data['port_id']);

        return $this->getPortMethod($port, $data['type']);
    }

    /**
     * Добавление портов для вывода в выпадающем списке
     *
     * @param int $deviceId - ИД устройства, для которого отбираем порты
     * @param string $typePort - тип выбираемых портов
    */
    public function getPortsIntoList($deviceId, $typePort = 'IN')
    {

        switch ($typePort) {

            case 'IN': $ports = $this->rep->getInPortsByDeviceId($deviceId);
                       break;

            case 'I2C': $ports = $this->rep->getI2CPortsByDeviceId($deviceId);
                break;


        }

        $portsArray = [];

            foreach ($ports AS $port) {

                if ($port->comment)
                    $commentString = ' (' . $port->comment . ')';
                else $commentString = '';

                $portsArray[$port->id] = $port->status . ' ' . $port->num_port . $commentString;
            }

            return $portsArray;

    }

    /**
     * Получаем текущий контроллер и порт, на котором находится объект
    */
    public function getIdDeviceAndPortId($idObject)
    {
        if($port = Port::where('object', $idObject)->first()) {
            $deviceId = $port->id_device;
            $portId = $port->id;
        } else {
            $deviceId = null;
            $portId = null;
        }

        return array('id_device' => $deviceId, 'id_port' => $portId);
    }

    /**
     * Подготовка данных для изменения у порта
     *
     * @param array $data Массив с данными
     * @param Port $port - объект модели порта
     */
    private function preparePort(array $data, Port $port)
    {
        $port->id_device = (int)$data['id_controller'];
        $port->status = $data['status'];
        $port->comment = trim($data['comment']);
    }



    /**
     * Подготовка команды для отправки на устройство
     *
     * @param Port $port - данные порта
     */
    private function prepareSendInDevice(Port $port)
    {
        $device = Device::where('id',$port->id_device)->first();

        switch ($port->status) {

            case 'IN': $paramString = "&pty=0";
                        break;

            case 'OUT': $paramString = "&pty=1";
                break;

            case '1WIRE': $paramString = "&pty=3&d=3";
                break;

            case '1W-BUS': $paramString = "&pty=3&m=0&misc=0.00&hst=0.00&ecmd=&eth=&d=5";
                break;

            case 'I2C': $paramString = "&pty=4&d=5";
                break;

            default: $paramString = "&pty=255&m=0&misc=0.00&hst=0.00&ecmd=&eth=&d=3";
                break;
        }

        return "http://{$device->ip_address}/sec/?pn={$port->num_port}$paramString";

    }

    /**
     * Сохранение измененых данных на порту
     *
     * @param array $data - данные порта с формы
     * @return bool - резульат выполнения
     */
    public function store(array $data)
    {

        $port = Port::where('id', $data['id_port'])->first();

        if (!$port) {
            $result = false;
        }

        $this->preparePort($data, $port);

        if(DeviceService::getStatus($port->id_device) === 1) {

            DB::transaction(function () use (&$port, $data, &$result) {

                $answer = file_get_contents($this->prepareSendInDevice($port));

                if ($answer === false) {
                    throw new \Exception('Некорректный ответ от удаленного сервера');
                } else {
                    $port->save();
                    $result = true;
                }

            });
        } else  throw new \Exception(': контроллер не доступен');

        return $result;
    }

    /**  Вывод методов для порта
     *
     */
    public function getMethodsByObject($idObject)
    {
       return Port::where('object', $idObject)->first();
    }



}
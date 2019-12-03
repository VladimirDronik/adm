<?php

namespace App\Services;

use App\Models\Device;
use App\Models\Method;
use App\Models\Port;
use App\Models\Script;
use App\Repositories\PortRepository;

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
        } elseif ($type === 'double' && $port->dcmethod) {
            $data['method_id'] = $port->dc_method;
            $data['object_id'] = $port->dcmethod->id_object;
            $data['method_name'] = $port->dcmethod->name;
            $data['object_name'] = optional($port->dcmethod->eobject)->name;
        }  elseif ($type === 'long' && $port->lcmethod) {
            $data['method_id'] = $port->lc_method;
            $data['object_id'] = $port->lcmethod->id_object;
            $data['method_name'] = $port->lcmethod->name;
            $data['object_name'] = optional($port->lcmethod->eobject)->name;
        } else {
            $data['method_id'] = 0;
            $data['object_id'] = 0;
        }

        return $data;
    }

    public function deletePortMethod(array $data)
    {
        $methodColumnName = $this->getMethodColumnName($data['type']);

        Port::where('id_device', $data['device_id'])->where('id', $data['port_id'])
            ->update([$methodColumnName => null]);
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
}
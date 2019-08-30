<?php

namespace App\Services;

use App\Models\Device;
use App\Models\Method;
use App\Models\Port;
use App\Models\Script;
use App\Repositories\PortRepository;

class PortService {

    private $rep;

    public function __construct(PortRepository $rep)
    {
        $this->rep = $rep;
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
    {\Log::alert($r->all());

        $device = $port = $act = '';

        $eport = Port::findOrFail($r->port_id);

        // Выбираемый метод равен существующему методу порта
        $value = $r->cur_method === $r->methodmode ? $r->value : 'отсутствует';

        if ($r->methodmode === 'easy') {
            // Разбираем значение для простого действия
            if ($value !== 'отсутствует') {
                $easy = explode(';', $r->value);
                $easy1 = explode(':', $easy[1]);
                $device = $easy[0];
                $port = $easy1[0];
                $act = $easy1[1];
            } else {
                $device = $port = $act = 'отсутствует';
            }
        } elseif ($r->methodmode === 'method') {
            if ($r->cur_method === $r->methodmode) {
                $value = empty($eport->method) ? 'отсутствует' : optional($eport->emethod)->name;
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
                $title_action = 'Выбор устройства';
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

        return ['html' => $html, 'title_action' => $title_action];
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
}
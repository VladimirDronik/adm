<?php

namespace App\Http\Controllers\Ajax;

use App\Services\DeviceService;
use App\Services\PortService;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Device;
use App\Models\Port;
use App\Models\Script;

class PortController extends Controller
{
    private $device_service;
    private $port_service;

    public function __construct(DeviceService $device_service, PortService $port_service)
    {
        $this->device_service = $device_service;
        $this->port_service = $port_service;
    }

    public function updateComment(Request $r)
    {
        abort_if(!ajaxHas($r, ['device_id', 'port_id', 'comment']), 400);

        $this->port_service->updateComment($r->all());

        return response()->json(['success' => true, 'html' => trim($r->comment)]);
    }

    // todo

    public function save_name_port()
    {
        $nameport = trim($_POST['nameport']);

        Port::save_name_port($_POST['id_port'], $nameport);

        return response()->json(array('success' => true, 'html' => $nameport));
    }

    /**
     * Загрузка метода в модальное окно
     **/
    public function load_method()
    {
        $method = $_POST['methodmode'];
        $device = '';
        $port = '';
        $act = '';
        $value = '';

        // Если выбираемый метод равен уже существующему методу порта
        if ($_POST['cur_method'] == $method)
            $value = $_POST['value'];
        else
            $value = 'отсутствует';

        if ($method == 'easy') {

            // Разбираем значение для простого действия
            if ($value != 'отсутствует') {

                $easy = explode(';', $_POST['value']);
                $easy1 = explode(':', $easy[1]);

                $device = $easy[0];
                $port = $easy1[0];
                $act = $easy1[1];

            } else {

                $device = 'отсутствует';
                $port = 'отсутствует';
                $act = 'отсутствует';

            }

        }

        $object = Port::select_object($_POST['port_id']);

        $returnHTML = (String)view('ajax.actions', ['action' => $method, 'device' => $device,
            'port' => $port, 'act' => $act, 'value' => $value, 'port_id' => $_POST['port_id'], 'object' => $object]);

        return response()->json(array('success' => true, 'html' => $returnHTML));
    }


    /**
     * Загрузка данных для выбора действия порта
     */
    static public function load_data()
    {
        switch ($_POST['mode']) {

            case 'device':
                $return = Device::all();
                $title_action = 'Выбор устройства';
                $returnHTML = (String)view('ajax.devices', ['devices' => $return]);
                break;
            case 'port':

                $return = Port::select_ports($_POST['device']);
                $title_action = 'Выбор порта';
                $returnHTML = (String)view('ajax.ports', ['ports' => $return]);
                break;

            case 'action':

                $title_action = 'Выбор действия';
                $returnHTML = (String)view('ajax.act');
                break;

            case 'script':

                $return = Script::all();
                $title_action = 'Выбор скрипта';
                $returnHTML = (String)view('ajax.scripts', ['scripts' => $return]);
                break;


        }


        return response()->json(array('success' => true, 'html' => $returnHTML, 'title_action' => $title_action));

    }

    /**
     * Сохранение метода для порта
     */
    public function save_method()
    {

        switch ($_POST['methodmode']) {

            case 'easy':
                $value = $_POST['device'] . ';' . $_POST['port'] . ':' . $_POST['act'];
                Port::add_method($_POST['id_port'], 'easy', $value);
                break;

            case 'method':
                $value = $_POST['id_object'];
                Port::add_method($_POST['id_port'], 'method', $value);
                break;

            case 'script':
                $value = $_POST['id_script'];
                Port::add_method($_POST['id_port'], 'script', $value);
                break;

            case 'none':
                $value = null;
                Port::add_method($_POST['id_port'], 'none', $value);
                break;
        }
    }
}

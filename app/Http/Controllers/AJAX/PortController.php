<?php

namespace App\Http\Controllers\AJAX;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Device;
use App\Port;
use App\Script;

class PortController extends Controller
{
    //

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
        if($_POST['cur_method'] == $method)
            $value = $_POST['value'];
        else
            $value = 'отсутствует';



        if ($method == 'easy') {

                // Разбираем значение для простого действия
                if ($value != 'отсутствует'){

                    $easy = explode(';', $_POST['value']);
                    $easy1 = explode(':', $easy[1]);

                    $device = $easy[0];
                    $port = $easy1[0];
                    $act = $easy1[1];

                }
                else{

                    $device = 'отсутствует';
                    $port = 'отсутствует';
                    $act = 'отсутствует';

                }

        }

        $object = Port::select_object($_POST['port_id']);

        $returnHTML = (String) view('AJAX.actions', ['action' => $method, 'device' => $device,
             'port' => $port, 'act' => $act, 'value' => $value, 'port_id' => $_POST['port_id'], 'object' => $object]);

        return response()->json(array('success' => true, 'html'=>$returnHTML));
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
                $returnHTML = (String) view('AJAX.devices', ['devices' => $return ]);
                break;

            case 'port':

                $return = Port::select_ports($_POST['device']);
                $title_action = 'Выбор порта';
                $returnHTML = (String) view('AJAX.ports', ['ports' => $return ]);
                break;

            case 'action':

                $title_action = 'Выбор действия';
                $returnHTML = (String) view('AJAX.act');
                break;

            case 'script':

                $return = Script::all();
                $title_action = 'Выбор скрипта';
                $returnHTML = (String) view('AJAX.scripts', ['scripts' => $return ]);
                break;


        }



        return response()->json(array('success' => true, 'html'=>$returnHTML, 'title_action' => $title_action));

    }

    /**
     * Сохранение метода для порта
     */
    public function save_method()
    {

        switch ($_POST['methodmode']) {

            case 'easy':
                $value = $_POST['device'] + ';' + $_POST['port'] + ':' + $_POST['act'];
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



    /**
     *  Сохранение названия порта
     */
    public function save_name_port()
    {

            $nameport = $_POST['nameport'];

        Port::save_name_port($_POST['id_port'], $nameport);

        return response()->json(array('success' => true, 'html'=>$nameport));
    }


    /**
     * Добавление портов для нового устройства
     *
     */
    public function add_ports()
    {

        port::addports($_POST['id_device'], $_POST['num_port'], $_POST['status']);

    }
}

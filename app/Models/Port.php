<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Port
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Port newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Port newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Port query()
 * @mixin \Eloquent
 * @property int $id
 * @property int $id_device id девайса из таблицы devices
 * @property int $num_port номер порта меги
 * @property string $status статус порта in, out, ds, nc, 1w
 * @property string|null $easy выполнение простого действия (например переключение порта). В значениях указываем id порта из этой таблицы  !!!
 * @property int|null $object id объекта
 * @property int|null $method id метода объекта
 * @property int|null $script выполнение скрипта из таблицы скриптов
 * @property int $longclick Разрешаем долгое нажатие
 * @property int $doubleclick Разрешаем двойное нажатие
 * @property string $comment комментарий к порту
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Port whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Port whereDoubleclick($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Port whereEasy($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Port whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Port whereIdDevice($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Port whereLongclick($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Port whereMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Port whereNumPort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Port whereObject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Port whereScript($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Port whereStatus($value)
 */
class Port extends Model
{
    public $timestamps = false;

    /**
     * Добавление объекта к порту
     *
     * @param int $id_port "id порта, к которому добавляем объект"
     * @param int $id_object "id объекта, который добавляем к порту"
     *
     * @return void
     */
    static public function add_object($id_port, $id_object)
    {

    if($id_object=='')
        $id_object = null;

    Port::where('id', $id_port)->update(['object' => $id_object]);


    }


    /**
     * Вывод всех портов для выбранного устройства
     *
     * @param int $device "id выбранного устройства"
     *
     */
    static public function select_ports($device)
    {
        $ports = Port::where('id_device',$device)->where('status', 'out')->get();

        return $ports;

    }


    /**
     * Добавление метода, скрипта или простого действия к порту
     *
     * @param int $id_port "id порта, у которого будем менять данные"
     * @param string $method "изменяемое свойство easy, script, none или object&method"
     * @param string $value "значение изменяемого свойства"
     */
    static public function add_method($id_port, $method, $value1, $value2 = null)
    {
        switch ($method){

            case 'easy':
                Port::where('id', $id_port)->update(['easy' => $value1, 'object' => null,
                    'method' => null, 'script' => null]);
                break;

            case 'method':
                Port::where('id', $id_port)->update(['object' => $value1, 'method' => $value2,
                    'easy' => null, 'script' => null]);
                break;

            case 'script':
                Port::where('id', $id_port)->update(['script' => $value1, 'object' => null,
                    'method' => null, 'easy' => null]);
                break;

            case 'none':
                Port::where('id', $id_port)->update(['script' => null, 'object' => null,
                    'method' => null, 'easy' => null]);
                break;

        }


    }




    /**
     * Выбор объекта у порта
     *
     * @param int $id_port
     *
     */
    static public function select_object($id_port)
    {

        $port = Port::where('id',$id_port)->firstOrFail();

        return $port->object;
    }



    /**
     * Сохнаение названия порта
     *
     * @param int $id_port
     * @param string $name_port
     *
     * @return void
     */
    static public function save_name_port($id_port, $name_port)
    {
        Port::where('id', $id_port)->update(['comment' => $name_port]);
    }


    /**
     * Добавление новых портов для устройства
     *
     * @param int $id_device
     * @param int $num_port
     * @param string $status
     *
     */
    static public function addports($id_device, $num_port, $status)
    {
        Port::insert(['id_device' => $id_device, 'num_port' => $num_port, 'status' => $status, 'comment' => '']);

    }

}

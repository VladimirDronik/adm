<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

<<<<<<< HEAD
class Rooms extends Model
{
    public $timestamps = false;

    public $idRoom;
    public $nameRoom = 'new_room';
    public $imageRoom = 'noimage.png';
    public $colorRoom = 'blue';

    public function __construct($idRoom = null)
    {
        $this->idRoom = $idRoom;
    }

    /**
     * Загрузка всех помещений из БД
     *
     * @return static
     */
    public static function getAllRooms()
    {
        $rooms = self::select('*')->where('id','>','0')->orderBy('sort', 'ASC')->get();
        return $rooms;
    }


    /**
     * Вывод изображений для всех помеещний
     *
     * @return array;
     *
     */
    public static function getAllImages()
    {
        $aFiles = array_diff(scandir('images/rooms'), array('..', '.'));

        return $aFiles;
=======
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
 * @property-read \App\Models\Device $device
 * @property-read \App\Models\HomeObject|null $eobject
 * @property-read \App\Models\Script|null $escript
 * @property-read \App\Models\Method|null $emethod
 * @property-read mixed $is_empty_comment
 */
class Port extends Model
{
    public $timestamps = false;

    /* attributes */

    public function getIsEmptyCommentAttribute()
    {
        return empty($this->comment) || mb_strtolower($this->comment, 'UTF-8') === 'отсутствует'
            ||  mb_strtolower($this->comment, 'UTF-8') === 'без названия';
>>>>>>> 60de956102a593f31582326fc280ce710437f7e7
    }

    /* relations */

<<<<<<< HEAD


    /**
     * Вывод всех цветовых схем
     *
     * @return static;
     */
    public static function getAllColors()
    {

        $colors = Colors::all();
        return $colors;
    }


    /**
     * Добавление нового помещения в БД
     *
     * @param string name
     * @param string image
     * @param string color
     */
    public function addRoom()
    {

        //Запрашиваем в БД последнюю цифру в списке сортировки
        $sort = self::max('sort');
        $sort++;

        $lastid =  Rooms::insertGetId(['name' => $this->nameRoom, 'image' => $this->imageRoom,
            'style' => $this->colorRoom, 'sort' => $sort]);
    }


    /**
     * Удаление помещения из БД
     *
     * @param int id
     */
    public static function deleteRoom($id)
    {

        self::where('id',$id)->delete();

    }


    /**
     * Сортировка строк с помещениями
     *
     * @param int id - id текущей записи
     * @param int sort - сортировка текущей записи
     * @param string direction - направление перемещения
     *
     */
    public static function sort($id, $sort, $direction)
    {
        if ($direction == 'UP')
            $newSort = $sort-1;
        else
            $newSort = $sort+1;


        $maxSort = self::max('sort');

        //Если двигаться еще есть куда
        if (($newSort != 0) && ($newSort <= $maxSort)) {
            //В БД меняем сортировку у следующей по сорту записи на -1
            self::where('sort', $newSort)->update(['sort' => $sort]);

            //У текущей записи меняем сорт на +1
            self::where('id', $id)->update(['sort' => $newSort]);
        }

    }


    /**
     * Сохраниение нового названия помещения
     */
    public function saveName()
    {
        self::where('id', $this->idRoom)->update(['name' => $this->nameRoom]);
    }


    /**
     * Изменение изображения для помещения
     */
    public function saveImage()
    {
        self::where('id', $this->idRoom)->update(['image' => $this->imageRoom]);
    }


    /**
     * Изменение цвета для помещения
     */
    public function saveColor()
    {
        self::where('id', $this->idRoom)->update(['style' => $this->colorRoom]);
    }

    /**
     * Наименование помещения по его id
     */
    public static function nameRoomFromId($id)
    {
        return Rooms::find($id)->name;
=======
    public function device()
    {
        return $this->belongsTo(Device::class, 'id_device', 'id');
    }

    public function eobject()
    {
        return $this->belongsTo(HomeObject::class, 'object', 'id');
    }

    public function emethod()
    {
        return $this->belongsTo(Method::class, 'method', 'id');
>>>>>>> 60de956102a593f31582326fc280ce710437f7e7
    }
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
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
    }




    /**
     * Вывод всех цветовых схем
     *
     * @return static;
     */
    public static function getAllColors()
    {

        $colors = Color::all();
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


}

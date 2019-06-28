<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Rooms extends Model
{
    public $timestamps = false;

    public $nameRoom = 'new_room';
    public $image = 'noimage.png';
    public $color = 'blue';

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

        $lastid =  Rooms::insertGetId(['name' => $this->nameRoom, 'image' => $this->image,
            'style' => $this->color, 'sort' => $sort]);
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


        //Если двигаться вверх еще есть куда
        if ($newSort != 0) {
            //В БД меняем сортировку у следующей по сорту записи на -1
            self::where('sort', $newSort)->update(['sort' => $sort]);

            //У текущей записи меняем сорт на +1
            self::where('id', $id)->update(['sort' => $newSort]);
        }

    }
}

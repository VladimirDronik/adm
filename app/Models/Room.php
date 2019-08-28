<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Room
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Room newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Room newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Room query()
 * @mixin \Eloquent
 * @property int $id
 * @property string $name
 * @property string $image
 * @property string $style
 * @property int $sort
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Room whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Room whereImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Room whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Room whereSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Room whereStyle($value)
 */
class Room extends Model
{
    const COMMON_NAME = 'Общие';

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
     * Наименование помещения по его id
     */
    public static function nameRoomFromId($id)
    {
        return Room::find($id)->name;
    }

    public static function getAllRooms()
    {
        $rooms = self::select('*')->where('id','>','0')->orderBy('sort', 'ASC')->get();
        return $rooms;
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

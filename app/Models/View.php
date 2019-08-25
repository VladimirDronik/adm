<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\View
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\View newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\View newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\View query()
 * @mixin \Eloquent
 */
class View extends Model
{
    protected $table = 'view_items';
    public $timestamps = false;

    const TYPE_SWITCH = 1;
    const TYPE_BTN = 2;
    const TYPE_TEMP = 3;
    const TYPE_INFOPANEL = 4;

    protected $casts = ['active' => 'boolean'];

    public static function getFullTypeIds()
    {
        return [
            self::TYPE_SWITCH => 'Переключатель',
            self::TYPE_BTN => 'Кнопка',
            self::TYPE_TEMP => 'Термометр/Гигрометр',
            self::TYPE_INFOPANEL => 'Инфопанель',
        ];
    }

    public static function getTypeIds()
    {
        return array_keys(self::getFullTypeIds());
    }

    public static function getTypeById($id) {
        return self::getFullTypeIds()[$id] ?? '';
    }

    /**
     * Вывод отображений с фильтром по номеру помещения
     *
     * @param $idRoom id помещения для вывода отображений в этом помещении
     */
    public static function getViews($idRoom)
    {
        return View::where('room','=',$idRoom)->orderBy('sort')->get();
    }
}

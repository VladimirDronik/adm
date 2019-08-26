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
    protected $guarded = ['id'];

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

    // todo refactoring
    /**
     * Вывод отображений с фильтром по номеру помещения
     *
     * @param $idRoom id помещения для вывода отображений в этом помещении
     */
    public static function getViews($idRoom)
    {
        return View::where('room','=',$idRoom)->orderBy('sort')->get();
    }

    /* attributes */

    public function getPartOfTitle($prefix = 'on', $part = 'top')
    {
        try {
            if (empty($this->{$prefix.'_title'})) {
                return '';
            }

            return explode('<br>', $this->{$prefix.'_title'})[$part === 'top' ? 0 : 1] ?? '';

        } catch (\Throwable $e) {
            \Log::alert('Некорректные данные в отображении № '.$this->id.' в поле '.$prefix.'_title');
        }

        return '';
    }

    public function getOnTitleTopAttribute()
    {
        return $this->getPartOfTitle('on','top');
    }

    public function getOnTitleBottomAttribute()
    {
        return $this->getPartOfTitle('on','bottom');
    }

    public function getOffTitleTopAttribute()
    {
        return $this->getPartOfTitle('off','top');
    }

    public function getOffTitleBottomAttribute()
    {
        return $this->getPartOfTitle('off','bottom');
    }

    public function getIsActiveAttribute()
    {
        return $this->active ? 'Да' : 'Нет';
    }

    public function getShortOnTitleAttribute()
    {
        return str_replace('<br>','|',$this->on_title);
    }

    public function getShortOffTitleAttribute()
    {
        return str_replace('<br>','|',$this->off_title);
    }

    /* relations */

    public function escene()
    {
        return $this->belongsTo(Scene::class, 'scene', 'id');
    }

    public function eroom()
    {
        return $this->belongsTo(Room::class, 'room', 'id');
    }
}

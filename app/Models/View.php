<?php

namespace App\Models;

use App\Services\ImageService;
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

    const TYPE_ITEM = 'i';
    const TYPE_TEMP = 't';

    const TYPE_NAME_SWITCH = 'switch';
    const TYPE_NAME_BUTTON = 'button';
    const TYPE_NAME_TEMP = 'temp';
    const TYPE_NAME_HUMIDITY = 'humidity';
    const TYPE_NAME_INFO = 'info';

    protected $casts = ['active' => 'boolean'];
    protected $guarded = ['id'];

    public static function getFullTypeNameIds()
    {
        return [
            self::TYPE_NAME_SWITCH => 'Переключатель',
            self::TYPE_NAME_BUTTON => 'Кнопка',
            self::TYPE_NAME_TEMP => 'Термометр',
            self::TYPE_NAME_HUMIDITY => 'Гигрометр',
            self::TYPE_NAME_INFO => 'Инфопанель',
        ];
    }

    public static function getTypeNameIds()
    {
        return array_keys(self::getFullTypeNameIds());
    }

    public static function getTypeNameById($id) {
        return self::getFullTypeNameIds()[$id] ?? '';
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

    public function getRusTypeNameAttribute()
    {
        return self::getTypeNameById($this->type_name);
    }

    public function getRoomNameAttribute()
    {
        if ($this->room !== 0) {
            return optional($this->eroom)->name;
        }

        return Room::COMMON_NAME;
    }

    public function getImagePath(string $prefix)
    {
        if (empty($this->{$prefix.'_image'})) {
            return ImageService::NO_IMAGE_PATH;
        }

        return ImageService::VIEW_PATH.'/'.$this->{$prefix.'_image'};
    }

    public function getOnImagePathAttribute()
    {
        return $this->getImagePath('on');
    }

    public function getOffImagePathAttribute()
    {
        return $this->getImagePath('off');
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

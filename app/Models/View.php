<?php

namespace App\Models;

use App\Services\ImageService;
use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\View
 *
 * @property int $id
 * @property string $type тип элемента: button, switch, temp, humidity, info
 * @property string $description описание элемента на русском языке
 * @property string $status
 * @property int|null $id_object id объекта из таблицы объектов
 * @property int|null $id_method метод объекта из таблицы методов
 * @property string $icon
 * @property string|null $title
 * @property int|null $position_left
 * @property int|null $position_top
 * @property int|null $room
 * @property int|null $scene
 * @property int $sort
 * @property bool $active
 * @property-read \App\Models\Method|null $emethod
 * @property-read \App\Models\HomeObject|null $eobject
 * @property-read \App\Models\Room|null $eroom
 * @property-read \App\Models\Scene|null $escene
 * @property-read mixed $icon_path
 * @property-read mixed $is_active
 * @property-read mixed $method_name
 * @property-read mixed $object_name
 * @property-read mixed $room_name
 * @property-read mixed $rus_type
 * @property-read mixed $short_title
 * @property-read mixed $title_bottom
 * @property-read mixed $title_top
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\View newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\View newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\View query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\View whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\View whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\View whereIcon($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\View whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\View whereIdMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\View whereIdObject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\View wherePositionLeft($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\View wherePositionTop($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\View whereRoom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\View whereScene($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\View whereSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\View whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\View whereTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\View whereType($value)
 * @mixin \Eloquent
 * @property int|null $room_group
 * @property string|null $id_method_params
 * @property-read mixed $icon_image
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\View whereIdMethodParams($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\View whereRoomGroup($value)
 * @property int|null $on_method метод объекта из таблицы методов
 * @property int|null $off_method
 * @property string|null $off_method_params
 * @property-read mixed $is_switch
 * @property-read mixed $off_method_name
 * @property-read \App\Models\Method|null $offmethod
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\View whereOffMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\View whereOffMethodParams($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\View whereOnMethod($value)
 */
class View extends Model
{
    protected $table = 'view_items';
    public $timestamps = false;

    const TYPE_SWITCH = 'switch';
    const TYPE_BUTTON = 'button';
    const TYPE_TEMP = 'temp';
    const TYPE_HUMIDITY = 'humidity';
    const TYPE_INFO = 'info';

    protected $casts = ['active' => 'boolean'];
    protected $guarded = ['id'];

    public static function getFullTypeIds()
    {
        return [
            self::TYPE_SWITCH => 'Переключатель',
            self::TYPE_BUTTON => 'Кнопка',
            self::TYPE_TEMP => 'Термометр',
            self::TYPE_HUMIDITY => 'Гигрометр',
            self::TYPE_INFO => 'Инфопанель',
        ];
    }

    public static function getTypeIds()
    {
        return array_keys(self::getFullTypeIds());
    }

    public static function getTypeById($id) {
        return self::getFullTypeIds()[$id] ?? '';
    }

    /* attributes */

    public function getPartOfTitle($part = 'top')
    {
        try {
            if (empty($this->title)) {
                return '';
            }

            return explode('<br>', $this->title)[$part === 'top' ? 0 : 1] ?? '';

        } catch (\Throwable $e) {
            \Log::alert('Некорректные данные в отображении № '.$this->id.' в поле title');
        }

        return '';
    }

    public function getTitleTopAttribute()
    {
        return $this->getPartOfTitle('top');
    }

    public function getTitleBottomAttribute()
    {
        return $this->getPartOfTitle('bottom');
    }

    public function getIconImageAttribute()
    {
        return $this->icon;
    }

    public function getIsActiveAttribute()
    {
        return $this->active ? 'Да' : 'Нет';
    }

    public function getIsSwitchAttribute()
    {
        return $this->type === self::TYPE_SWITCH;
    }

    public function getShortTitleAttribute()
    {
        return str_replace('<br>',' | ', $this->title);
    }

    public function getRusTypeAttribute()
    {
        return self::getTypeById($this->type);
    }

    public function getRoomNameAttribute()
    {
        if (is_null($this->room)) {
            return Room::COMMON_NAME;
        }

        if ($this->room) {
            return optional($this->eroom)->name;
        }

        return 'Не указано';
    }

    public function getMethodNameAttribute()
    {
        return optional($this->emethod)->name;
    }

    public function getOffMethodNameAttribute()
    {
        return optional($this->offmethod)->name;
    }

    public function getObjectNameAttribute()
    {
        return optional($this->eobject)->name;
    }

    public function getIconPathAttribute()
    {
        if (empty($this->icon) || $this->icon === 'noimage') {
            return ImageService::NO_IMAGE_PATH;
        }

        return ImageService::VIEW_PATH.'/'.$this->icon . '.svg';
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

    public function eobject()
    {
        return $this->belongsTo(HomeObject::class, 'id_object', 'id');
    }

    public function emethod()
    {
        return $this->belongsTo(Method::class, 'on_method', 'id');
    }

    public function offmethod()
    {
        return $this->belongsTo(Method::class, 'off_method', 'id');
    }
}

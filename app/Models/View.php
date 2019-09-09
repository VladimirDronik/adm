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
 * @property int $id
 * @property string $type i=item, s=setting, e=event,  t=temp
 * @property string $type_name тип элемента: button, switch, temp, humidity, info
 * @property string $name Название элемента на русском языке
 * @property string $description описание элемнта на русском языке
 * @property string $status
 * @property string $on_image
 * @property string|null $off_image
 * @property float|null $value
 * @property string|null $on_title
 * @property string|null $off_title
 * @property string $items
 * @property string $date
 * @property int|null $position_left
 * @property int|null $position_top
 * @property int|null $room
 * @property int|null $scene
 * @property int $sort
 * @property bool $active
 * @property-read \App\Models\Room|null $eroom
 * @property-read \App\Models\Scene|null $escene
 * @property-read mixed $is_active
 * @property-read mixed $off_image_path
 * @property-read mixed $off_title_bottom
 * @property-read mixed $off_title_top
 * @property-read mixed $on_image_path
 * @property-read mixed $on_title_bottom
 * @property-read mixed $on_title_top
 * @property-read mixed $room_name
 * @property-read mixed $rus_type_name
 * @property-read mixed $short_off_title
 * @property-read mixed $short_on_title
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\View whereActive($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\View whereDate($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\View whereDescription($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\View whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\View whereItems($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\View whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\View whereOffImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\View whereOffTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\View whereOnImage($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\View whereOnTitle($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\View wherePositionLeft($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\View wherePositionTop($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\View whereRoom($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\View whereScene($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\View whereSort($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\View whereStatus($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\View whereType($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\View whereTypeName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\View whereValue($value)
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
        return str_replace('<br>',' | ',$this->on_title);
    }

    public function getShortOffTitleAttribute()
    {
        return str_replace('<br>',' | ',$this->off_title);
    }

    public function getRusTypeNameAttribute()
    {
        return self::getTypeNameById($this->type_name);
    }

    public function getRoomNameAttribute()
    {
        if (is_null($this->room)) {
            return 'Не указано';
        }

        if ($this->room !== 0) {
            return optional($this->eroom)->name;
        }

        return Room::COMMON_NAME;
    }

    public function getMethodNameAttribute()
    {
        return optional($this->emethod)->name;
    }

    public function getObjectNameAttribute()
    {
        return optional($this->eobject)->name;
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

    public function eobject()
    {
        return $this->belongsTo(HomeObject::class, 'id_object', 'id');
    }

    public function emethod()
    {
        return $this->belongsTo(Method::class, 'id_method', 'id');
    }
}

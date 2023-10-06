<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Port
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Port newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Port newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Port query()
 *
 * @mixin \Eloquent
 *
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
 *
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
 *
 * @property-read \App\Models\Device $device
 * @property-read \App\Models\HomeObject|null $eobject
 * @property-read \App\Models\Script|null $escript
 * @property-read \App\Models\Method|null $emethod
 * @property-read mixed $is_empty_comment
 * @property int|null $dc_method id метода при двойном нажатии
 * @property int|null $lc_method id метода при длительном нажатии
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Port whereDcMethod($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Port whereLcMethod($value)
 *
 * @property-read \App\Models\Method|null $dcmethod
 * @property-read \App\Models\Method|null $lcmethod
 * @property string|null $method_params
 * @property string|null $dc_method_params
 * @property string|null $lc_method_params
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Port whereDcMethodParams($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Port whereLcMethodParams($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Port whereMethodParams($value)
 *
 * @property string $type
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Port whereType($value)
 */
class Port extends Model
{
    public $timestamps = false;

    protected $guarded = ['id'];

    /* attributes */

    public function getIsEmptyCommentAttribute()
    {
        return empty($this->comment) || mb_strtolower($this->comment, 'UTF-8') === 'отсутствует'
            || mb_strtolower($this->comment, 'UTF-8') === 'без названия';
    }

    /* relations */

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
    }

    public function dcmethod()
    {
        return $this->belongsTo(Method::class, 'dc_method', 'id');
    }

    public function lcmethod()
    {
        return $this->belongsTo(Method::class, 'lc_method', 'id');
    }

    public function extensionModule()
    {
        return $this->belongsTo(ExtensionModule::class);
    }
}

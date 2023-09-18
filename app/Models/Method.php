<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Method
 *
 * @property int $id
 * @property int $id_object
 * @property string $name Название метода объекта
 * @property int|null $script id скрипта из таблицы скриптов
 * @property string $comment
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Method newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Method newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Method query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Method whereComment($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Method whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Method whereIdObject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Method whereName($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Method whereScript($value)
 *
 * @mixin \Eloquent
 *
 * @property string|null $easy выполнение простого действия (например переключение порта). В значениях указываем номер порта устройства
 * @property-read \App\Models\HomeObject $eobject
 * @property-read \App\Models\Script|null $escript
 * @property-read mixed $action
 * @property-read mixed $device_id
 * @property-read mixed $port
 * @property-read mixed $type
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Method whereEasy($value)
 *
 * @property int $is_system
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Method whereIsSystem($value)
 *
 * @property string|null $params Если null, то метод без параметров, иначе названия параметров через символ ;
 * @property-read mixed $is_need_param
 * @property-read mixed $is_need_params
 *
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Method whereParams($value)
 */
class Method extends Model
{
    public $timestamps = false;

    public function getTypeAttribute()
    {
        if (! empty($this->script)) {
            return 'script';
        }

        if (! empty($this->easy)) {
            return 'easy';
        }

        return 'none';
    }

    private function getEasySecondPart($index)
    {
        $ar = explode(';', $this->easy);

        return explode(':', $ar[1] ?? 'отсутствует')[$index] ?? 'отсутствует';
    }

    public function getPortAttribute()
    {
        if ($this->type === 'easy') {
            return $this->getEasySecondPart(0);
        }

        return 'отсутствует';
    }

    public function getActionAttribute()
    {
        if ($this->type === 'easy') {
            return $this->getEasySecondPart(1);
        }

        return 'отсутствует';
    }

    public function getDeviceIdAttribute()
    {
        if ($this->type === 'easy') {
            return explode(';', $this->easy)[0] ?? 'отсутствует';
        }

        return 'отсутствует';
    }

    public function getIsNeedParamAttribute()
    {
        return ! is_null($this->params);
    }

    public function getIsNeedParamsAttribute()
    {
        return $this->is_need_param;
    }

    /* relations */

    public function escript()
    {
        return $this->belongsTo(Script::class, 'script', 'id');
    }

    public function eobject()
    {
        return $this->belongsTo(HomeObject::class, 'id_object', 'id');
    }
}

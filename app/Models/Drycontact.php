<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * App\Models\Drycontact
 *
 * @property int $id
 * @property int|null $id_object id датчика из таблицы объектов
 * @property string $name
 * @property-read \App\Models\HomeObject|null $object
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Drycontact newModelQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Drycontact newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Drycontact query()
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Drycontact whereId($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Drycontact whereIdObject($value)
 * @method static \Illuminate\Database\Eloquent\Builder|\App\Models\Drycontact whereName($value)
 * @mixin \Eloquent
 * @property-read \App\Models\Method|null $emethod_off
 * @property-read \App\Models\Method|null $emethod_on
 */
class Drycontact extends Model
{
    protected $table = 'drycontacts';
    public $timestamps = false;
    protected $guarded = ['id'];

    public static function getEvents()
    {
        return [
            'onConnect' => 'Замыкание',
            'onUnconnect' => 'Размыкание',
        ];
    }

    /**
     * Получение доступных свойств объекта.
     * Формат: 'название_свойтсва' => ['Описание на русском', 'доступно для чтения', 'доступно для записи']
     * @return array
     */
    public static function getProperties()
    {
        return [
            'status' => ['Текущее состояние контакта', true, true],
        ];
    }

    public function object()
    {
        return $this->belongsTo(HomeObject::class, 'id_object', 'id');
    }

    public function eobject()
    {
        return $this->belongsTo(HomeObject::class, 'object', 'id');
    }

    public function iobject()
    {
        return $this->belongsTo(HomeObject::class, 'id_object', 'id');
    }

    public function emethod_on()
    {
        return $this->belongsTo(Method::class, 'method_on', 'id');
    }

    public function emethod_off()
    {
        return $this->belongsTo(Method::class, 'method_off', 'id');
    }
}

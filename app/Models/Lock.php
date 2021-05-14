<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lock extends Model
{
    const TYPE_CURTAIN = '';
    const TYPE_JALOUSIE = 'jalousie';
    const TYPE_SHUTTERS = 'shutters';


    protected $table = 'curtains';
    public $timestamps = false;
    protected $guarded = ['id'];

    public static function getTypes(bool $is_full = false)
    {
        $types = [
            self::TYPE_CURTAIN => 'Шторы',
            self::TYPE_JALOUSIE => 'Жалюзи',
            self::TYPE_SHUTTERS => 'Ставни'
        ];

        return $is_full ? $types : array_keys($types);
    }



    /**
     * Получение доступных событий для объекта
     * @return array
     */
    public static function getEvents()
    {
        return [
            'onOpen' => 'При отрытии',
            'onClose' => 'При закрытии',
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
            'status' => ['Статус, open/close', true, true],
        ];
    }

    public function getRusTypeAttribute()
    {
        return self::getTypes(true)[$this->type] ?? '';
    }

    /* relations */

    public function object()
    {
        return $this->belongsTo(HomeObject::class, 'id_object', 'id');
    }
}

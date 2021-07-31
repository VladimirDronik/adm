<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Virtual extends Model
{
    protected $table = 'virtualsdev';
    public $timestamps = false;


    /**
     * Получение доступных событий для объекта
     * @return array
     */
    public static function getEvents()
    {
        return [
            'onStatus' => 'Смена статуса',
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
            'status' => ['Статус, on/off', true, true],
        ];
    }

    /* relations */

    public function object()
    {
        return $this->belongsTo(HomeObject::class, 'id_object', 'id');
    }
}

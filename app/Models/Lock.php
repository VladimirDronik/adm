<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Lock extends Model
{
    const TYPE_ELECTROMECHANICAL = 'Electromechanical';
    const TYPE_MAGNETIC = 'Magnetic';
    const TYPE_LATCH = 'Latch';


    protected $table = 'locks';
    public $timestamps = false;
    protected $guarded = ['id'];

    public static function getTypes(bool $is_full = false)
    {
        $types = [
            self::TYPE_ELECTROMECHANICAL => 'Электромеханический',
            self::TYPE_MAGNETIC => 'Магнитный',
            self::TYPE_LATCH => 'Защелка'
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
            'onOpen' => 'Открытие',
            'onClose' => 'Закрытие',
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

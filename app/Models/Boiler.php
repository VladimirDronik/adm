<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;


/**
 * Class Boiler
 *
 * @package App\Models
 * @mixin \Eloquent
 * @property-read mixed $rus_type
 */
class Boiler extends Model
{


    protected $table = 'boiler';
    public $timestamps = false;
    protected $guarded = ['id'];



    public static function getTypes(bool $is_full = true)
    {
        $types = [
            'proterm' => 'Протерм',
            'evan' => 'Эван'
        ];

        return $is_full ? $types : array_keys($types);
    }


    public function getRusTypeAttribute()
    {
        return self::getTypes(true)[$this->type] ?? '';
    }
}

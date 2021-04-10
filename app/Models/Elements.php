<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Elements extends Model
{
    protected $table = 'elements';
    public $timestamps = false;
    protected $guarded = ['id'];

    const TYPE_LABEL = 'label';
    const TYPE_SWITCH = 'switch';
    const TYPE_ACCORDION = 'accordeon';

    public static function getTypes(bool $is_full = false)
    {
        $types = [
            self::TYPE_LABEL => 'Label',
            self::TYPE_SWITCH => 'Switch',
            self::TYPE_ACCORDION => 'Accordeon'
        ];

        return $is_full ? $types : array_keys($types);
    }
}

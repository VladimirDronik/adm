<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Page extends Model
{

    const TYPE_2FIELD = '2field';

    protected $table = 'pages';
    public $timestamps = false;

    public static function getTypes(bool $is_full = false)
    {
        $types = [
            '2field' => '2 блока'
        ];

        return $is_full ? $types : array_keys($types);
    }
}

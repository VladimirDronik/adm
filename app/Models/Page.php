<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Page extends Model
{
    const TYPE_2FIELD = '2field';

    protected $table = 'pages';

    public $timestamps = false;

    protected $guarded = ['id'];

    public static function getTypes(bool $is_full = false)
    {
        $types = [
            '2field' => '2 блока',
        ];

        return $is_full ? $types : array_keys($types);
    }

    public function elements(): HasMany
    {
        return $this->hasMany(Elements::class, 'page', 'id');
    }
}

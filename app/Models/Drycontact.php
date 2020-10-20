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
 */
class Drycontact extends Model
{
    protected $table = 'drycontacts';
    public $timestamps = false;
    protected $guarded = ['id'];

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
}

<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

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
            self::TYPE_ACCORDION => 'Accordeon',
        ];

        return $is_full ? $types : array_keys($types);
    }

    public function internalPages(): HasMany
    {
        return $this->hasMany(InternalPage::class, 'idElement');
    }

    public function object(): BelongsTo
    {
        return $this->belongsTo(HomeObject::class, 'id_object', 'id');
    }

    public function getValueAttribute()
    {
        $value = $this->status;

        if ($this->object && $this->object->type == ObjType::TYPE_BOILER) {
            $boiler = $this->object->boiler;

            if ($this->handle == Boiler::PROP_OUTDOOR_TEMP) {
                $value = $boiler->outdoorSensor?->current;
            } else {
                $value = $boiler->boilersParam ? $boiler->boilersParam[$this->handle]  : null;
            }
        }

        return $value;
    }
}

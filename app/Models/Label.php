<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Label extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $guarded = ['id'];

    const CURRENT_PARAM = 'current';

    const CUR_VALUE_PARAM = 'cur_value';

    const CSUPPLY_PARAM = 'csupply';

    const CRETURN_PARAM = 'creturn';

    const GVSSUPPLY_PARAM = 'gvssupply';

    const GVSRETURN_PARAM = 'gvsreturn';

    const PRESSURE_PARAM = 'pressure';

    const ERROR_CODE_PARAM = 'error_code';

    public static function getParametrs()
    {
        return [
            ObjType::TYPE_TERMOSTAT => [['value' => self::CURRENT_PARAM, 'name' => 'Текущее значение']],
            ObjType::TYPE_LIGHTSTAT => [['value' => self::CURRENT_PARAM, 'name' => 'Текущее значение']],
            ObjType::TYPE_HYGROSTAT => [['value' => self::CURRENT_PARAM, 'name' => 'Текущее значение']],
            ObjType::TYPE_PRESSURESTAT => [['value' => self::CURRENT_PARAM, 'name' => 'Текущее значение']],
            ObjType::TYPE_CARBMONOXIDE => [['value' => self::CUR_VALUE_PARAM, 'name' => 'Текущее значение']],
            ObjType::TYPE_BOILER => [
                ['value' => self::CSUPPLY_PARAM, 'name' => 'Температура подачи'],
                ['value' => self::CRETURN_PARAM, 'name' => 'Температура обратки'],
                ['value' => self::GVSSUPPLY_PARAM, 'name' => 'Температура подачи ГВС'],
                ['value' => self::GVSRETURN_PARAM, 'name' => 'Температура обратки ГВС'],
                ['value' => self::PRESSURE_PARAM, 'name' => 'Давление'],
                ['value' => self::ERROR_CODE_PARAM, 'name' => 'Код ошибки'],
            ],
        ];
    }

    public static function getParametrsByObject(HomeObject $object)
    {
        return self::getParametrs()[$object->type] ?? [];
    }

    public function object()
    {
        return $this->belongsTo(HomeObject::class, 'id_object', 'id');
    }

    public function view()
    {
        return $this->belongsTo(View::class, 'id_item', 'id');
    }
}

<?php

namespace App\Services;

use App\Models\Conditioner;
use App\Models\HomeObject;
use App\Models\ObjType;
use Illuminate\Support\Facades\DB;

class ConditionerService {

    /**
     * Создание кондиционера и объекта кондиционера
     *
     * @param array $data
     * @return int
     * @throws \Throwable
     */
    public function store(array $data): int
    {

        $conditioner = new Conditioner();
        $conditioner->model = $data['model_id'];
        $conditioner->id_room = $data['room_id'];
        $conditioner->ip = $data['ip'];

        DB::transaction(function () use (&$conditioner, $data) {
            $unique_name = HomeObject::getUniqueObjectName(
                0,
                $conditioner->conditionerModel->conditionerVendor->name.' '.$conditioner->conditionerModel->name
            );
            $object = new HomeObject();
            $object->type = ObjType::TYPE_CONDITIONER;
            $object->name = $unique_name;
            $object->status = 'off';
            $object->is_system = 0;
            $object->save();

            $conditioner->id_object = $object->id;
            $conditioner->save();
        });

        return $conditioner->id;
    }
}
<?php

namespace App\Services;

use Exception;
use App\Models\ObjType;
use App\Models\HomeObject;
use App\Models\Conditioner;
use App\Models\ModbusSlaver;
use App\Models\ConditionerType;
use Illuminate\Support\Facades\DB;
use App\Repositories\ConditionerRepository;

class ConditionerService
{
    public function __construct(
        private ConditionerRepository $conditionersRep
    ) {
    }

    private function prepare(Conditioner $conditioner, array $data): void
    {
        $conditioner->name = trim($data['name']);
        $conditioner->modbus_slaver_id = $data['modbus_slaver_id'];
        $conditioner->id_room = $data['id_room'] ?? null;

        $modbusSlaver = ModbusSlaver::find($data['modbus_slaver_id']);
        $conditionerType = ConditionerType::where('device', $modbusSlaver->relatedType->type)->first();

        $registers = $modbusSlaver->registers()
            ->whereIn('alias', ['ac_mode', 'ac_temp', 'ac_fan', 'ac_vdir', 'ac_hdir'])
            ->get();

        foreach ($registers as $register) {
            $conditioner[substr($register->alias, 3)] = $register->last_value;
        }

        if (!$conditionerType) {
            throw new Exception('Запись в таблице conditioner_types с полем device = ' . $modbusSlaver->relatedType->type . ' не найдена');
        }

        $conditioner->type = $conditionerType->id;
    }

    /**
     * Создание кондиционера и объекта кондиционера
     */
    public function store(array $data): int
    {
        $conditioner = new Conditioner();

        $this->prepare($conditioner, $data);

        DB::transaction(function () use (&$conditioner) {
            $uniqueName = HomeObject::getUniqueObjectName(0, $conditioner->name);

            $object = new HomeObject();
            $object->type = ObjType::TYPE_CONDITIONER;
            $object->name = $uniqueName;
            $object->status = 'off';
            $object->is_system = 1;
            $object->save();

            $conditioner->id_object = $object->id;
            $conditioner->save();
        });

        return $conditioner->id;
    }

    /**
     * Изменение кондиционера
     */
    public function update(Conditioner $conditioner, array $data): int
    {
        $this->prepare($conditioner, $data);

        DB::transaction(function () use (&$conditioner, $data) {
            $name = trim($data['name']);

            if ($conditioner->name != $name) {
                $conditioner->object->name = HomeObject::getUniqueObjectName($conditioner->id_object, $name);
                $conditioner->object->save();
            }

            $conditioner->save();
        });

        return $conditioner->id;
    }

    /**
     * Удаление кондиционера и объекта.
     */
    public function delete(int $id): bool
    {
        $conditioner = Conditioner::findOrFail($id);

        $conditioner->object->delete();

        return true;
    }
}

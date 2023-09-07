<?php

namespace App\Services;

use App\Models\Conditioner;
use App\Models\HomeObject;
use App\Models\ObjType;
use App\Repositories\ConditionerRepository;
use Illuminate\Support\Facades\DB;

class ConditionerService {

    private $conditionersRep;

    public function __construct(ConditionerRepository $conditionersRep)
    {
        $this->conditionersRep = $conditionersRep;
    }

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
        $conditioner->device_id = $data['device_id'];
        $conditioner->wb_mir = $data['wb_mir'];

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

    /**
     * Изменение кондиционера
     *
     * @param Conditioner $conditioner
     * @param array $data
     * @return int
     * @throws \Throwable
     */
    public function update(Conditioner $conditioner, array $data): int
    {
        $kind = $conditioner->conditionerModel->conditionerKind->id;

        if (array_key_exists('code', $data)) {
            if ($data['temp'] == 'off') {
                $conditionerCode = $this->conditionersRep
                    ->getOffCode((int)$kind, (string)$data['temp']);
                $this->conditionersRep
                    ->updateOrCreate($conditionerCode ?: null, (string)$data['code'], (int)$kind, null, null, null, true);
            } else {
                $conditionerCode = $this->conditionersRep
                    ->getCode((int)$kind, (string)$data['operationMode'], (string)$data['fanMode'], (float)$data['temp']);
                $this->conditionersRep
                    ->updateOrCreate($conditionerCode ?: null, (string)$data['code'], (int)$kind, (string)$data['operationMode'], (string)$data['fanMode'], (float)$data['temp']);
            }
        }

        $conditioner->id_object = $data['id_object'];
        $conditioner->id_room = $data['id_room'];
        $conditioner->device_id = $data['device_id'];
        $conditioner->wb_mir = $data['wb_mir'];

        $conditioner->save();

        return $conditioner->id;
    }

    /**
     * Команда перевода устройства в режим считывания кода с пульта
     *
     * @param string $ip
     * @param string $wbMir
     * @return null|array
     */
    public function startReadCommand(string $ip, string $wbMir): ?array
    {
        $command = 'rs_control ir_scan -r -d wb-mir --ip ' . $ip . ' -u ' . $wbMir;

        $output = null;

        exec($command, $output);

        return $output ? json_decode($output[0], true) : null;
    }

    /**
     * Команда получения считанного кода с пульта
     *
     * @param string $ip
     * @param string $wbMir
     * @return null|array
     */
    public function reciveCodeCommand(string $ip, string $wbMir): ?array
    {
        $command = 'rs_control ir_scan -g -d wb-mir --ip ' . $ip . ' -u ' . $wbMir;

        $output = null;

        exec($command, $output);

        return $output ? json_decode($output[0], true) : null;
    }

    /**
     * Команда отмены считывания кода с пульта
     *
     * @param string $ip
     * @param string $wbMir
     * @return null|array
     */
    public function cancelReadCommand(string $ip, string $wbMir): ?array
    {
        $command = 'rs_control ir_scan --cancel_scan -d wb-mir --ip ' . $ip . ' -u ' . $wbMir;

        $output = null;

        exec($command, $output);

        return $output ? json_decode($output[0], true) : null;
    }

    /**
     * Удаление кондиционера и объекта.
     *
     * @param int $id
     * @return bool
     */
    public function delete(int $id): bool
    {
        $conditioner = Conditioner::findOrFail($id);

        DB::transaction(function () use (&$conditioner) {
            $conditioner->object->delete();
            $conditioner->delete();
        });

        return true;
    }
}
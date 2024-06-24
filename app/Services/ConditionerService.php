<?php

namespace App\Services;

use Exception;
use App\Models\Method;
use App\Models\Script;
use App\Models\ObjType;
use App\Models\HomeObject;
use App\Models\Conditioner;
use App\Models\ModbusSlaver;
use App\Models\SchedulerTask;
use App\Models\SchedulerPoint;
use App\Models\ConditionerType;
use Illuminate\Support\Facades\DB;
use Database\Seeders\ScriptsTableSeeder;
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
        $conditioner->id_room = $data['id_room'] ?? null;

        if (array_key_exists('modbus_slaver_id', $data)) {
            $conditioner->modbus_slaver_id = $data['modbus_slaver_id'];
        }
    }

    /**
     * Создание кондиционера и объекта кондиционера
     */
    public function store(array $data): int
    {
        $conditioner = new Conditioner();

        $this->prepare($conditioner, $data);

        $modbusSlaver = ModbusSlaver::find($data['modbus_slaver_id']);
        $conditionerType = ConditionerType::where('device', $modbusSlaver->relatedType->type)->first();

        if (!$conditionerType) {
            throw new Exception('Запись в таблице conditioner_types с полем device = ' . $modbusSlaver->relatedType->type . ' не найдена');
        }

        $conditioner->type = $conditionerType->id;

        DB::transaction(function () use (&$conditioner) {
            $uniqueName = HomeObject::getUniqueObjectName(0, $conditioner->name);

            $object = new HomeObject();
            $object->type = ObjType::TYPE_CONDITIONER;
            $object->name = $uniqueName;
            $object->status = 'off';
            $object->is_system = 1;
            $object->save();

            $this->createCheckMethodWithEvent($object->id);

            $conditioner->id_object = $object->id;
            $conditioner->save();
        });

        chdir(env('SERVER_FOLDER') . '/scripts');
        exec('php ac_polling.php ' . $conditioner->id_object);

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

    /**
     * Запуск скрипта смены состояния кондиционера
     *
     * @param int $condObjId
     * @param string $newStatus Доступные значения 'on' или 'off'
     * @return array
     */
    public function setStatus(int $condObjId, string $newStatus): array
    {
        $output = [];
        $resultCode = null;

        $scripts = [
            'on' => 'ac_on.php',
            'off' => 'ac_off.php',
        ];

        chdir(env('SERVER_FOLDER').'/scripts');
        exec('php ' . $scripts[$newStatus] . ' ' . $condObjId, $output, $resultCode);

        return [
            'code' => $resultCode,
            'output' => $output,
        ];
    }

    /**
     * Запуск скрипта установки температуры кондиционера
     *
     * @param int $condObjId
     * @param int $newTemp
     * @return array
     */
    public function setTemp(int $condObjId, int $newTemp): array
    {
        $output = [];
        $resultCode = null;

        chdir(env('SERVER_FOLDER').'/scripts');
        exec('php ac_set_temperature.php ' . $condObjId . ' ' . $newTemp, $output, $resultCode);

        return [
            'code' => $resultCode,
            'output' => $output,
        ];
    }

    /**
     * Запуск скрипта установки режима работы кондиционера
     *
     * @param int $condObjId
     * @param string $newMode
     * @return array
     */
    public function setMode(int $condObjId, string $newMode): array
    {
        $output = [];
        $resultCode = null;

        chdir(env('SERVER_FOLDER').'/scripts');
        exec('php ac_set_mode.php ' . $condObjId . ' ' . $newMode, $output, $resultCode);

        return [
            'code' => $resultCode,
            'output' => $output,
        ];
    }

    /**
     * Запуск скрипта установки скорости вентилятора кондиционера
     *
     * @param int $condObjId
     * @param string $newFan
     * @return array
     */
    public function setFan(int $condObjId, string $newFan): array
    {
        $output = [];
        $resultCode = null;

        chdir(env('SERVER_FOLDER').'/scripts');
        exec('php ac_set_fan.php ' . $condObjId . ' ' . $newFan, $output, $resultCode);

        return [
            'code' => $resultCode,
            'output' => $output,
        ];
    }

    /**
     * Запуск скрипта установки вертикального направления воздуха кондиционера
     *
     * @param int $condObjId
     * @param string $newVdir
     * @return array
     */
    public function setVdir(int $condObjId, string $newVdir): array
    {
        $output = [];
        $resultCode = null;

        chdir(env('SERVER_FOLDER').'/scripts');
        exec('php ac_set_vdir.php ' . $condObjId . ' ' . $newVdir, $output, $resultCode);

        return [
            'code' => $resultCode,
            'output' => $output,
        ];
    }

    /**
     * Запуск скрипта установки горизонтального направления воздуха кондиционера
     *
     * @param int $condObjId
     * @param string $newHdir
     * @return array
     */
    public function setHdir(int $condObjId, string $newHdir): array
    {
        $output = [];
        $resultCode = null;

        chdir(env('SERVER_FOLDER').'/scripts');
        exec('php ac_set_hdir.php ' . $condObjId . ' ' . $newHdir, $output, $resultCode);

        return [
            'code' => $resultCode,
            'output' => $output,
        ];
    }

    /**
     * Создание метода 'Опрос кондиционера' и элемента планировщика 'Опрос кондиционера' (каждую 1 мин)
     */
    private function createCheckMethodWithEvent(int $objectId): void
    {
        $script = Script::where('link', 'ac_polling.php')
            ->where('system', 1)
            ->first();

        if (!$script) {
            $script = Script::create(ScriptsTableSeeder::getCheckConditionerScript());
        }

        $method = Method::create([
            'name' => 'Опрос кондиционера',
            'id_object' => $objectId,
            'comment' => 'Периодический опрос кондиционера',
            'is_system' => 1,
            'script' => $script->id,
        ]);

        $schedulerTask = SchedulerTask::create([
            'name' => 'Опрос кондиционера',
            'is_system' => 1,
            'is_hidden' => 1,
            'object' => $objectId,
            'method' => $method->id,
        ]);

        SchedulerPoint::create([
            'id_task' => $schedulerTask->id,
            'type' => 'c',
            'time' => '1',
            'days' => '',
            'close' => 1,
            'system' => 1,
        ]);
    }
}

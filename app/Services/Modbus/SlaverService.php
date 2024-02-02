<?php

namespace App\Services\Modbus;

use App\Models\DaliDevice;
use App\Models\HomeObject;
use App\Models\ModbusSlaver;
use App\Models\ObjType;
use App\Models\Script;
use App\Models\Method;
use Database\Seeders\ScriptsTableSeeder;
use Illuminate\Support\Facades\DB;

class SlaverService
{
    public function prepare(ModbusSlaver $slaver, array $data)
    {
        $slaver->name = $data['name'];
        $slaver->type = $data['type'];
        $slaver->bus = $data['bus'];
        $slaver->address = $data['address'];
    }

    /**
     * Создание устройства
     */
    public function store(array $data): int
    {
        $slaver = new ModbusSlaver();

        $this->prepare($slaver, $data);

        $slaver->save();

        return $slaver->id;
    }

    /**
     * Изменение устройства
     */
    public function update(ModbusSlaver $slaver, array $data): int
    {
        $this->prepare($slaver, $data);

        $slaver->save();

        return $slaver->id;
    }

    /**
     * Удалить устройство
     *
     * @return bool
     */
    public function delete(int $id)
    {
        $modbusSlaver = ModbusSlaver::findOrFail($id);

        DB::transaction(function () use ($modbusSlaver) {
            if ($modbusSlaver->relatedType->type == 'ecodim-dali-gw2' && $modbusSlaver->daliDevices->isNotEmpty()) {
                foreach ($modbusSlaver->daliDevices as $daliDevice) {
                    if ($daliDevice->object) {
                        $daliDevice->object->delete();
                    } else {
                        $daliDevice->delete();
                    }
                }
            }

            $modbusSlaver->delete();
        });

        return true;
    }

    /**
     * Изменение устройства DALI
     */
    public function updateDaliDevice(DaliDevice $daliDevice, array $data): int
    {
        $daliDevice->name = $data['name'];

        $daliDevice->room = $data['room'];

        $daliDevice->save();

        return $daliDevice->id;
    }

    /**
     * Запуск скрипта сборки сети для DALI
     *
     * @param int $id
     * @return array
     */
    public function networkAssembly(int $id): array
    {
        $output = [];
        $resultCode = null;

        chdir(env('SERVER_FOLDER').'/scripts');
        exec('php dali_assembling.php ' . $id, $output, $resultCode);

        $this->createDaliDevicesObjectsAndMethods();

        return [
            'code' => $resultCode,
            'output' => $output,
        ];
    }

    /**
     * Запуск скрипта расширения сети для DALI
     *
     * @param int $id
     * @return array
     */
    public function networkExpansion(int $id): array
    {
        $output = [];
        $resultCode = null;

        chdir(env('SERVER_FOLDER').'/scripts');
        exec('php dali_expanding.php ' . $id, $output, $resultCode);

        $this->createDaliDevicesObjectsAndMethods();

        return [
            'code' => $resultCode,
            'output' => $output,
        ];
    }

    /**
     * Создание объектов и методов для всех записей в таблице dali_devices
     *
     * @return void
     */
    private function createDaliDevicesObjectsAndMethods(): void
    {
        $daliDevices = DaliDevice::get();

        if ($daliDevices->isNotEmpty()) {
            $scripts = ScriptsTableSeeder::getDaliDeviceScripts();
            $methods = [];

            foreach ($scripts as $script) {
                $scriptId = $this->getScriptId($script);
                $methods[] = [
                    'name' => $script['name'],
                    'script' => $scriptId,
                    'comment' => $script['name'],
                    'is_system' => 1,
                ];
            }

            foreach ($daliDevices as $daliDevice) {
                if (!$daliDevice->id_object) {
                    $uniqueName = HomeObject::getUniqueObjectName(0, $daliDevice->name);

                    $object = HomeObject::create([
                        'name' => $uniqueName,
                        'type' => ObjType::TYPE_DALI,
                        'status' => 'off',
                        'is_system' => 1,
                    ]);

                    $daliDevice->update([
                        'id_object' => $object->id,
                    ]);

                    foreach ($methods as $method) {
                        $method['id_object'] = $object->id;
                        Method::create($method);
                    }
                }
            }
        }
    }

    private function getScriptId(array $scriptArray): int
    {
        $script = Script::where('link', $scriptArray['link'])
            ->where('system', 1)
            ->first();

        if (! $script) {
            $script = Script::create($scriptArray);
        }

        return $script->id;
    }
}

<?php

namespace App\Services;

use App\Models\Port;
use App\Models\Usensor;
use App\Models\ObjType;
use App\Models\HomeObject;
use App\Models\Pressurestat;
use Illuminate\Support\Facades\DB;
use App\Repositories\PortRepository;

class UsensorService
{
    public function __construct(
        private PortRepository $portRepository,
        private LightstatService $lightstatService,
        private HygrostatService $hygrostatService,
        private TermostatService $termostatService,
        private CarbdioxideService $carbdioxideService,
        private PressurestatService $pressurestatService
    ) {
    }

    /**
     * Создание I2C датчика.
     *
     * @throws \Throwable
     */
    public function store(array $data): int
    {
        $usensor = new Usensor();
        $usensor->fill($data);

        $dbWriting = DB::transaction(function () use (&$usensor, $data) {
            $portSDA = $data['port_SDA'];
            $portSCL = $data['port_SCL'];
            $deviceId = $data['device_id'];

            $object = HomeObject::create([
                'type' => ObjType::TYPE_USENSOR,
                'name' => HomeObject::getUniqueObjectName(0, $usensor->name),
                'status' => 'off',
                'is_system' => 1,
            ]);

            $usensor->id_object = $object->id;
            $usensor->save();

            Port::find($portSDA)->update([
                'object' => $object->id,
                'status' => 'I2C',
                'comment' => $data['name'],
            ]);

            ConfigMegaService::setPortType(
                $deviceId,
                $this->portRepository->getNumPortByID($portSDA),
                'SDA'
            );

            Port::find($portSCL)->update([
                'object' => $object->id,
                'status' => 'I2C',
                'comment' => $data['name'],
            ]);

            ConfigMegaService::setPortType(
                $deviceId,
                $this->portRepository->getNumPortByID($portSCL),
                'SCL'
            );

            return true;
        });

        if ($dbWriting === true) {
            $this->createDetectorsByType($usensor);
        }

        return $usensor->id;
    }

    /**
     * Удаление I2C датчика.
     *
     * @throws \Throwable
     */
    public function delete(int $id): bool
    {
        $usensor = Usensor::findOrFail($id);

        Port::where('object', $usensor->id_object)->update([
            'object' => null,
            'status' => 'IN',
            'comment' => '',
        ]);

        DB::transaction(function () use ($usensor) {
            if ($usensor->sensors()->isNotEmpty()) {
                foreach ($usensor->sensors() as $sensor) {
                    if ($sensor->is_system) {
                        if ($sensor->id_object) {
                            $sensor->iobject->delete();
                        }
                        $sensor->delete();
                    }
                }
            }

            if ($usensor->id_object) {
                $usensor->iobject->delete();
            }
            $usensor->delete();
        });

        return true;
    }

    /**
     * Обновление I2C датчика.
     *
     * @throws \Throwable
     */
    public function update(Usensor $usensor, array $data): int
    {
        DB::transaction(function () use (&$usensor, $data) {
            $name = trim($data['name']);
            if ($usensor->name != $name) {
                $usensor->iobject->name = HomeObject::getUniqueObjectName($usensor->id_object, $name);
                $usensor->iobject->save();
            }

            $sdaPortNum = $this->portRepository->getNumPortByID($data['port_SDA']);
            $sclPortNum = $this->portRepository->getNumPortByID($data['port_SCL']);

            Port::where('object', $usensor->id_object)->update([
                'object' => null,
                'status' => 'IN',
                'comment' => '',
            ]);

            ConfigMegaService::setPortType($data['device_id'], $sdaPortNum, 'IN');
            ConfigMegaService::setPortType($data['device_id'], $sclPortNum, 'IN');

            Port::find($data['port_SDA'])->update([
                'object' => $usensor->id_object,
                'status' => 'I2C',
                'comment' => $data['name'],
            ]);

            ConfigMegaService::setPortType($data['device_id'], $sdaPortNum, 'SDA');

            Port::find($data['port_SCL'])->update([
                'object' => $usensor->id_object,
                'status' => 'I2C',
                'comment' => $data['name'],
            ]);

            ConfigMegaService::setPortType($data['device_id'], $sclPortNum, 'SCL');

            $usensor->fill($data);
            $usensor->save();
        });

        return $usensor->id;
    }

    /**
     * Автоматическое создание статов по выбранному типу I2C датчика
     */
    private function createDetectorsByType(Usensor $usensor): void
    {
        switch ($usensor->type) {
            case Usensor::TYPE_BH1750:
                $this->createLightstat($usensor, 0, 54612, 0, 54612);
                break;
            case Usensor::TYPE_MAX44009:
                $this->createLightstat($usensor, 0, 188000, 0, 188000);
                break;
            case Usensor::TYPE_HTU21D:
                $this->createHygrostat($usensor);
                $this->createTermostat($usensor);
                break;
            case Usensor::TYPE_BME280:
                $this->createHygrostat($usensor);
                $this->createTermostat($usensor);
                $this->createPressurestat($usensor, Pressurestat::TYPE_BMX280);
                break;
            case Usensor::TYPE_OUTDOORV2:
                $this->createHygrostat($usensor);
                $this->createTermostat($usensor);
                $this->createLightstat($usensor, 0, 54612, 0, 54612);
                break;
            case Usensor::TYPE_OUTDOORV3:
                $this->createHygrostat($usensor);
                $this->createTermostat($usensor);
                $this->createLightstat($usensor, 0, 54612, 0, 54612);
                $this->createPressurestat($usensor, Pressurestat::TYPE_BMX280);
                break;
            case Usensor::TYPE_SCD40:
                $this->createCarbdioxide($usensor);
                $this->createHygrostat($usensor);
                $this->createTermostat($usensor);
                break;
            case Usensor::TYPE_SCD41:
                $this->createCarbdioxide($usensor);
                $this->createHygrostat($usensor);
                $this->createTermostat($usensor);
                break;
            case Usensor::TYPE_PTSENSOR:
                $this->createPressurestat($usensor, Pressurestat::TYPE_PTSENSOR);
                break;
        }
    }

    private function createLightstat(Usensor $usensor, int $minAlarm, int $maxAlarm, int $minThreshold, int $maxThreshold): void
    {
        $this->lightstatService->store([
            'name' => 'Датчик освещенности (' . $usensor->type . '). ' . $usensor->relatedRoom->name,
            'room' => $usensor->room,
            'usensor_id' => $usensor->id_object,
            'mode' => 0,
            'optimal' => 10,
            'gisteresis' => 10,
            'min_alarm' => $minAlarm,
            'max_alarm' => $maxAlarm,
            'min_threshold' => $minThreshold,
            'max_threshold' => $maxThreshold,
            'is_system' => 1,
        ]);
    }

    private function createTermostat(Usensor $usensor): void
    {
        $this->termostatService->store([
            'name' => 'Датчик температуры (' . $usensor->type . '). ' . $usensor->relatedRoom->name,
            'room' => $usensor->room,
            'usensor_id' => $usensor->id_object,
            'placetype' => 'usensor',
            'device_id' => null,
            'thermostat' => 1,
            'optimal' => 22,
            'gisteresis' => 1,
            'min_alarm' => 0,
            'max_alarm' => 40,
            'is_system' => 1,
        ]);
    }

    private function createHygrostat(Usensor $usensor): void
    {
        $this->hygrostatService->store([
            'name' => 'Датчик влажности (' . $usensor->type . '). ' . $usensor->relatedRoom->name,
            'room' => $usensor->room,
            'usensor_id' => $usensor->id_object,
            'type' => 0,
            'optimal' => 50,
            'gisteresis' => 5,
            'min_alarm' => 0,
            'max_alarm' => 80,
            'is_system' => 1,
        ]);
    }

    private function createPressurestat(Usensor $usensor, string $sensorType): void
    {
        $this->pressurestatService->store([
            'name' => 'Датчик давления (' . $usensor->type . '). ' . $usensor->relatedRoom->name,
            'room' => $usensor->room,
            'usensor_id' => $usensor->id_object,
            'mode' => 0,
            'type_sensor' => $sensorType,
            'optimal' => 760,
            'gisteresis' => 5,
            'min_alarm' => 600,
            'max_alarm' => 820,
            'is_system' => 1,
        ]);
    }

    private function createCarbdioxide(Usensor $usensor): void
    {
        $this->carbdioxideService->store([
            'name' => 'Датчик углекислого газа (' . $usensor->type . '). ' . $usensor->relatedRoom->name,
            'room' => $usensor->room,
            'usensor_id' => $usensor->id_object,
            'mode' => 0,
            'optimal' => 900,
            'gisteresis' => 50,
            'min_alarm' => 400,
            'max_alarm' => 1400,
            'is_system' => 1,
        ]);
    }
}

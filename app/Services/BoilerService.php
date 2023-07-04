<?php
/**
 * Created by PhpStorm.
 * User: kinord
 * Date: 11.04.21
 * Time: 16:27
 */

namespace App\Services;
use App\Models\Boiler;
use App\Models\BoilerAuto;
use App\Models\BoilerWater;
use App\Models\BoilerManual;
use App\Services\BoilerObjectService;
use Illuminate\Support\Facades\DB;
use App\Models\HomeObject;

class BoilerService
{
    private $boiler_object_service;


    public function __construct(BoilerObjectService $boilerObjectService)
    {
        $this->boiler_object_service = $boilerObjectService;
    }

    public function update(Boiler $boiler, array $data): int
    {
        DB::transaction(function () use (&$boiler, $data) {
            if ($this->isUpdateAutoObjectName($boiler, $data['name'])) {
                $boiler->object->name = HomeObject::getUniqueObjectName($boiler->id_object, trim($data['name']));
                $boiler->object->save();
            }

            $boiler->name = $data['name'];
            $boiler->ip_address = $data['ip_address'];
            $boiler->thermostat = $data['thermostat'];
            $boiler->boiler = $data['boiler'];
            $boiler->target_water_temp = $data['target_water_temp'];
            $boiler->id_outside_thermostat = array_key_exists('id_outside_thermostat', $data) ? $data['id_outside_thermostat'] : null;
            $boiler->mode = $data['mode'];

            $boiler->save();

            if ($boiler->mode == Boiler::PROP_MANUALMODE) {
                $boilerManual = $boiler->object->boilerManual;
                $boilerManual->set_value = $data['set_value'];
                $boilerManual->save();
            }

            if ($boiler->mode == Boiler::PROP_AUTOMODE) {
                if (array_key_exists('boiler_auto', $data)) {
                    foreach ($data['boiler_auto'] as $id => $boilerAutoData) {
                        BoilerAuto::where('id', $id)->update($boilerAutoData);
                    }
                }

                if (array_key_exists('t_out', $data) && array_key_exists('t_water', $data)) {
                    $idObject = $boiler->object->id;
                    $tOut = $data['t_out'];
                    $tWater = $data['t_water'];
                    $fieldsSet = [];

                    for ($i = 0; $i < count($tOut); $i++) {
                        $fieldsSet[] = [
                            't_out' => $tOut[$i],
                            't_water' => $tWater[$i],
                            'id_object' => $idObject,
                        ];
                    }

                    foreach ($fieldsSet as $fields) {
                        BoilerAuto::create($fields);
                    }
                }
            }
        });

        return true;
    }


    private function isUpdateAutoObjectName(Boiler $boiler, string $name): bool
    {
        return $boiler->name !== trim($name) && $boiler->object && $boiler->object->is_system;
    }

    public function store(array $data): int
    {
        $boiler = new Boiler();
        $boiler->name = $data['name'];
        $boiler->ip_address = $data['ip_address_boiler'];
        $boiler->protocol = $data['type_boiler'];
        $boiler->id_outside_thermostat = array_key_exists('id_outside_thermostat', $data) ? $data['id_outside_thermostat'] : null;
        $boiler->mode = Boiler::PROP_MANUALMODE;

        $boiler->thermostat = 0;
        $boiler->boiler = 1;
        $boiler->lock = 0;


        DB::transaction(function () use (&$boiler, $data) {

            $unique_name = HomeObject::getUniqueObjectName(0, $boiler->name);
            $object = $this->boiler_object_service->createBoilerObject($unique_name);
            $this->boiler_object_service->createBoilerObjectMethodsWithEvents($object->id);
            $boiler->id_object = $object->id;

            BoilerWater::create([
                'id_object' => $boiler->id_object,
                'min_value' => BoilerWater::MIN_VALUE,
                'max_value' => BoilerWater::MAX_VALUE,
            ]);

            BoilerManual::create([
                'id_object' => $boiler->id_object,
                'min_value' => BoilerManual::MIN_VALUE,
                'max_value' => BoilerManual::MAX_VALUE,
            ]);

            $boiler->save();
        });

        return $boiler->id_object;
    }

    /**
     * @param int $boilerAutoId
     * @return bool
     * @throws \Throwable
     */
    public function boilerAutoDelete(int $boilerAutoId)
    {
        BoilerAuto::where('id', $boilerAutoId)->delete();
        return true;
    }
}

<?php

namespace App\Services;

use App\Models\Page;
use App\Models\Boiler;
use App\Models\BoilerAuto;
use App\Models\HomeObject;
use App\Models\BoilersParam;
use App\Models\BoilersParamsFlag;
use Illuminate\Support\Facades\DB;

class BoilerService
{
    public function __construct(
        private BoilerObjectService $boilerObjectService
    ) {
    }

    public function update(Boiler $boiler, array $data): int
    {
        DB::transaction(function () use (&$boiler, $data) {
            $name = trim($data['name']);
            $page = Page::where('name', $boiler->object->name)->first();

            if ($boiler->name != $name) {
                $objectName = HomeObject::getUniqueObjectName(
                    $boiler->id_object,
                    trim($data['name'])
                );

                $boiler->object->update([
                    'name' => $objectName,
                ]);;

                if ($page) {
                    $page->update([
                        'name' => $objectName,
                    ]);
                }
            }

            $boiler->name = $name;
            $boiler->outdoor_sensor = array_key_exists('outdoor_sensor', $data) ? $data['outdoor_sensor'] : null;
            $boiler->mode = $data['mode'];
            $boiler->heating_mode = $data['heating_mode'];
            $boiler->gateway_id = $data['gateway_id'];

            $boiler->save();

            $boiler->boilersParamsFlag->update([
                'ch_current_temp' => $data['ch_current_temp'] ?? 0,
                'ch_setpoint_temp' => $data['ch_setpoint_temp'] ?? 0,
                'dhw_current_temp' => $data['dhw_current_temp'] ?? 0,
                'dhw_setpoint_temp' => $data['dhw_setpoint_temp'] ?? 0,
                'return_temp' => $data['return_temp'] ?? 0,
                'modulation' => $data['modulation'] ?? 0,
                'pressure' => $data['pressure'] ?? 0,
                'error_code' => $data['error_code'] ?? 0,
                'outdoor_temp' => $boiler->outdoor_sensor ? 1 : 0,
            ]);

            if ($page) {
                $boiler->updatePageElements($page->id);
            }

            // if ($boiler->mode == Boiler::PROP_MANUALMODE) {
            //     $boilerManual = $boiler->object->boilerManual;
            //     $boilerManual->set_value = $data['set_value'];
            //     $boilerManual->save();
            // }

            // if ($boiler->mode == Boiler::PROP_AUTOMODE) {
            //     if (array_key_exists('boiler_auto', $data)) {
            //         foreach ($data['boiler_auto'] as $id => $boilerAutoData) {
            //             BoilerAuto::where('id', $id)->update($boilerAutoData);
            //         }
            //     }

            //     if (array_key_exists('t_out', $data) && array_key_exists('t_water', $data)) {
            //         $idObject = $boiler->object->id;
            //         $tOut = $data['t_out'];
            //         $tWater = $data['t_water'];
            //         $fieldsSet = [];

            //         for ($i = 0; $i < count($tOut); $i++) {
            //             $fieldsSet[] = [
            //                 't_out' => $tOut[$i],
            //                 't_water' => $tWater[$i],
            //                 'id_object' => $idObject,
            //             ];
            //         }

            //         foreach ($fieldsSet as $fields) {
            //             BoilerAuto::create($fields);
            //         }
            //     }
            // }
        });

        return true;
    }

    public function store(array $data): int
    {
        $boiler = new Boiler();
        $boiler->name = $data['name'];
        $boiler->type = $data['type'];
        $boiler->mode = $data['mode'];
        $boiler->heating_mode = Boiler::HEATING_MODE_MANUAL;
        $boiler->outdoor_sensor = array_key_exists('outdoor_sensor', $data) ? $data['outdoor_sensor'] : null;
        $boiler->gateway_type = $data['gateway_type'];

        switch ($boiler->gateway_type) {
            case HomeObject::GATEWAY_MODBUS:
                $boiler->gateway_id = $data['modbus_gateway_id'];
                $boiler->protocol = $boiler->protocol_by_slaver;
                break;
            case HomeObject::GATEWAY_HTTP:
                $boiler->gateway_id = $data['http_gateway_id'];
                $boiler->protocol = $data['type_boiler'];
                break;
        }

        DB::transaction(function () use (&$boiler) {
            $uniqueName = HomeObject::getUniqueObjectName(0, $boiler->name);
            $object = $this->boilerObjectService->createBoilerObject($uniqueName);

            $this->boilerObjectService
                ->createMethodsAndEvents($object->id, $boiler->gateway_type == HomeObject::GATEWAY_MODBUS ? $boiler->gateway_id : null);

            $boiler->id_object = $object->id;

            // BoilerWater::create([
            //     'id_object' => $boiler->id_object,
            //     'min_value' => BoilerWater::MIN_VALUE,
            //     'max_value' => BoilerWater::MAX_VALUE,
            // ]);

            // BoilerManual::create([
            //     'id_object' => $boiler->id_object,
            //     'min_value' => BoilerManual::MIN_VALUE,
            //     'max_value' => BoilerManual::MAX_VALUE,
            //     'set_value' => BoilerManual::DEFAULT_SET_VALUE,
            // ]);

            $boiler->save();

            $this->createBoilersParam($boiler);
            $this->createBoilersParamsFlag($boiler);
        });

        return $boiler->id_object;
    }

    /**
     * @return bool
     *
     * @throws \Throwable
     */
    public function boilerAutoDelete(int $boilerAutoId)
    {
        BoilerAuto::where('id', $boilerAutoId)->delete();

        return true;
    }

    public function createBoilersParam(Boiler $boiler): BoilersParam
    {
        return BoilersParam::create([
            'boiler_id' => $boiler->id,
        ]);
    }

    public function createBoilersParamsFlag(Boiler $boiler): BoilersParamsFlag
    {
        switch ($boiler->type) {
            case Boiler::TYPE_GAS:
                $modulation = 1;
                break;
            case Boiler::TYPE_ELECTRO:
                $modulation = 0;
                break;
            default:
                $modulation = 0;
                break;
        }

        switch ($boiler->modbusSlaver?->relatedType->type) {
            case 'bcg-301-w':
                $pressure = 1;
                break;
            case 'beg-311-w':
                $pressure = 0;
                break;
            default:
                $pressure = 0;
                break;
        }

        return BoilersParamsFlag::create([
            'boiler_id' => $boiler->id,
            'modulation' => $modulation,
            'pressure' => $pressure,
            'ch_current_temp' => 1,
            'ch_setpoint_temp' => 1,
            'dhw_current_temp' => 1,
            'dhw_setpoint_temp' => 1,
            'return_temp' => 1,
            'error_code' => 1,
            'outdoor_temp' => $boiler->outdoor_sensor ? 1 : 0,
            'indoor_temp' => 0,
        ]);
    }
}

<?php
/**
 * Created by PhpStorm.
 * User: kinord
 * Date: 11.04.21
 * Time: 16:27
 */

namespace App\Services;
use App\Models\Boiler;
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

            $boiler->save();
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
        $boiler->model = $data['type_boiler'];
        $boiler->mode = 'auto';
       

        DB::transaction(function () use (&$boiler, $data) {

            $unique_name = HomeObject::getUniqueObjectName(0, $boiler->name);
            $object = $this->boiler_object_service->createBoilerObject($unique_name);
            $boiler->id_object = $object->id;

            $boiler->save();

        });

        return $boiler->id_object;
    }
}
<?php
/**
 * Created by PhpStorm.
 * User: kinord
 * Date: 08.10.20
 * Time: 11:26
 */

namespace App\Services;

use App\Models\Carbmonoxide;
use Illuminate\Support\Facades\DB;
use App\Models\HomeObject;
use App\Models\Port;

class CarbmonoxideService
{

    private $carbmonoxide_object_service;

    public function __construct(CarbmonoxideObjectService $objectService)
    {
        $this->carbmonoxide_object_service = $objectService;
    }


    public function prepare(Carbmonoxide $carbmonoxide, array $data)
    {

        unset($data['device_id']);
        unset($data['port']);


        if (($data['room'] ?? 0) == 0) {
            $data['room'] = null;
        }

        $carbmonoxide->fill($data);
    }

    /**
     * Создание датчика.
     *
     * @param array $data
     * @return int
     * @throws \Throwable
     */
    public function store(array $data): int
    {

        $carbmonoxide = new Carbmonoxide();

        $port = $data['port'] ?? null;

        $this->prepare($carbmonoxide, $data);
        $carbmonoxide->cur_value = 0;


            DB::transaction(function () use (&$carbmonoxide, $port) {

                $unique_name = HomeObject::getUniqueObjectName(0, $carbmonoxide->name);
                $object = $this->carbmonoxide_object_service->createCarbmonoxideObject($unique_name);
                $this->carbmonoxide_object_service->createCarbmonoxideObjectMethodsWithEvents($object->id);
                $carbmonoxide->id_object = $object->id;
                $carbmonoxide->save();

                if ($port) {
                    Port::where('id', $port)->update(['object' => $object->id]);
                }

            });


        return $carbmonoxide->id;
    }


    public function delete(int $id): bool
    {
        $carbmonoxide = Carbmonoxide::findOrFail($id);

        if ($carbmonoxide->iobject && $carbmonoxide->iobject->is_system) {
            DB::transaction(function () use (&$carbmonoxide) {
                HomeObject::deleteAutoObject($carbmonoxide->id_object);
                $carbmonoxide->delete();
            });
        } else {
            $carbmonoxide->delete();
        }

        return true;
    }
}
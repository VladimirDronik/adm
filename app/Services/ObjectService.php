<?php

namespace App\Services;

use App\Models\Method;
use App\Models\Termostat;
use App\Models\HomeObject;
use Illuminate\Support\Facades\DB;
use App\Repositories\ObjectRepository;
use Illuminate\Database\Eloquent\Builder;

class ObjectService
{
    public function __construct(
        private ObjectRepository $rep
    ) {
    }

    public function delete(int $id)
    {
        return HomeObject::destroy($id);
    }

    public function deleteObjects($ids)
    {
        if (empty($ids)) {
            DB::table('objects')
                ->where('is_system', 0)
                ->delete();
        } else {
            HomeObject::whereIn('id', $ids)
                ->where('is_system', 0)
                ->delete();
        }

        return true;
    }

    public function prepareObject(HomeObject $object, array $data)
    {
        $object->type = trim($data['type']);
        $object->name = trim($data['name']);
        $object->status = 'off';
    }

    public function store(array $data)
    {
        $object = new HomeObject();
        $this->prepareObject($object, $data);
        $object->save();

        return $object->id;
    }

    public function update(HomeObject $object, array $data)
    {
        $this->prepareObject($object, $data);
        $object->save();

        return $object->id;
    }

    public function getMethodsByObjectId(int $objectId): array
    {
        $object = HomeObject::find($objectId);

        if (! $object) {
            return [];
        }

        $methodsQuery = $object->methods();

        if ($object->lamp) {
            $methodsQuery->where(function (Builder $subQuery) use ($object) {
                $subQuery->whereIn('alias', $object->lamp->getMethodsAliasByType())
                    ->orWhereNull('alias');
            });
        }

        return $methodsQuery->orderBy('name')
            ->select('id', 'name', 'params')
            ->get()
            ->toArray();
    }

    /**
     * Получение описание параметров метода по его ид
     */
    public function getParamsByMethodId(int $methodId)
    {
        return Method::where('id', $methodId)
            ->first()
            ->params;
    }

    public function getPropertiesByObjectId($objectId, $easyArray = true): array
    {
        if ($objectId) {
            $object = HomeObject::find($objectId);

            switch ($object->type) {
                case 'boiler': $properties = $object->boiler->getProperties();
                    break;

                case 'termostat': $properties = Termostat::getProperties();
                    break;

                default: return [];
            }

            if ($easyArray) {
                $propertiesArray = [];

                foreach ($properties as $key => $property) {
                    $propertiesArray[] = ['id' => $key, 'name' => $property];
                }

                return $propertiesArray;
            } else {
                return $properties;
            }
        }

        return [];
    }

    public function getObjectsByType($typeObject): array
    {
        $query = HomeObject::query();

        if ($typeObject) {
            if (is_array($typeObject)) {
                $query->whereIn('type', $typeObject);
            } else {
                $query->where('type', $typeObject);

                if (($typeObject == 'switch') || ($typeObject == 'button')) {
                    $query->orwhere('type', 'lamp')
                        ->orwhere('type', 'relay')
                        ->orwhere('type', 'socket')
                        ->orwhere('type', 'curtain')
                        ->orwhere('type', 'lock')
                        ->orwhere('type', 'virtual');
                }
            }

            return $query->orderBy('name')
                ->select('id', 'name')
                ->get()
                ->toArray();
        }

        return [];
    }

    public function getMethodsByObjectIdToArray($objectId): array
    {
        if ($objectId) {
            return Method::where('id_object', $objectId)
                ->orderBy('name')
                ->select('id', 'name')
                ->pluck('name', 'id')
                ->toArray();
        }

        return [];
    }

    public function isNameExists(string $name): bool
    {
        return HomeObject::where('name', $name)->exists();
    }

    public function getObjectsArray(): array
    {
        return HomeObject::orderBy('name')
            ->select('id', 'name')
            ->get()
            ->toArray();
    }

    public function getObjects()
    {
        return HomeObject::orderBy('name')->get();
    }

    /**
     * Возвращает id объекта, соответсвующего методу
     *
     * @param $idMethod - id метода
     */
    public function getObjectByMethod($idMethod)
    {
        if ($idMethod) {
            $return = Method::where('id', $idMethod)->first();

            return $return->id_object;
        }

        return null;
    }

    /**
     * Возвращает первый попавшийся (или единственный) метод для объекта
     */
    public function getMethodByObject($idObject)
    {
        if ($idObject) {
            $return = Method::where('id_object', $idObject)
                ->where('is_system', 1)
                ->first();
            if ($return) {
                return $return->id;
            }
        }

        return null;
    }
}

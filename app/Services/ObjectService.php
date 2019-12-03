<?php

namespace App\Services;

use App\Models\HomeObject;
use App\Models\Method;
use App\Repositories\ObjectRepository;
use Illuminate\Support\Facades\DB;

class ObjectService {

    private $rep;

    public function __construct(ObjectRepository $rep)
    {
        $this->rep = $rep;
    }

    public function delete(int $id)
    {
        return HomeObject::destroy($id);
    }

    public function deleteObjects($ids)
    {
        if (empty($ids)) {
            DB::table('objects')->delete();
        } else {
            HomeObject::whereIn('id', $ids)->delete();
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

    public function getMethodsByObjectId(int $object_id)
    {
        if ($object_id) {
            return Method::where('id_object', $object_id)
                ->orderBy('name')
                ->select('id', 'name')->get()->toArray();
        }

        return [];
    }

    public function getMethodsByObjectIdToArray($object_id)
    {
        if ($object_id) {
            return Method::where('id_object', $object_id)
                ->orderBy('name')->select('id', 'name')
                ->pluck('name', 'id')->toArray();
        }

        return [];
    }

    public function isNameExists(string $name)
    {
        return HomeObject::where('name', $name)->exists();
    }

    public function getObjectsArray(): array
    {
        return HomeObject::orderBy('name')->select('id', 'name')->get()->toArray();
    }

    public function getObjects()
    {
        return HomeObject::orderBy('name')->get();
    }
}
<?php

namespace App\Services;

use App\Models\HomeObject;
use App\Models\Method;

class ObjectService {

    public function delete(int $id)
    {
        return HomeObject::destroy($id);
    }

    public function prepareObject(HomeObject $object, array $data)
    {
        $object->type = trim($data['type']);
        $object->name = trim($data['name']);
        $object->view = $data['view'] ?? null;
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
}
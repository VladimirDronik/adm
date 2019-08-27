<?php

namespace App\Services;

use App\Models\HomeObject;

class SceneService {

    public function delete(int $id)
    {
        return Scene::destroy($id);
    }

    // todo
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
}
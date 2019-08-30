<?php

namespace App\Services;

use App\Models\Method;
use App\Models\Script;

class MethodService {

    public function delete(int $id)
    {
        return Method::destroy($id);
    }

    public function store(array $data)
    {
        $method = empty($data['id']) ? new Method() : Method::find((int)$data['id']);

        $method->id_object = (int)$data['object_id'];
        $method->name = trim($data['name']);
        $method->script = empty($data['script_id']) ? null : (int)$data['script_id'];
        $method->comment = trim($data['comment']) === '' ? $method->name : trim($data['comment']);

        $method->save();

        $script_name = $method->script ? optional(Script::find($method->script))->name : '';

        return ['id' => $method->id, 'script_name' => $script_name];
    }
}
<?php

namespace App\Services;

use App\Models\Script;

class ScriptService
{
    public function delete(int $id)
    {
        return Script::destroy($id);
    }

    public function prepareScript(Script $script, array $data)
    {
        $script->system = 0;
        $script->count = 0;
        $script->name = trim($data['name']);
    }

    public function store(array $data)
    {
        $script = new Script();
        $this->prepareScript($script, $data);
        $script->save();

        return $script->id;
    }
}
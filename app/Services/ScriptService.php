<?php

namespace App\Services;

use App\Models\Script;

class ScriptService
{
    /**
     * @return int
     */
    public function delete(int $id)
    {
        $script = Script::find($id);

        if ($script->isLinkExists()) {
            $script->deleteFile();
        }

        return Script::destroy($id);
    }

    /**
     * @throws \Exception
     */
    public function prepareScript(Script $script, array $data)
    {
        $script->system = 0;
        $script->count = 0;
        $script->name = trim($data['name']);
        $script->storeCodeToFile(trim($data['code']));
    }

    /**
     * @return int
     *
     * @throws \Exception
     */
    public function store(array $data)
    {
        $script = new Script();
        $this->prepareScript($script, $data);
        $script->save();

        return $script->id;
    }

    public function update(Script $script, array $data)
    {
        if ($script->system) {
            return $script->id;
        }

        $script->name = trim($data['name']);
        $script->updateCodeToFile(trim($data['code']));
        $script->save();

        return $script->id;
    }
}

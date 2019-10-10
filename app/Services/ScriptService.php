<?php

namespace App\Services;

use App\Models\Script;
use Illuminate\Support\Facades\Storage;
use Exception;

class ScriptService
{
    public function delete(int $id)
    {
        return Script::destroy($id);
    }

    public function storeCodeToFile(string $name, string $code)
    {
        $path = 'scripts/';

        $name = mb_strtolower($name, 'UTF-8');
        $name = preg_replace('/\s\s+/', ' ', $name);
        $name = translitRussian($name);
        $name = str_replace(' ','_', $name);

        $count = 1;
        $filename = $name.'.php';
        while (Storage::disk('local')->exists($path . $filename)) {
            $filename = $name.'_'.$count.'.php';
            $count++;
            if ($count > 1000) {
                throw new Exception('Не удалось сохранить файл');
            }
        }

        Storage::disk('local')->put($path . $filename, $code);

        return $filename;
    }

    public function prepareScript(Script $script, array $data)
    {
        $script->system = 0;
        $script->count = 0;
        $script->name = trim($data['name']);
        $script->link = $this->storeCodeToFile($script->name, trim($data['code']));
    }

    public function store(array $data)
    {
        $script = new Script();
        $this->prepareScript($script, $data);
        $script->save();

        return $script->id;
    }
}
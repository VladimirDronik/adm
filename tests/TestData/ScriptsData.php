<?php

namespace Tests\TestData;

use App\Models\Script;

class ScriptsData
{
    /**
     * Генератор сущностей для скрипта
     */
    public function generateScript(): array
    {
        $script = Script::create([
            'name' => 'Тестовый скрипт',
            'link' => 'test_script',
            'count' => 0,
            'system' => 0,
            'enable' => 1,
        ]);

        return [
            'script' => $script,
        ];
    }
}

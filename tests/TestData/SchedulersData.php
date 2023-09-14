<?php

namespace Tests\TestData;

use App\Models\SchedulerTask;
use App\Models\Script;

class SchedulersData
{
    /**
     * Генератор сущностей для задачи
     *
     * @return array
     */
    public function generateScheduler(): array
    {
        $script = Script::create([
            'name' => 'Тестовый скрипт',
            'link' => 'test_script',
            'count' => 0,
            'system' => 0,
            'enable' => 1,
        ]);

        $scheduler = SchedulerTask::create([
            'name' => 'Тестовая задача',
            'script' => $script->id,
            'is_system' => 0,
            'is_hidden' => 0,
            'active' => 1,
        ]);

        return [
            'script' => $script,
            'scheduler' => $scheduler,
        ];
    }
}

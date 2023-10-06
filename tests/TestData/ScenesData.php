<?php

namespace Tests\TestData;

use App\Models\Scene;

class ScenesData
{
    /**
     * Генератор сущностей для сцены
     */
    public function generateScene(): array
    {
        $scene = Scene::create([
            'label' => 'Тестовая сцена',
            'image' => 'IMG_6339.jpg',
            'background_color' => '#E9E9F0',
            'sort' => 1,
            'active' => 1,
        ]);

        return [
            'scene' => $scene,
        ];
    }
}

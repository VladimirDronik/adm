<?php

namespace Tests\TestData;

use App\Models\View;

class ViewsData
{
    /**
     * Генератор сущностей для отображений
     *
     * @return array
     */
    public function generateView(): array
    {
        $view = View::create([
            'description' => 'Тестовое отображение',
            'type' => 'dimmer',
            'sort' => 1,
            'active' => 1,
            'status' => 'on',
            'icon' => 'noimage',
        ]);

        return [
            'view' => $view,
        ];
    }
}

<?php

namespace Tests\TestData;

use App\Models\Elements;
use App\Models\Page;

class PagesData
{
    /**
     * Генератор сущностей для страницы
     *
     * @return array
     */
    public function generatePage(): array
    {
        $page = Page::create([
            'name' => 'Тестовая страница',
            'type' => Page::TYPE_2FIELD,
            'link' => 'test-page',
            'sort' => 1,
        ]);

        $element = Elements::create([
            'name' => 'Тестовый элемент',
            'type' => Elements::TYPE_LABEL,
            'image' => 'noimage.png',
            'value' => '[{"status":null,"wh_color":"#187306","bl_color":"#00ffbb"}]',
            'page' => $page->id,
            'parent' => 0,
            'position' => 1,
            'sort' => 1,
            'active' => 1,
        ]);

        return [
            'element' => $element,
            'page' => $page,
        ];
    }
}

<?php

namespace Tests\TestData;

use App\Models\Menu;

class MenuData
{
    /**
     * Генератор сущностей для меню
     */
    public function generateMenu(): array
    {
        $menuGroup = Menu::create([
            'name' => 'Тестовая группа меню',
            'title' => 'test-menu-group',
            'link' => 'test-menu-group',
            'image' => 'noimage.png',
            'parent' => 0,
            'sort' => 1,
            'active' => 1,
        ]);

        $menu = Menu::create([
            'name' => 'Тестовое меню',
            'title' => 'test-menu',
            'link' => 'test-menu',
            'image' => 'noimage.png',
            'parent' => $menuGroup->id,
            'sort' => 1,
            'active' => 1,
        ]);

        return [
            'menu_group' => $menuGroup,
            'menu' => $menu,
        ];
    }
}

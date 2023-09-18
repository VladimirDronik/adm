<?php

namespace Tests\TestData;

use App\Models\User;

class DevUsersData
{
    /**
     * Генератор сущностей для пользователя
     */
    public function generateDevUser(): array
    {
        $devUser = User::create([
            'name' => 'Тестовый пользователь',
            'dev_id' => 1,
            'def_scene' => 0,
        ]);

        return [
            'dev_user' => $devUser,
        ];
    }
}

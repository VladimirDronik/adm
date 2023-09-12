<?php

namespace Tests\TestData;

use App\Models\Camera;
use App\Models\Room;

class CamerasData
{
    /**
     * Генератор сущностей для камеры
     *
     * @return array
     */
    public function generateCamera(): array
    {
        $room = Room::create([
            'name' => 'Тестовая комната',
            'image' => 'noimage.png',
            'style' => 'grey',
            'sort' => 1,
            'is_group' => 0,
        ]);

        $camera = Camera::create([
            'name' => 'Тестовая камера',
            'type' => 'ivideon',
            'sort' => 1,
            'active' => 1,
            'image' => 'https://openapi-alpha.ivideon.com/cameras/100-Oa52xERnVUudwQ9FBwhaa5:0/live_preview?op=GET&access_token=public',
            'link' => 'https://open.ivideon.com/embed/v2/?server=100-Oa52xERnVUudwQ9FBwhaa5&camera=0',
            'room' => $room->id,
        ]);

        return [
            'room' => $room,
            'camera' => $camera,
        ];
    }
}

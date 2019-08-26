<?php

namespace App\Services;

class ImageService {

    const ROOM_PATH = 'images/rooms';
    const VIEW_PATH = 'images/views_items';

    /**
     * Вывод изображений для всех помемещний
     *
     * @return array;
     *
     */
    public static function getRoomImages()
    {
        return array_diff(scandir(self::ROOM_PATH), ['..', '.']);
    }

    /**
     * Вывод изображений для всех отображений
     *
     * @return array;
     *
     */
    public static function getViewImages()
    {
        return array_diff(scandir(self::VIEW_PATH), ['..', '.']);
    }
}
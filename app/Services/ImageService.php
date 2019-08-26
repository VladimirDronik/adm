<?php

namespace App\Services;

class ImageService {

    const ROOM_PATH = 'ela/images/rooms';
    const VIEW_PATH = 'ela/images/views_items';
    const NO_IMAGE_PATH = 'ela/images/rooms/noimage.png';

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
        $images = array_diff(scandir(self::VIEW_PATH), ['..', '.']);

        foreach($images as &$image) {
            $image = self::VIEW_PATH.'/'.$image;
        }

        return $images;
    }
}
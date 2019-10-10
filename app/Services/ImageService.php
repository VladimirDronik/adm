<?php

namespace App\Services;

class ImageService {

    const ROOM_PATH = 'ela/images/rooms';
    const VIEW_PATH = 'ela/images/views_items';
    const SCENE_PATH = 'ela/images/scenes';
    const MENU_PATH = 'ela/images/menu';
    const NO_IMAGE_PATH = 'ela/images/rooms/noimage.png';

    public static function getImages(string $path)
    {
        return array_values(array_diff(scandir($path), ['..', '.']));
    }

    /**
     * Вывод изображений для всех помемещний
     *
     * @return array;
     *
     */
    public static function getRoomImages()
    {
        return self::getImages(self::ROOM_PATH);
    }

    /**
     * Вывод изображений для всех отображений
     *
     * @return array;
     *
     */
    public static function getViewImages()
    {
        $images = self::getImages(self::VIEW_PATH);

        foreach($images as &$image) {
            $image = self::VIEW_PATH.'/'.$image;
        }

        return $images;
    }

    public static function getNoImageName()
    {
        return basename(self::NO_IMAGE_PATH);
    }

    public static function getSceneImages()
    {
        $images = self::getImages(self::SCENE_PATH);

        foreach($images as &$image) {
            $image = self::SCENE_PATH.'/'.$image;
        }

        return $images;
    }
}
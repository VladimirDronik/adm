<?php

namespace App\Services;

use App\Models\Camera;
use Illuminate\Support\Facades\DB;

class CameraService
{
    public function prepare(Camera $camera, array $data)
    {
        $camera->name = $data['name'];
        $camera->link = $data['link'];
        $camera->room = $data['room'];
        $camera->image = $data['image'];
        $camera->type = 'ivideon';
        $camera->active = array_key_exists('active', $data);
    }

    /**
     * Создание камеры
     */
    public function store(array $data): int
    {
        $camera = new Camera();

        $imageUrl = $this->parseIdAndNumberFromUrl($data['link']);
        $data['image'] = $imageUrl;

        $this->prepare($camera, $data);
        $camera->sort = Camera::max('sort') + 1;
        $camera->save();

        return $camera->id;
    }

    /**
     * Изменение камеры
     */
    public function update(Camera $camera, array $data): int
    {
        $this->prepare($camera, $data);

        $camera->save();

        return $camera->id;
    }

    /**
     * Изменение активности камеры
     *
     * @return bool
     */
    public function changeActive(int $id, int $active)
    {
        Camera::where('id', $id)->update(['active' => $active]);

        return true;
    }

    /**
     * Удалить камеру
     *
     * @return bool
     */
    public function delete(int $id)
    {
        return Camera::destroy($id);

        return true;
    }

    private function updatePreviousSortRoom($camera, $previous_sort)
    {
        Camera::where('sort', $camera->sort)->update(['sort' => $previous_sort]);
    }

    public function sort(array $data)
    {
        $camera = Camera::findOrFail($data['id']);

        $min = Camera::min('sort');
        $max = Camera::max('sort');

        if (($camera->sort === $min && $data['direction'] === 'up')
            || ($camera->sort === $max && $data['direction'] === 'down')) {
            return true;
        }

        $previous_sort = $camera->sort;
        $camera->sort += $data['direction'] === 'up' ? -1 : 1;

        DB::transaction(function () use ($camera, $previous_sort) {
            $this->updatePreviousSortRoom($camera, $previous_sort);
            $camera->save();
        });

        return true;
    }

    /**
     * Распарсить полученную ссылку на камеру и получить id и номер для вывода изображения
     */
    private function parseIdAndNumberFromUrl(string $url): ?string
    {
        if (! filter_var($url, FILTER_VALIDATE_URL)) {
            return null;
        }

        $components = parse_url($url);
        parse_str($components['query'], $queryParameters);

        if (! isset($queryParameters['server']) || ! isset($queryParameters['camera'])) {
            return null;
        }

        $serverId = $queryParameters['server'];
        $cameraNumber = $queryParameters['camera'];

        return 'https://openapi-alpha.ivideon.com/cameras/'.
            $serverId.':'.$cameraNumber.
            '/live_preview?op=GET&access_token=public';
    }
}

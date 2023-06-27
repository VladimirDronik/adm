<?php

namespace App\Services;

use App\Models\Camera;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class CameraService
{
    public function prepare(Camera $camera, array $data)
    {
        $camera->name = $data['name'];
        $camera->link = $data['link'];
        $camera->room = $data['room'];
        $camera->type = 'ivideon';
        $camera->sort = Camera::max('sort') + 1;
        $camera->active = array_key_exists('active', $data);
    }

    /**
     * Создание камеры
     *
     * @param array $data
     * @param UploadedFile $image
     * @return int
     */
    public function store(array $data, UploadedFile $image): int
    {
        $camera = new Camera();

        $this->prepare($camera, $data);

        $path = Storage::disk('custom')->put("img/cameras", $image);
        $fullPath = asset($path);
        $camera->image = $fullPath;

        $camera->save();

        return $camera->id;
    }

    /**
     * Изменение камеры
     *
     * @param Camera $camera
     * @param array $data
     * @param null|UploadedFile $image
     * @return int
     */
    public function update(Camera $camera, array $data, ?UploadedFile $image): int
    {
        $this->prepare($camera, $data);

        if ($image) {
            $path = Storage::disk('custom')->put("img/cameras", $image);
            $fullPath = asset($path);
            $camera->image = $fullPath;
        }

        $camera->save();

        return $camera->id;
    }

    /**
     * Изменение активности камеры
     *
     * @param int $id
     * @param int $active
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
     * @param int $id
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
}

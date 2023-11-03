<?php

namespace App\Services;

use App\Models\Camera;
use App\Models\Recorder;
use Illuminate\Support\Facades\DB;

class CameraService
{
    public function prepare(Camera $camera, array $data)
    {
        $camera->name = $data['name'];
        $camera->link = $data['link'];
        $camera->recorder_id = array_key_exists('recorder_id', $data) ? $data['recorder_id'] : null;
        $camera->room = array_key_exists('room', $data) ? $data['room'] : null;
        $camera->image = array_key_exists('image', $data) ? $data['image'] : null;
        $camera->type = array_key_exists('type', $data) ? $data['type'] : null;
        $camera->active = array_key_exists('active', $data);
    }

    /**
     * Создание камеры
     */
    public function store(array $data): int
    {
        $camera = new Camera();

        if ($data['type'] == 'ivideon') {
            $imageUrl = $this->parseIdAndNumberFromUrl($data['link']);
            $data['image'] = $imageUrl;
        }

        $this->prepare($camera, $data);
        $camera->sort = array_key_exists('sort', $data) ? $data['sort'] : Camera::whereNull('recorder_id')->max('sort') + 1;
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

    private function updatePreviousSortCamera(Camera $camera, int $previousSort, ?Recorder $cameraRecorder)
    {
        if ($cameraRecorder) {
            $cameraRecorder->cameras()
                ->where('sort', $camera->sort)
                ->update(['sort' => $previousSort]);
        } else {
            Camera::whereNull('recorder_id')
                ->where('sort', $camera->sort)
                ->update(['sort' => $previousSort]);
        }
    }

    public function sort(array $data)
    {
        $camera = Camera::findOrFail($data['id']);

        if ($camera->recorder) {
            $recorder = $camera->recorder;
            $tab = 'recorder' . $recorder->id;
            $min = $recorder->cameras->min('sort');
            $max = $recorder->cameras->max('sort');
        } else {
            $tab = 'cameras';
            $camerasWithoutRecorders = Camera::whereNull('recorder_id');
            $min = $camerasWithoutRecorders->min('sort');
            $max = $camerasWithoutRecorders->max('sort');
        }

        if (($camera->sort === $min && $data['direction'] === 'up')
            || ($camera->sort === $max && $data['direction'] === 'down')) {
            return ['result' => true, 'tab' => $tab];
        }

        $previousSort = $camera->sort;
        $camera->sort += $data['direction'] === 'up' ? -1 : 1;

        DB::transaction(function () use ($camera, $previousSort) {
            $this->updatePreviousSortCamera($camera, $previousSort, $camera->recorder);
            $camera->save();
        });

        return ['result' => true, 'tab' => $tab];
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

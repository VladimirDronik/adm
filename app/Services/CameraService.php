<?php

namespace App\Services;

use App\Models\Camera;
use App\Models\Recorder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class CameraService
{
    public function prepare(Camera $camera, array $data)
    {
        switch ($data['vendor']) {
            case Camera::VENDOR_IVIDEON:
                $camera->name = $data['name'];
                $camera->link = $data['link'];
                $camera->vendor = $data['vendor'];
                $camera->active = array_key_exists('active', $data);
                $camera->type = Camera::TYPE_DIRECT_LINK;
                break;
            case Camera::VENDOR_HIKVISION_HIWATCH:
                $camera->name = $data['name'];
                $camera->vendor = $data['vendor'];
                $camera->type = Camera::TYPE_MEDIA_SERVER;
                $recorder = Recorder::create([
                    'name' => 'Видеорегистратор камеры - ' . $data['name'],
                    'vendor' => $data['vendor'],
                    'sort' => Recorder::max('sort') + 1,
                    'ip_address' => $data['ip_address'],
                    'login' => $data['login'],
                    'password' => customEncrypt($data['password'], config('secret.password_key')),
                ]);
                $camera->link = 'rtsp://$login:$password@$ip_address/ISAPI/Streaming/channels/101';
                $camera->recorder_id = $recorder->id;
                $camera->active = array_key_exists('active', $data);

                chdir(env('SERVER_FOLDER').'/scripts');
                exec('php get_rtsp_snapshots.php '.$recorder->id);
                break;
            case Camera::VENDOR_OTHER:
                $camera->name = $data['name'];
                $camera->vendor = $data['vendor'];
                $camera->type = Camera::TYPE_MEDIA_SERVER;
                $recorder = Recorder::create([
                    'name' => 'Видеорегистратор камеры - ' . $data['name'],
                    'vendor' => $data['vendor'],
                    'sort' => Recorder::max('sort') + 1,
                    'ip_address' => $data['ip_address'],
                    'login' => $data['login'],
                    'password' => customEncrypt($data['password'], config('secret.password_key')),
                ]);
                $camera->link = $data['link_rtsp'];
                $camera->recorder_id = $recorder->id;
                $camera->active = array_key_exists('active', $data);

                chdir(env('SERVER_FOLDER').'/scripts');
                exec('php get_rtsp_snapshots.php '.$recorder->id);
                break;
        }
    }

    /**
     * Создание камеры
     */
    public function store(array $data): int
    {
        $camera = new Camera();

        DB::transaction(function () use ($camera, $data) {
            $this->prepare($camera, $data);
            $camera->sort = Camera::max('sort') + 1;
            $camera->save();

            if ($data['vendor'] == Camera::VENDOR_IVIDEON) {
                $imageUrl = $this->parseIdAndNumberFromUrl($data['link']);
            } else {
                $imageUrl = 'ela/images/cameras_snapshots/camera' . $camera->id . '.jpeg';
            }

            $camera->update(['image' => $imageUrl]);
        });

        return $camera->id;
    }

    /**
     * Изменение камеры
     */
    public function update(Camera $camera, array $data): int
    {
        $camera->name = $data['name'];
        $camera->link = $data['link'];

        if (array_key_exists('image', $data)) {
            $camera->image = $data['image'];
        }

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
        $camera = Camera::find($id);

        if (!$camera) {
            return false;
        }

        if ($camera->type == Camera::TYPE_MEDIA_SERVER) {
            if ($active == 0) {
                try {
                    Http::delete('http://localhost:9997/v3/config/paths/delete/camera' . $camera->id);
                } catch (\Throwable $th) {
                }
            } else {
                $recorder = $camera->recorder;

                if ($recorder) {
                    $link = str_replace(
                        ['$login', '$password', '$ip_address'],
                        [$recorder->login, customDecrypt($recorder->password, config('secret.password_key')), $recorder->ip_address],
                        $camera->link
                    );

                    try {
                        Http::post('http://localhost:9997/v3/config/paths/add/camera' . $camera->id, [
                            'source' => $link,
                        ]);
                    } catch (\Throwable $th) {
                    }
                }
            }
        }

        $camera->update(['active' => $active]);

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

    private function updatePreviousSortCamera(Camera $camera, int $previousSort)
    {
        Camera::where('sort', $camera->sort)
            ->update(['sort' => $previousSort]);
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

        $previousSort = $camera->sort;
        $camera->sort += $data['direction'] === 'up' ? -1 : 1;

        DB::transaction(function () use ($camera, $previousSort) {
            $this->updatePreviousSortCamera($camera, $previousSort);
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

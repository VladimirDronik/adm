<?php

namespace App\Services;

use App\Models\Recorder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class RecorderService
{
    public function __construct(
        private CameraService $cameraService
    ) {
    }

    public function prepare(Recorder $recorder, array $data)
    {
        $recorder->name = $data['name'];
        $recorder->ip_address = $data['ip_address'];
        $recorder->login = $data['login'];

        if (array_key_exists('vendor', $data) && $data['vendor']) {
            $recorder->vendor = $data['vendor'];
        }

        $password = null;

        if (array_key_exists('new_password', $data) && $data['new_password']) {
            $password = $data['new_password'];
        } elseif (array_key_exists('password', $data)) {
            $password = $data['password'];
        }

        if ($password) {
            $recorder->password = Hash::make(strval($password));
        }
    }

    /**
     * Создание видеорегистратора
     */
    public function store(array $data): int
    {
        $recorder = new Recorder();
        $this->prepare($recorder, $data);
        $recorder->sort = Recorder::max('sort') + 1;

        DB::transaction(function () use ($recorder, $data) {
            $recorder->save();

            $cameraData = [
                'type' => $data['vendor'],
                'recorder_id' => $recorder->id,
                'active' => 1,
            ];

            for ($i=1; $i <= $data['number_of_cameras']; $i++) {
                $cameraData['sort'] = $i;
                $cameraData['name'] = 'Камера ' . $i;
                $cameraData['link'] = 'rtsp://'. $data['login'] .':'. $data['password'] .'@'. $data['ip_address'] .'/ISAPI/Streaming/channels/'. $i .'01';
                $this->cameraService->store($cameraData);
            }
        });

        return $recorder->id;
    }

    /**
     * Изменение видеорегистратора
     */
    public function update(Recorder $recorder, array $data): int
    {
        $this->prepare($recorder, $data);
        DB::transaction(function () use ($recorder, $data) {
            $recorder->save();

            foreach ($recorder->cameras as $camera) {
                if (array_key_exists('new_password', $data) && $data['new_password']) {
                    $newLink = preg_replace('/rtsp:\/\/(.*):(.*@)([^\/]+)(.*)/', 'rtsp://'. $data['login'] .':'. $data['new_password'] .'@'. $data['ip_address'] .'$4', $camera->link);
                } else  {
                    $newLink = preg_replace('/rtsp:\/\/(.*):(.*)@([^\/]+)(.*)/', 'rtsp://'. $data['login'] .':$2@'. $data['ip_address'] .'$4', $camera->link);
                }
                $camera->link = $newLink;
                $camera->save();
            }
        });

        return $recorder->id;
    }

    /**
     * Удалить видеорегистратор
     *
     * @return bool
     */
    public function delete(int $id)
    {
        return Recorder::destroy($id);

        return true;
    }

    private function updatePreviousSortRecorder(Recorder $recorder, int $previousSort)
    {
        Recorder::where('sort', $recorder->sort)
            ->update(['sort' => $previousSort]);
    }

    public function sort(array $data)
    {
        $recorder = Recorder::findOrFail($data['id']);

        $min = Recorder::min('sort');
        $max = Recorder::max('sort');

        if (($recorder->sort === $min && $data['direction'] === 'up')
            || ($recorder->sort === $max && $data['direction'] === 'down')) {
            return true;
        }

        $previousSort = $recorder->sort;
        $recorder->sort += $data['direction'] === 'up' ? -1 : 1;

        DB::transaction(function () use ($recorder, $previousSort) {
            $this->updatePreviousSortRecorder($recorder, $previousSort);
            $recorder->save();
        });

        return true;
    }
}

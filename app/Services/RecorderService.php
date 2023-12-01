<?php

namespace App\Services;

use App\Models\Camera;
use App\Models\Recorder;
use File;
use Illuminate\Support\Facades\DB;

class RecorderService
{
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
            $recorder->password = customEncrypt($password, config('secret.password_key'));
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
                'vendor' => $data['vendor'],
                'type' => Camera::TYPE_MEDIA_SERVER,
                'recorder_id' => $recorder->id,
                'active' => 1,
            ];

            for ($i=1; $i <= $data['number_of_cameras']; $i++) {
                $cameraData['name'] = 'Камера ' . $i;
                $cameraData['sort'] = Camera::max('sort') + 1;

                switch ($data['vendor']) {
                    case Recorder::VENDOR_HIKVISION_HIWATCH:
                        $cameraData['link'] = 'rtsp://$login:$password@$ip_address/ISAPI/Streaming/channels/'. $i .'01';
                        break;
                    case Recorder::VENDOR_OTHER:
                        $cameraData['link'] = null;
                        break;
                }

                $camera = Camera::create($cameraData);
                $camera->update(['image' => 'ela/images/cameras_snapshots/camera' . $camera->id . '.jpeg']);
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
        $recorder->save();

        return $recorder->id;
    }

    /**
     * Удалить видеорегистратор
     *
     * @return bool
     */
    public function delete(int $id)
    {
        $recorder = Recorder::findOrFail($id);

        if ($recorder->cameras->isNotEmpty()) {
            foreach ($recorder->cameras as $camera) {
                if ($camera->image) {
                    File::delete($camera->image);
                }
            }
        }

        $recorder->delete();

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

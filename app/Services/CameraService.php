<?php

namespace App\Services;

use App\Models\Camera;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class CameraService
{
    public function prepare(Camera $camera, array $data)
    {
        $camera->name = $data['name'];
        $camera->link = $data['link'];
        $camera->room_id = $data['room_id'];
        $camera->type = 'ivideon';
        $camera->sort = $data['sort'];
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
     * @return int
     */
    // public function update(Conditioner $conditioner, array $data): int
    // {
    //     $kind = $conditioner->conditionerModel->conditionerKind->id;

    //     if (array_key_exists('code', $data)) {
    //         if ($data['temp'] == 'off') {
    //             $conditionerCode = $this->conditionersRep
    //                 ->getOffCode((int)$kind, (string)$data['temp']);
    //             $this->conditionersRep
    //                 ->updateOrCreate($conditionerCode ?: null, (string)$data['code'], (int)$kind, null, null, null, true);
    //         } else {
    //             $conditionerCode = $this->conditionersRep
    //                 ->getCode((int)$kind, (string)$data['operationMode'], (string)$data['fanMode'], (float)$data['temp']);
    //             $this->conditionersRep
    //                 ->updateOrCreate($conditionerCode ?: null, (string)$data['code'], (int)$kind, (string)$data['operationMode'], (string)$data['fanMode'], (float)$data['temp']);
    //         }
    //     }

    //     $conditioner->id_object = $data['id_object'];
    //     $conditioner->id_room = $data['id_room'];
    //     $conditioner->device_id = $data['device_id'];
    //     $conditioner->wb_mir = $data['wb_mir'];

    //     $conditioner->save();

    //     return $conditioner->id;
    // }

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
}

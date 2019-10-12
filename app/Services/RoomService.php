<?php

namespace App\Services;

use App\Models\Room;
use App\Models\Temperature;
use Illuminate\Support\Facades\DB;

class RoomService {

    public function delete(int $id)
    {
        $room = Room::find($id);

        if (!$room) {
            return false;
        }

        DB::transaction(function () use ($room) {
            Room::where('sort','>', max($room->sort, 0))->update([
                'sort' => DB::raw('sort-1'),
            ]);
            $room->delete();
        });

        return true;
    }

    public function sort(array $data)
    {
        $room = Room::find($data['id']);

        if (!$room) {
            return false;
        }

        $min = Room::where('id','>',0)->min('sort');
        $max = Room::where('id','>',0)->max('sort');

        if (($room->sort === $min && $data['direction'] === 'up')
            || ($room->sort === $max && $data['direction'] === 'down')) {
            return true;
        }

        $previous_sort = $room->sort;
        $room->sort += $data['direction'] === 'up' ? -1 : 1;

        DB::transaction(function () use ($room, $previous_sort) {
            Room::where('sort', $room->sort)->update(['sort' => $previous_sort]);
            $room->save();
        });

        return true;
    }

    private function setNameIfEmpty($name)
    {
        if (empty($name)) {
            return 'Без названия';
        }

        return $name;
    }

    private function setImageIfEmpty($image)
    {
        if (empty($image)) {
            return ImageService::getNoImageName();
        }

        return $image;
    }

    private function setColorIfEmpty($color)
    {
        if (empty($color)) {
            return optional(ColorService::getAll()[0])->name ?? 'red';
        }

        return $color;
    }

    public function store(array $data)
    {
        $room = new Room();

        $room->sort = Room::max('sort') + 1;

        array_walk($data, function (&$value) { $value = trim($value); });

        $room->name = $this->setNameIfEmpty($data['name']);
        $room->image = $this->setImageIfEmpty($data['image']);
        $room->style = $this->setColorIfEmpty($data['style']);

        $room->save();

        return $room->id;
    }

    public function updateName(int $id, string $name)
    {
        Room::where('id', $id)->update(['name' => $this->setNameIfEmpty($name)]);
    }

    public function updateImage(int $id, string $image)
    {
        Room::where('id', $id)->update(['image' => $this->setImageIfEmpty($image)]);
    }

    public function updateColor(int $id, string $color)
    {
        Room::where('id', $id)->update(['style' => $this->setColorIfEmpty($color)]);
    }

    public function update(Room $room, array $data)
    {
        DB::transaction(function() use ($room, $data) {

            $room->lighting = (int)$data['lighting'];
            $room->save();

            $temperature = Temperature::where('id_room', $room->id)->first();

            if (!$temperature) {
                $temperature = new Temperature();
                $temperature->id_room = $room->id;
                $temperature->sort = 1;
            }

            $temperature->normal = (int)$data['temperature_normal'];
            $temperature->night = (int)$data['temperature_night'];
            $temperature->eco = (int)$data['temperature_eco'];

            $temperature->save();
        });

        return $room->id;
    }
}
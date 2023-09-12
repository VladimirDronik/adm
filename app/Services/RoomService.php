<?php

namespace App\Services;

use App\Models\Room;
use App\Models\Temperature;
use App\Models\View;
use Illuminate\Support\Facades\DB;

class RoomService
{

    public function delete(int $id)
    {
        $room = Room::find($id);

        if (!$room) {
            return false;
        }

        DB::transaction(function () use ($room) {
            if ($room->is_group || $room->is_separate_room) {
                if ($room->is_group) {
                    Room::room()->where('group_room', $room->id)->delete();
                }
                Room::where(function ($query) {
                    $query->group()
                        ->orWhere(function ($query) {
                            $query->room()->whereNull('group_room');
                        });
                })->where('sort', '>', max($room->sort, 0))->update([
                    'sort' => DB::raw('sort-1'),
                ]);
            } else {
                Room::room()->where('group_room', $room->group_room)
                    ->where('sort', '>', max($room->sort, 0))->update([
                    'sort' => DB::raw('sort-1'),
                ]);
            }
            $room->delete();
        });

        return true;
    }

    private function getSortMin($room): int
    {
        if ($room->is_group || $room->is_separate_room) {
            return (int) Room::group()
                ->orWhere(function ($query) {
                    $query->room()->whereNull('group_room');
                })->min('sort');
        }

        return (int) Room::room()->where('group_room', $room->group_room)->min('sort');
    }

    private function getSortMax($room): int
    {
        if ($room->is_group || $room->is_separate_room) {
            return (int) Room::group()
                ->orWhere(function ($query) {
                    $query->room()->whereNull('group_room');
                })->max('sort');
        }

        return (int) Room::room()->where('group_room', $room->group_room)->max('sort');
    }

    private function updatePreviousSortRoom($room, $previous_sort)
    {
        if ($room->is_group || $room->is_separate_room) {
            Room::where(function ($query) {
                $query->group()
                    ->orWhere(function ($query) {
                        $query->room()->whereNull('group_room');
                    });
            })->where('sort', $room->sort)->update(['sort' => $previous_sort]);
        } else {
            Room::room()->where('group_room', $room->group_room)
                ->where('sort', $room->sort)->update(['sort' => $previous_sort]);
        }
    }

    public function sort(array $data)
    {
        $room = Room::find($data['id']);

        if (!$room) {
            return false;
        }

        $min = $this->getSortMin($room);
        $max = $this->getSortMax($room);

        if (($room->sort === $min && $data['direction'] === 'up')
            || ($room->sort === $max && $data['direction'] === 'down')) {
            return true;
        }

        $previous_sort = $room->sort;
        $room->sort += $data['direction'] === 'up' ? -1 : 1;

        DB::transaction(function () use ($room, $previous_sort) {
            $this->updatePreviousSortRoom($room, $previous_sort);
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
            $colors = ColorService::getAll();

            if ($colors->isEmpty()) {
                return 'red';
            }

            return $colors->first()->name;
        }

        return $color;
    }

    private function storeGroup(array $data)
    {
        $room = new Room();

        $room->is_group = 1;
        $room->group_room = null;

        $room->sort = $this->getSortMax($room) + 1;

        array_walk($data, function (&$value) {
            $value = trim($value);
        });

        $room->name = $this->setNameIfEmpty($data['name']);
        $room->image = $this->setImageIfEmpty($data['image']);
        $room->style = $this->setColorIfEmpty($data['style']);

        $room->save();

        return $room->id;
    }

    private function storeRoom(array $data)
    {
        $group = null;
        if ($data['group_id'] !== '0') {
            $group = Room::group()->where('id', $data['group_id'])->first();
        }
        $room = new Room();

        $room->is_group = 0;
        $room->group_room = $group ? $group->id : null;

        $room->sort = $this->getSortMax($room) + 1;

        array_walk($data, function (&$value) {
            $value = trim($value);
        });

        $room->name = $this->setNameIfEmpty($data['name']);
        $room->image = $this->setImageIfEmpty($data['image']);
        $room->style = $this->setColorIfEmpty($data['style']);

        $room->save();


        $temperature = new Temperature();
        $temperature->id_room = $room->id;
        $temperature->normal = 0.00;
        $temperature->night = 0.00;
        $temperature->eco = 0.00;
        $temperature->sort = 1;

        $temperature->save();

        return $room->id;
    }

    public function store(array $data)
    {
        if ($data['type'] === 'group') {
            return $this->storeGroup($data);
        }

        return $this->storeRoom($data);
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
        DB::transaction(function () use ($room, $data) {

            if (is_null($room->group_room) && $data['group_room'] !== '0') {
                // из отдельных в конкретную
                Room::where(function ($query) {
                    $query->group()
                        ->orWhere(function ($query) {
                            $query->room()->whereNull('group_room');
                        });
                })->where('sort', '>', $room->sort)->update([
                    'sort' => DB::raw('sort-1'),
                ]);
                View::where('room', $room->id)->update(['room_group' => (int)$data['group_room']]);
                $room->group_room = $data['group_room'];
            } elseif (!is_null($room->group_room) && $data['group_room'] === '0') {
                // из конкретных в отдельную
                Room::room()->where('group_room', $room->group_room)->where('sort', '>', $room->sort)->update([
                    'sort' => DB::raw('sort-1'),
                ]);
                View::where('room', $room->id)->update(['room_group' => $room->id]);
                $room->group_room = null;
            } elseif (!is_null($room->group_room) && $room->group_room !== (int)$data['group_room']) {
                // из конкретной в конкретную
                Room::room()->where('group_room', $room->group_room)->where('sort', '>', $room->sort)->update([
                    'sort' => DB::raw('sort-1'),
                ]);
                View::where('room', $room->id)->update(['room_group' => (int)$data['group_room']]);
                $room->group_room = (int)$data['group_room'];
            }

            $room->sort = $this->getSortMax($room) + 1;
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

    /**
     * Добавление термостата к комнате.
     */
    static public function addTermostat($idRoom, $termostatValue)
    {

        $temperature = Temperature::where('id_room', $idRoom)->first();

        if ($temperature->id) {

            if($temperature->normal == null)
                $temperature->normal = $termostatValue;

            if($temperature->night == null)
                $temperature->night = $termostatValue;

            if($temperature->eco == null)
                $temperature->eco = $termostatValue;

            $temperature->save();
        }

    }

    /**
     * Добавление гигростата к комнате.
     */
    static public function addHygrostat($idRoom, $hygrostatValue)
    {

//        $temperature = Temperature::where('id_room', $idRoom)->first();
//
//        if ($temperature->id) {
//
//            if($temperature->normal == null)
//                $temperature->normal = $termostatValue;
//
//            if($temperature->night == null)
//                $temperature->night = $termostatValue;
//
//            if($temperature->eco == null)
//                $temperature->eco = $termostatValue;
//
//            $temperature->save();
//        }

    }
}
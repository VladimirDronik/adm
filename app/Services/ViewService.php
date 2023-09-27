<?php

namespace App\Services;

use App\Models\Room;
use App\Models\View;
use Illuminate\Support\Facades\DB;

class ViewService {

    public function prepareView(View $view, array $data)
    {
        $view->title = trim($data['title']);
        $view->type = trim($data['type']);
        $view->scene = $data['scene'] ?? null;
        $view->position_top = (int)$data['position_top'];
        $view->position_left = (int)$data['position_left'];

        if($data['color'] != '') $view->color = $data['color'];
        else
            $view->color = NULL;

        $safeType = null;

        if (array_key_exists('safe_type', $data) && $data['safe_type']) {
            $safeType = 'auth='.$data['safe_type'];
        }

        if(trim($data['type']) == 'termostat') {

            $stringMethod = 'editable='.$data['enabletermostat'].';';
            $stringMethod.='lowval='.$data['lowval_termostat'].';';
            if ($safeType) {
                $stringMethod.='highval='.$data['highval_termostat'].';'.$safeType;
            } else {
                $stringMethod.='highval='.$data['highval_termostat'];
            }

            $data['params'] = $stringMethod;
        } elseif(trim($data['type']) == 'link') {
            if ($safeType) {
                $data['params'] = 'link='.$data['link'].';'.$safeType;
            } else {
                $data['params'] = 'link='.$data['link'];
            }
        } elseif(trim($data['type']) == 'label') {
            if ($safeType) {
                $data['params'] = "push={$data['pushlabel']}&modal={$data['modallabel']}&message={$data['label_longclick_text']}&$safeType";
            } else {
                $data['params'] = "push={$data['pushlabel']}&modal={$data['modallabel']}&message={$data['label_longclick_text']}";
            }
        } else {
            if ($safeType) {
                $data['params'] = $safeType;
            } else {
                $data['params'] = null;
            }
        }

        $view->room = ((int)$data['room'] === 0) ? null : (int)$data['room'];
        if (is_null($view->room)) {
            $view->room_group = null;
        } else {
            $room = Room::find($view->room);
            if (!$room->is_group && !$room->is_separate_room) {
                $view->room_group = $room->group_room;
            } else {
                $view->room_group = $view->room;
            }
        }

        $view->id_object = $data['id_object'] ?? null;
        $view->on_method = $data['id_method'] ?? null;
        $view->description = trim($data['description']);
        $view->status = 'off';
        $view->active = $data['active'] ?? 0;
        if (!$view->sort) {
            $view->sort = $this->getSortMax($view) + 1;
        }
        $view->icon = pathinfo($data['icon_image'], PATHINFO_FILENAME);
        $view->on_method_params = $data['on_method_params'];
        $view->params = $data['params'];

        if ($view->type === View::TYPE_SWITCH) {
            $view->off_method = $data['off_method'] ?? null;
            $view->off_method_params = $data['off_method_params'];
        } else {
            $view->off_method = null;
            $view->off_method_params = null;
        }
    }

    public function store(array $data)
    {
        $view = new View();
        $this->prepareView($view, $data);
        $view->save();

        return $view->id;
    }

    public function update(View $view, array $data)
    {
        $this->prepareView($view, $data);
        $view->save();

        return $view->id;
    }

    public function delete(int $id)
    {
        return View::destroy($id);
    }

    public function changeActive(int $id, int $active)
    {
        View::where('id', $id)->update(['active' => $active]);

        return true;
    }

    //

    private function getSortMin($view): int
    {
        return (int)View::where('room', $view->room)
            ->where('room_group', $view->room_group)->min('sort');
    }

    public function getSortMax($view): int
    {
        return (int)View::where('room', $view->room)
            ->where('room_group', $view->room_group)->max('sort');
    }

    private function updatePreviousSortView($view, $previous_sort)
    {
        View::where('room', $view->room)->where('room_group', $view->room_group)
            ->where('sort', $view->sort)->update(['sort' => $previous_sort]);
    }

    public function sort(array $data)
    {
        $view = View::find($data['id']);

        if (!$view) {
            return false;
        }

        $min = $this->getSortMin($view);
        $max = $this->getSortMax($view);

        if (($view->sort === $min && $data['direction'] === 'up')
            || ($view->sort === $max && $data['direction'] === 'down')) {
            return true;
        }

        $previous_sort = $view->sort;
        $view->sort += $data['direction'] === 'up' ? -1 : 1;

        DB::transaction(function () use ($view, $previous_sort) {
            $this->updatePreviousSortView($view, $previous_sort);
            $view->save();
        });

        return true;
    }
}
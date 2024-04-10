<?php

namespace App\Http\Controllers;

use App\Http\Requests\View\CreateRequest;
use App\Http\Requests\View\UpdateRequest;
use App\Models\ObjType;
use App\Models\View;
use App\Repositories\ColorRepository;
use App\Repositories\ObjectRepository;
use App\Repositories\PageRepository;
use App\Repositories\RoomRepository;
use App\Repositories\SceneRepository;
use App\Repositories\ViewRepository;
use App\Services\ImageService;
use App\Services\ObjectService;
use App\Services\ViewService;
use Illuminate\Http\Request;

class ViewController extends Controller
{
    public function __construct(
        private ViewRepository $view_rep,
        private RoomRepository $room_rep,
        private SceneRepository $scene_rep,
        private ViewService $service,
        private ObjectRepository $object_rep,
        private PageRepository $pages_rep,
        private ObjectService $object_service
    ) {
    }

    public function getLists()
    {
        $types = View::getFullTypeIds();
        $rooms = $this->room_rep->getAllToArray();
        $scenes = $this->scene_rep->getAll()->pluck('label', 'id')->toArray();
        $images = ImageService::getViewImages();
        $objects = $this->object_rep->getAllToArray();
        $links = $this->pages_rep->getAllToArray();
        $safeTypes = View::getFullSafeTypes();
        $relatedParameterObjects = $this->object_rep->getAllByTypes([
            ObjType::TYPE_TERMOSTAT,
            ObjType::TYPE_LIGHTSTAT,
            ObjType::TYPE_HYGROSTAT,
            ObjType::TYPE_CARBMONOXIDE,
            ObjType::TYPE_PRESSURESTAT,
            ObjType::TYPE_BOILER,
        ]);

        return [$types, $rooms, $objects, $scenes, $images, $links, $safeTypes, $relatedParameterObjects];
    }

    public function index(Request $r)
    {
        $views = $this->view_rep->getByRoom($r->room);
        $rooms = $this->room_rep->getSpecialRooms();

        $filter_room = $r->input('room', '');
        $filter_room_name = $this->room_rep->getRoomName($filter_room, $rooms);

        return view('views.index', compact('views', 'rooms', 'filter_room', 'filter_room_name'));
    }

    public function create()
    {
        [$types, $rooms, $objects, $scenes, $images, $links, $safeTypes, $relatedParameterObjects] = $this->getLists();

        $colors = ColorRepository::getNameTypeColors();

        return view('views.create', compact('types', 'rooms', 'objects', 'scenes', 'images', 'links', 'colors', 'safeTypes', 'relatedParameterObjects'));
    }

    public function store(CreateRequest $r)
    {
        try {
            if ($id = $this->service->store($r->except('_token'))) {
                return redirect()->route('views.edit', [$id])->with('success', 'Отображение успешно добавлено');
            }
        } catch (\Throwable $e) {
            \Log::error('Ошибка при добавлении отображения ', [$r->all(), $e->getMessage()]);
        }

        return back()->withInput($r->all())->with('error', 'Ошибка при добавлении отображения');
    }

    public function edit(View $view)
    {
        [$types, $rooms, , $scenes, $images, $links, $safeTypes, $relatedParameterObjects] = $this->getLists();
        $methods = $this->object_service->getMethodsByObjectIdToArray($view->id_object);

        $colors = ColorRepository::getNameTypeColors();

        $settingFromApp = null;
        $lowval = null;
        $highval = null;
        $pushlabel = null;
        $modallabel = null;
        $label_longclick_text = null;
        $link = null;
        $safe_type = null;

        if ($view->type == View::TYPE_TEMP || $view->type == View::TYPE_LIGHTSTAT || $view->type == View::TYPE_CARBMONOXIDE) {
            $onmethodparams = explode(';', $view->params);
            $settingFromApp = explode('=', $onmethodparams[0])[1];
            $lowval = explode('=', $onmethodparams[1])[1];
            $highval = explode('=', $onmethodparams[2])[1];
            if (array_key_exists(3, $onmethodparams)) {
                $safe_type = explode('=', $onmethodparams[3])[1];
            }
        } elseif ($view->type == View::TYPE_LABEL) {
            $onmethodparams = explode('&', $view->params);
            $pushlabel = explode('=', $onmethodparams[0])[1];
            $modallabel = explode('=', $onmethodparams[1])[1];
            $label_longclick_text = explode('=', $onmethodparams[2])[1];
            if (array_key_exists(3, $onmethodparams)) {
                $safe_type = explode('=', $onmethodparams[3])[1];
            }
        } elseif ($view->type == View::TYPE_LINK) {
            $params = explode(';', $view->params);
            $link = explode('=', $params[0])[1];
            if (array_key_exists(1, $params)) {
                $safe_type = explode('=', $params[1])[1];
            }
        } elseif ($view->params) {
            $safe_type = explode('=', $view->params)[1];
        }

        switch ($view->type) {
            case View::TYPE_TEMP:
                $lowvalSet = ['min' => 0, 'max' => 30];
                $highvalSet = ['min' => 0, 'max' => 50];
                break;
            case View::TYPE_LIGHTSTAT:
                $lowvalSet = ['min' => 0, 'max' => 100];
                $highvalSet = ['min' => 0, 'max' => 100];
                break;
            case View::TYPE_CARBMONOXIDE:
                $lowvalSet = ['min' => 400, 'max' => 2000];
                $highvalSet = ['min' => 400, 'max' => 2000];
                break;
            default:
                $lowvalSet = ['min' => null, 'max' => null];
                $highvalSet = ['min' => null, 'max' => null];
                break;
        }

        return view('views.edit', compact('view', 'types', 'safeTypes', 'link', 'lowvalSet', 'highvalSet',
            'rooms', 'methods', 'scenes', 'images', 'links', 'colors', 'safe_type', 'relatedParameterObjects',
            'settingFromApp', 'lowval', 'highval', 'pushlabel', 'modallabel', 'label_longclick_text'));
    }

    public function update(UpdateRequest $r, View $view)
    {
        try {
            if ($this->service->update($view, $r->except('_token'))) {
                return redirect()->route('views.edit', [$view->id])
                    ->with('success', 'Отображение успешно изменено');
            }
        } catch (\Throwable $e) {
            \Log::error('Ошибка при изменении отображения '.$view->id.' '
                .json_encode($r->all()).' '.$e->getMessage());
        }

        return back()->withInput($r->all())->with('error', 'Ошибка при изменении отображения');
    }
}

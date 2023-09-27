<?php

namespace App\Services;

use App\Models\HomeObject;
use App\Models\LedTape;
use App\Models\ObjType;
use App\Models\Port;
use App\Models\View;
use Illuminate\Support\Facades\DB;

class LedTapeService
{
    private $viewService;

    public function __construct(ViewService $viewService)
    {
        $this->viewService = $viewService;
    }

    /**
     * Создание led ленты
     *
     * @param array $data
     * @return int
     * @throws \Throwable
     */
    public function store(array $data): int
    {
        $ledTape = new LedTape();
        $ledTape->type = $data['type'];
        $ledTape->name = $data['name'];
        $ledTape->status = 'off';

        if ($ledTape->type != LedTape::TYPE_W) {
            $ledTape->h = 125;
            $ledTape->s = 50;
            $ledTape->v = 70;
        }

        if ($ledTape->type != LedTape::TYPE_RGB) {
            $ledTape->w = 50;
        }

        DB::transaction(function () use (&$ledTape, $data) {
            $unique_name = HomeObject::getUniqueObjectName(0, $ledTape->name);
            $object = new HomeObject();
            $object->type = ObjType::TYPE_TAPE;
            $object->name = $unique_name;
            $object->status = 'off';
            $object->is_system = 1;
            $object->save();

            $view = new View();
            $view->type = View::TYPE_TAPE;
            $view->icon = 'noimage';
            $view->description = 'Отображение для led ленты - '.$ledTape->name;
            $view->status = 'off';
            $view->sort = $this->viewService->getSortMax($view) + 1;
            $view->active = 1;
            $view->id_object = $object->id;
            $view->save();

            if ($ledTape->type == LedTape::TYPE_RGBW || $ledTape->type == LedTape::TYPE_RGB) {
                $portsIds = explode(',', $data['port_id']);
                foreach ($portsIds as $portId) {
                    Port::where('id', $portId)
                        ->update([
                            'object' => $object->id,
                            'comment' => $data['name'],
                            'status' => $ledTape->type,
                        ]);
                }
            } else {
                Port::where('id', $data['port_id'])
                    ->update([
                        'object' => $object->id,
                        'comment' => $data['name'],
                        'status' => LedTape::TYPE_W,
                    ]);
            }

            $ledTape->id_object = $object->id;
            $ledTape->save();
        });

        return $ledTape->id;
    }

    /**
     * Изменение led ленты
     *
     * @param LedTape $ledTape
     * @param array $data
     * @return int
     * @throws \Throwable
     */
    public function update(LedTape $ledTape, array $data): int
    {
        DB::transaction(function () use (&$ledTape, $data) {
            $newName = trim($data['name']);

            if ($ledTape->name != $newName) {
                $ledTape->object->name = HomeObject::getUniqueObjectName($ledTape->object->id, $newName);
                $ledTape->object->save();

                View::where('id_object', $ledTape->object->id)->update([
                    'description' => 'Отображение для led ленты - '.$newName,
                ]);
            }

            Port::where('object', $ledTape->object->id)
                ->update([
                    'object' => null,
                    'method' => null,
                    'status' => 'NC',
                    'comment' => '',
                ]);

            if ($ledTape->type == LedTape::TYPE_RGBW || $ledTape->type == LedTape::TYPE_RGB) {
                $portsIds = explode(',', $data['port_id']);
                foreach ($portsIds as $portId) {
                    Port::where('id', $portId)
                        ->update([
                            'object' => $ledTape->object->id,
                            'comment' => $data['name'],
                            'status' => $ledTape->type,
                        ]);
                }
            } else {
                Port::where('id', $data['port_id'])
                    ->update([
                        'object' => $ledTape->object->id,
                        'comment' => $data['name'],
                        'status' => LedTape::TYPE_W,
                    ]);
            }

            $ledTape->name = $newName;
            $ledTape->save();
        });

        return $ledTape->id;
    }

    /**
     * Удаление led ленты и связанных объектов.
     *
     * @param int $id
     * @return bool
     * @throws \Throwable
     */
    public function delete(int $id): bool
    {
        $ledTape = LedTape::findOrFail($id);

        DB::transaction(function () use (&$ledTape) {
            Port::where('object', $ledTape->object->id)
                ->update([
                    'object' => null,
                    'method' => null,
                    'status' => 'NC',
                    'comment' => ''
                ]);

            View::where('id_object', $ledTape->object->id)->delete();

            $ledTape->object()->delete();
        });

        return true;
    }
}
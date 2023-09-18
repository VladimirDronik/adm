<?php

namespace App\Services;

use App\Models\Method;
use App\Models\Port;
use App\Models\SchedulerTask;
use App\Models\Script;
use Illuminate\Support\Facades\DB;

class MethodService
{
    public function delete(int $id)
    {
        DB::transaction(function () use ($id) {
            Port::whereNotNull('method')->where('method', $id)
                ->update(['method' => null]);
            SchedulerTask::whereNotNull('method')->where('method', $id)->update(['method' => null, 'object' => null]);
            Method::destroy($id);
        });

        return true;
    }

    public function setTypeAction($method, $data)
    {
        if ($data['type'] === 'none') {
            $method->script = null;
            $method->easy = null;
        } elseif ($data['type'] === 'script') {
            $method->script = empty($data['script_id']) ? null : (int) $data['script_id'];
            $method->easy = null;
        } elseif ($data['type'] === 'easy') {
            $method->script = null;
            $method->easy = $data['device_id'].';'.$data['port'].':'.$data['action'];
        }
    }

    private function getMethodData($method)
    {
        return [
            'id' => $method->id,
            'script_id' => $method->script,
            'script_name' => $method->script ? optional(Script::find($method->script))->name : '',
            'name' => $method->name,
            'comment' => $method->comment,
            'easy' => empty($method->easy) ? '' : $method->easy,
            'device_id' => $method->device_id,
            'port' => $method->port,
            'action' => $method->action,
            'type' => $method->type,
        ];
    }

    public function store(array $data)
    {
        $method = empty($data['id']) ? new Method() : Method::find((int) $data['id']);

        $method->id_object = (int) $data['object_id'];
        $method->name = trim($data['name']);
        $method->comment = trim($data['comment']) === '' ? $method->name : trim($data['comment']);

        $this->setTypeAction($method, $data);

        $method->save();

        return $this->getMethodData($method);
    }
}

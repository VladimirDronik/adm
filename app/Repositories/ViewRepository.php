<?php

namespace App\Repositories;

use App\Models\View;
use Illuminate\Database\Eloquent\Collection;

class ViewRepository
{
    public function getAll(): Collection
    {
        return View::with('eroom', 'escene')
            ->orderBy('id')
            ->get();
    }

    public function getAllToArray(): array
    {
        $views = View::select('id', 'description')
            ->orderBy('description')
            ->pluck('description', 'id')
            ->toArray();

        return $views;
    }

    public function getByRoom(mixed $roomId, int $perPage = 50)
    {
        $query = View::with('eroom', 'escene', 'eobject', 'emethod');

        if ($roomId === '0') {
            $query->whereNull('room')->orderBy('sort');
        } elseif (! is_null($roomId)) {
            $query->where('room', $roomId)->orderBy('sort');
        } else {
            $query->orderBy('id');
        }

        return $query->paginate($perPage);
    }

    public function updateObject(array $data)
    {
        if (empty($data['id_object'])) {
            View::where('id', $data['id_view'])
                ->update([
                    'id_object' => null,
                    'on_method' => null,
                    'off_method' => null,
                    'on_method_params' => null,
                    'off_method_params' => null,
                ]);
        } else {
            View::where('id', $data['id_view'])
                ->update(['id_object' => $data['id_object']]);
        }
    }

    public function updateMethod(array $data)
    {
        if (empty($data['id_method'])) {
            View::where('id', $data['id_view'])
                ->update([
                    'on_method' => null,
                    'on_method_params' => null,
                ]);
        } else {
            if (empty($data['params'])) {
                View::where('id', $data['id_view'])
                    ->update([
                        'on_method' => $data['id_method'],
                        'on_method_params' => null,
                    ]);
            } else {
                View::where('id', $data['id_view'])
                    ->update([
                        'on_method' => $data['id_method'],
                        'on_method_params' => $data['params'],
                    ]);
            }
        }
    }

    public function updateOffMethod(array $data)
    {
        if (empty($data['id_method'])) {
            View::where('id', $data['id_view'])
                ->update([
                    'off_method' => null,
                    'off_method_params' => null,
                ]);
        } else {
            if (empty($data['params'])) {
                View::where('id', $data['id_view'])
                    ->update([
                        'off_method' => $data['id_method'],
                        'off_method_params' => null,
                    ]);
            } else {
                View::where('id', $data['id_view'])
                    ->update([
                        'off_method' => $data['id_method'],
                        'off_method_params' => $data['params'],
                    ]);
            }
        }
    }

    public static function getNameById($idView): ?View
    {
        return View::find($idView);
    }
}

<?php

namespace App\Services;

use App\Models\Count;

class CountService {

    public function delete(int $id)
    {
        return Count::destroy($id);
    }

    public function prepareCount(Count $count, array $data)
    {
        $count->name = trim($data['name']);
        $count->type = $data['type'];
        $count->id_object = (int)$data['id_object'];
        $count->impulse = (int)$data['impulse'];
        $count->unit = trim($data['unit']);
        $count->today_value = (int)($data['today_value'] ?? 0);
        $count->total_value = (int)($data['total_value'] ?? 0);
    }

    public function store(array $data)
    {
        $count = new Count();
        $this->prepareCount($count, $data);
        $count->save();

        return $count->id;
    }

    public function update(Count $count, array $data)
    {
        $this->prepareCount($count, $data);
        $count->save();

        return $count->id;
    }
}
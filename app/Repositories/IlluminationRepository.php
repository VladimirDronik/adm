<?php

namespace App\Repositories;

use App\Models\Lamp;
use App\Models\LedTape;
use App\Models\DaliDevice;
use Illuminate\Pagination\LengthAwarePaginator;

class IlluminationRepository
{
    public function getAll(int $perPage = 30)
    {
        $lamps = Lamp::get();
        $ledTapes = LedTape::get();
        $daliDevices = DaliDevice::get();

        $illuminations = $lamps->concat($ledTapes)->concat($daliDevices)->sortBy('id_object');

        $currentPage = request()->get('page') ?: 1;

        return new LengthAwarePaginator(
            $illuminations->forPage($currentPage, $perPage)->all(),
            $illuminations->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'pageName' => 'page']
        );
    }
}

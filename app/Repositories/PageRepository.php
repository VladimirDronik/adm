<?php

namespace App\Repositories;

use App\Models\Page;

class PageRepository
{
    public function getAll(int $perPage = 30)
    {
        return Page::select(
            'pages.id',
            'pages.name',
            'pages.link',
            'pages.type',
            \DB::raw('(SELECT count(elements.id) FROM elements WHERE elements.page = pages.id) AS countElements')
        )->paginate($perPage);
    }

    public function getAllToArray(): array
    {
        return Page::select('link', 'name')
            ->orderBy('name')
            ->pluck('name', 'link')
            ->toArray();
    }
}

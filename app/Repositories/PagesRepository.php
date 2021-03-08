<?php

namespace App\Repositories;

use App\Models\Pages;

class PagesRepository
{
    public function getAllToArray()
    {
        return Pages::select('link','name')->orderBy('name')->pluck('name', 'link')->toArray();
    }



}
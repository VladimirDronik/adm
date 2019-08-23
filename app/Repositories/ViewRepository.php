<?php

namespace App\Repositories;

use App\Models\View;

class ViewRepository {

    public function getAll()
    {
        return View::all();
    }
}
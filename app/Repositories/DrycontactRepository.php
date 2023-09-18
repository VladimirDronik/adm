<?php

namespace App\Repositories;

use App\Models\Drycontact;

class DrycontactRepository
{
    public function getAll($pagination_count = 30)
    {
        return Drycontact::with('object')->orderBy('id', 'desc')->paginate($pagination_count);
    }
}

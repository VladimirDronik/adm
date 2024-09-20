<?php

namespace App\Repositories;

use App\Models\Drycontact;

class DrycontactRepository
{
    public function getAll(int $perPage = 30)
    {
        return Drycontact::with('object')
            ->orderBy('id', 'desc')
            ->paginate($perPage);
    }
}

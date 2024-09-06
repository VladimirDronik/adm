<?php

namespace App\Services;

use App\Models\Logging;

class LogService
{
    public function changeActive(int $id, int $active)
    {
        Logging::where('id', $id)->update(['value' => $active]);

        return true;
    }
}

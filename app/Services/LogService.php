<?php
/**
 * Created by PhpStorm.
 * User: kinord
 * Date: 08.04.20
 * Time: 13:22
 */

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

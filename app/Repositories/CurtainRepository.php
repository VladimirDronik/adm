<?php
/**
 * Created by PhpStorm.
 * User: kinord
 * Date: 09.05.21
 * Time: 13:19
 */

namespace App\Repositories;

use App\Models\Curtain;

class CurtainRepository
{

    public function getAll(int $pagination_count = 30)
    {
        return Curtain::with('object')->orderBy('id', 'desc')
            ->paginate($pagination_count);
    }
}
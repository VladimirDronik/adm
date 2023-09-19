<?php
/**
 * Created by PhpStorm.
 * User: kinord
 * Date: 23.04.21
 * Time: 14:16
 */

namespace App\Repositories;

use App\Models\Action;

class ActionRepository
{
    /**
     * Отдает все доступные действия для выбранного события
     */
    public function getAllActionsByEvent($idEvent)
    {
        return Action::where('id_event', $idEvent)->get();
    }
}

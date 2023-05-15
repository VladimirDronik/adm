<?php
/**
 * Created by PhpStorm.
 * User: kinord
 * Date: 13.04.21
 * Time: 15:37
 */

namespace App\Services;

use App\Models\HomeObject;
use App\Models\Page;
use App\Repositories\MenuRepository;
use Illuminate\Support\Facades\DB;

class EngineeringService
{


    public function delete(int $idObject, bool $delMenuAndPages)
    {

        //HomeObject::deleteAutoObject(idObject);
        $menuRep = new MenuRepository();

        $object = HomeObject::findOrFail($idObject);

            DB::transaction(function () use (&$object, $idObject, $delMenuAndPages, $menuRep) {
                if ($delMenuAndPages) {
                    $boilerMenu = $menuRep->getByName('Котёл');
                    if ($boilerMenu) {
                        $boilerMenu->delete();
                    }

                    $parentMenu = $menuRep->getByName('Инженерное');
                    if ($parentMenu && $parentMenu->children->isEmpty()) {
                        $parentMenu->delete();
                    }

                    $page = Page::where('name', $object->name);
                    if ($page) {
                        $page->delete();
                    }
                }

                HomeObject::deleteAutoObject($idObject);
                $object->delete();
            });

        return true;

    }
}
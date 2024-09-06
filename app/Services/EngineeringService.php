<?php

namespace App\Services;

use App\Models\Page;
use App\Models\HomeObject;
use Illuminate\Support\Facades\DB;
use App\Repositories\MenuRepository;

class EngineeringService
{
    public function delete(int $idObject, bool $delMenuAndPages)
    {
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

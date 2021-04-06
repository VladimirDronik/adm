<?php
/**
 * Created by PhpStorm.
 * User: kinord
 * Date: 06.04.21
 * Time: 11:30
 */

namespace App\Services;
use App\Models\Pages;

class PageService
{

    public function updateName(int $id, string $name)
    {
        Pages::where('id', $id)->update(['name' => $this->setNameIfEmpty($name)]);
    }


    public function updateLink(int $id, string $link)
    {
        Pages::where('id', $id)->update(['link' => $this->setLinkIfEmpty($link)]);
    }


    private function setNameIfEmpty($name)
    {
        if (empty($name)) {
            return 'Без названия';
        }

        return $name;
    }


    private function setLinkIfEmpty($link)
    {
        if (empty($link)) {
            return 'Нет ссылки';
        }

        return $link;
    }


}
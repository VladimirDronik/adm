<?php
/**
 * Created by PhpStorm.
 * User: kinord
 * Date: 06.04.21
 * Time: 11:30
 */

namespace App\Services;
use App\Models\Page;
use App\Models\Elements;
use Illuminate\Support\Facades\DB;

class PageService
{

    public function updateName(int $id, string $name)
    {
        Page::where('id', $id)->update(['name' => $this->setNameIfEmpty($name)]);
    }


    public function updateLink(int $id, string $link)
    {
        Page::where('id', $id)->update(['link' => $this->setLinkIfEmpty($link)]);
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


    public function store(array $data)
    {

        $page = new Page();

        $page->name = $this->setNameIfEmpty($data['name']);
        $page->link = $this->setLinkIfEmpty($data['link']);
        $page->type = $data['type'];
        $page->sort = 1;

        $page->save();

        return $page->id;
    }
    


    public function delete($idPage)
    {
        $page = Page::find($idPage);

        DB::transaction(function () use ($page) {

            //Удаляем все элементы для страницы
            Elements::where('page', $page->id)->delete();

            $page->delete();
        });


        return true;
    }


}

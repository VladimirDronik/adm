<?php

namespace App\Services;

use App\Models\Menu;
use Illuminate\Support\Facades\DB;

class MenuService {

    public function sort(array $data)
    {
        $menu = Menu::find($data['id']);

        if (!$menu) {
            return false;
        }

        $min = Menu::min('sort');
        $max = Menu::max('sort');

        if (($menu->sort === $min && $data['direction'] === 'up')
            || ($menu->sort === $max && $data['direction'] === 'down')) {
            return true;
        }

        $previous_sort = $menu->sort;
        $menu->sort += $data['direction'] === 'up' ? -1 : 1;

        DB::transaction(function () use ($menu, $previous_sort) {
            Menu::where('sort', $menu->sort)->update(['sort' => $previous_sort]);
            $menu->save();
        });

        return true;
    }

    public function changeActive(int $id, int $active)
    {
        Menu::where('id', $id)->update(['active' => $active]);

        return true;
    }

    public function updateImage(int $id, string $image)
    {
        Menu::where('id', $id)->update(['image' => $this->setImageIfEmpty($image)]);
    }

    private function setImageIfEmpty($image)
    {
        if (empty($image)) {
            return ImageService::getNoImageName();
        }

        return $image;
    }

    public function updateName(int $id, string $name)
    {
        Menu::where('id', $id)->update(['title' => $this->setNameIfEmpty($name)]);
    }

    private function setNameIfEmpty($name)
    {
        if (empty($name)) {
            return 'Без названия';
        }

        return $name;
    }


    /**
     * Получение максимального индекса сортировки, который есть в таблице
     * @param $menu
     * @return int
     */
    private function getSortMax($menu): int
    {
        /*
        if ($menu->parent == 0) {
            return (int) Menu::group()
                ->orWhere(function ($query) {
                    $query->room()->whereNull('group_room');
                })->max('sort');
        }
*/
        return (int) Menu::where('parent', $menu->parent)->max('sort');
    }


    public function update(Menu $menu, array $data)
    {


        DB::transaction(function () use ($menu, $data) {


           // if (is_null($menu->parent) && $data['parent'] !== '0') {
                // из отдельных в конкретную
            unset($data['_method']);

            $menu->fill($data);
            //$menu->sort = $this->getSortMax($menu) + 1;
            $menu->save();

        });

        return $menu->id;
    }

    public function store(array $data)
    {
        if ($data['type'] === 'group') {
            return $this->storeGroup($data);
        }

        return $this->storeMenu($data);
    }


    private function storeGroup(array $data)
    {
        $menu = new Menu();

        $menu->sort = $this->getSortMax($menu) + 1;

        array_walk($data, function (&$value) {
            $value = trim($value);
        });

        $menu->name = $menu->title =$this->setNameIfEmpty($data['name']);
        $menu->image = $this->setImageIfEmpty($data['image']);
        $menu->active = 1;
        $menu->parent = 0;

        $menu->save();

        return $menu->id;
    }

    private function storeMenu(array $data)
    {

        $menu = new Menu();

        $menu->parent = $data['parent'];

        $menu->sort = $this->getSortMax($menu) + 1;



        $menu->name = $menu->title =$this->setNameIfEmpty($data['name']);
        $menu->image = $this->setImageIfEmpty($data['image']);
        $menu->link = $this->setImageIfEmpty($data['link']);
        $menu->active = 1;

        $menu->save();

        return $menu->id;
    }





    public function delete(int $id)
    {
        $menu = Menu::find($id);

        if (!$menu) {
            return false;
        }


        DB::transaction(function () use ($menu) {

            //Если выбран для удаления родительский пункт, то удаляем и дочерние
        if($menu->parent == 0)
            Menu::where('parent', $menu->id)->delete();

            $menu->delete();
        });

        return true;
    }
}
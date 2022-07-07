<?php
/**
 * Created by PhpStorm.
 * User: kinord
 * Date: 07.04.21
 * Time: 15:50
 */

namespace App\Services;


use App\Models\Elements;
use App\Models\Boiler;
use App\Models\InternalPage;
use Illuminate\Support\Facades\DB;


class ElementService
{
    public function updateName($id, $name)
    {
        Elements::where('id', $id)->update(['name' => $this->setNameIfEmpty($name)]);
    }

    public function store(array $data): int
    {
        $element = new Elements();

        $this->prepare($data, $element);

        $element->active = 1;
        $element->save();

        if (array_key_exists($data, 'handle')          &&
            ($data['handle'] == Boiler::PROP_WATER_TEMP ||
             $data['handle'] == Boiler::PROP_MANUALMODE ||
             $data['handle'] == Boiler::PROP_AUTOMODE))
        {
            InternalPage::create([
                'idElement' => $element->id,
            ]);
        }

        return $element->page;
    }

    private function prepare($data, Elements $element)
    {
        //unset($data['wh_color']);
        //unset($data['bl_color']);

        if(!$data['parent'] || $data['type'] == 'accordeon')
            $data['parent']  ='0';

        if(!$data['image'])
            $data['image'] = 'noimage.png';


        if($data['type'] == 'label') {
            $paramsArray = array(array('status'  => $data['value']));

            if (array_key_exists('settings', $data)) {
                $paramsArray[0]['settings'] = 'true';
            }

            $paramsArray[0]['wh_color'] = '#187306';
            $paramsArray[0]['bl_color'] = '#00ffbb';

            $data['value'] = json_encode($paramsArray, JSON_UNESCAPED_UNICODE);
        }
        else if($data['type'] == 'switch') {
            $paramsArray = array(array());

            if (array_key_exists('settings', $data)) {
                $paramsArray[0]['settings'] = 'true';
            }

            $paramsArray[0]['wh_color'] = '#187306';
            $paramsArray[0]['bl_color'] = '#00ffbb';

            $data['value'] = json_encode($paramsArray, JSON_UNESCAPED_UNICODE);
        }


        $data['sort'] = $this->getSortMax($data['page'], $data['parent'], $data['position'])+1;

        $element->fill($data);

    }

    private function getSortMax($page, $parent, $position): int
    {
        return (int) Elements::where('page', $page)
            ->where('parent', $parent)
            ->where('position', $position)
            ->max('sort');
    }


    private function setNameIfEmpty($name)
    {
        if (empty($name)) {
            return 'Без названия';
        }

        return $name;
    }




    public function delete(int $id)
    {
        $element = Elements::find($id);

        if (!$element) {
            return false;
        }


        DB::transaction(function () use ($element) {

            //Если выбран для удаления родительский пункт, то удаляем и дочерние
            if($element->parent == 0)
                Elements::where('parent', $element->id)->delete();

            if ($element->handle == Boiler::PROP_WATER_TEMP ||
                $element->handle == Boiler::PROP_AUTOMODE   ||
                $element->handle == Boiler::PROP_MANUALMODE)
            {
                InternalPage::where('idElement', $element->id)->delete();
            }

            $element->delete();

        });

        return true;

    }



    public function changeActive(int $id, int $active)
    {
        Elements::where('id', $id)->update(['active' => $active]);

        return true;
    }



    public function sort(array $data)
    {
        $element = Elements::find($data['id']);

        if (!$element) {
            return false;
        }

        $min = Elements::min('sort');
        $max = Elements::max('sort');

        if (($element->sort === $min && $data['direction'] === 'up')
            || ($element->sort === $max && $data['direction'] === 'down')) {
            return true;
        }

        $previous_sort = $element->sort;
        $element->sort += $data['direction'] === 'up' ? -1 : 1;

        DB::transaction(function () use ($element, $previous_sort) {
            Elements::where('sort', $element->sort)->update(['sort' => $previous_sort]);
            $element->save();
        });

        return true;
    }


    public function updateImage(int $id, string $image)
    {
        Elements::where('id', $id)->update(['image' => $this->setImageIfEmpty($image)]);
    }

    private function setImageIfEmpty($image)
    {
        if (empty($image)) {
            return ImageService::getNoImageName();
        }

        return $image;
    }

    public function update(Elements $element, array $data)
    {

        DB::transaction(function () use ($element, $data) {
            if (array_key_exists($data, 'handle') &&
                $data['handle'] != Boiler::PROP_WATER_TEMP &&
                $data['handle'] != Boiler::PROP_AUTOMODE   &&
                $data['handle'] != Boiler::PROP_MANUALMODE)
            {
                if ($element->handle == Boiler::PROP_WATER_TEMP ||
                    $element->handle == Boiler::PROP_AUTOMODE   ||
                    $element->handle == Boiler::PROP_MANUALMODE)
                {
                    InternalPage::where('idElement', $element->id)->delete();
                }
            }
            else {
                if ($element->handle != Boiler::PROP_WATER_TEMP &&
                    $element->handle != Boiler::PROP_AUTOMODE   &&
                    $element->handle != Boiler::PROP_MANUALMODE)
                {
                    InternalPage::create([
                        'idElement' => $element->id,
                    ]);
                }
            }

            $this->prepare($data, $element);

            $element->active = 1;
            $element->save();
        });

        return $element->id;
    }
}

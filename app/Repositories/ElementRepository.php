<?php
/**
 * Created by PhpStorm.
 * User: kinord
 * Date: 07.04.21
 * Time: 6:53
 */

namespace App\Repositories;

use App\Models\Elements;

class ElementRepository
{
    public function getAllByPage($idPage, $pagination_count = 30)
    {
        $elements = Elements::where('page', $idPage)
            ->where('parent', 0)
            ->orderBy('sort')
            ->paginate($pagination_count);

        foreach ($elements as $key => $element) {
            $element->value = $this->parser($element->value, 'status');
            $element->childs = null;

            if ($element->type == 'accordion') {
                $childs = Elements::where('page', $idPage)
                    ->where('parent', $element->id)
                    ->orderBy('sort')->get();

                if ($childs != null) {
                    $element->childs = $childs;
                }
            }
        }

        return $elements;
    }

    public function parser($valueToParsing, $key)
    {
        $inputArray = json_decode($valueToParsing, true);
        if (isset($inputArray[0][$key])) {
            $formattedStatus = $inputArray[0][$key];
        } else {
            $formattedStatus = '';
        }

        return $formattedStatus;
    }

    public function getParentsToArray($idPage)
    {
        return Elements::select('id', 'name')
            ->where('page', $idPage)
            ->where('type', 'accordion')
            ->orderBy('name')
            ->pluck('name', 'id')
            ->toArray();
    }
}

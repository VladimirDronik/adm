<?php

namespace App\Repositories;

use App\Models\Elements;

class ElementRepository
{
    public function getAllByPage(int $idPage, int $perPage = 30)
    {
        $elements = Elements::where('page', $idPage)
            ->where('parent', 0)
            ->orderBy('sort')
            ->paginate($perPage);

        foreach ($elements as $element) {
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

    public function parser(string $valueToParsing, $key)
    {
        $inputArray = json_decode($valueToParsing, true);
        if (isset($inputArray[0][$key])) {
            $formattedStatus = $inputArray[0][$key];
        } else {
            $formattedStatus = '';
        }

        return $formattedStatus;
    }

    public function getParentsToArray(int $idPage): array
    {
        return Elements::select('id', 'name')
            ->where('page', $idPage)
            ->where('type', 'accordion')
            ->orderBy('name')
            ->pluck('name', 'id')
            ->toArray();
    }
}

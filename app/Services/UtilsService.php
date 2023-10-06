<?php

namespace App\Services;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class UtilsService
{
    public function dataToTree(Collection $data): array
    {
        $tree = function ($elements, $parentId = 0) use (&$tree) {
            $branch = array();
            foreach ($elements as $element) {

                if ($element['parent_id'] == $parentId) {

                    $children = $tree($elements, $element['id']);
                    if ($children) {
                        $element['children'] = $children;
                    } else {
                        $element['children'] = [];
                    }
                    $branch[] = $element;
                }
            }

            return $branch;
        };

        return $tree($data);
    }
}

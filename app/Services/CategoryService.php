<?php

namespace App\Services;

use App\Models\Category;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class CategoryService
{
    /**
     * @param string|null $search
     * @return Collection
     */
    public function getCategories(?string $search): Collection
    {
        return Category::query()
            ->when(isset($search), fn(Builder $query) => $query->where('name', 'LIKE', "%{$search}%"))
            ->orderBy('name')
            ->get();
    }

    public function getCategoriesIds($category): ?array
    {
        if (!empty($category)) {
            $array = array($category->id);
            if (count($category->subcategories) == 0) {
                return $array;
            } else {
                return array_merge($array, $this->getChildrenIds($category->subcategories));
            }
        } else {
            return null;
        }
    }

    public function getChildrenIds($subcategories): array
    {
        $array = array();
        foreach ($subcategories as $subcategory) {
            array_push($array, $subcategory->id);
            if (count($subcategory->subcategories)) {
                $array = array_merge($array, $this->getChildrenIds($subcategory->subcategories));
            }
        }
        return $array;
    }
}

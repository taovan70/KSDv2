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
}

<?php

namespace App\Services;

use App\Models\Tag;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class TagService
{
    /**
     * @param string|null $search
     * @return Collection
     */
    public function getTags(?string $search): Collection
    {
        return Tag::query()
            ->when(isset($search), fn(Builder $query) => $query->where('name', 'LIKE', "%{$search}%"))
            ->orderBy('name')
            ->get();
    }
}
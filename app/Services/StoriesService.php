<?php

namespace App\Services;

use App\Models\Stories;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class StoriesService
{
    /**
     * @param string|null $search
     * @return Collection
     */
    public function getStories(?string $search): Collection
    {
        return Stories::query()
            ->when(isset($search), fn(Builder $query) => $query->where('name', 'LIKE', "%{$search}%"))
            ->orderBy('name')
            ->get();
    }
}

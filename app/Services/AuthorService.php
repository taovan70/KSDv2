<?php

namespace App\Services;

use App\Models\Author;
use App\Models\Tag;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Collection;

class AuthorService
{
    /**
     * @param string|null $search
     * @return Collection
     */
    public function getAuthors(?string $search): Collection
    {
        if (isset($search)) {
            return Author::query()
                ->when(isset($search), fn(Builder $query) => $query->where('name', 'LIKE', "%{$search}%"))
                ->orderBy('name')
                ->get();
        }

        return Author::all();
    }
}

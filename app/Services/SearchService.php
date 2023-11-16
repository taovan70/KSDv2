<?php

namespace App\Services;

use App\Models\Article;
use App\Models\Category;
use Illuminate\Support\Collection;

class SearchService
{
    public function search(string $searchTerm): Collection
    {
        $articlesContent = Article::search($searchTerm)->where('published', '1')->with('tags')->get();

        $articlesTitle = Article::query()
            ->where('title', 'LIKE', "%{$searchTerm}%")
            ->where('published', '1')
            ->with('tags')
            ->get();

        $articles =  $articlesContent->merge($articlesTitle);
        $groupedByCategory = $articles->groupBy('category_id');
        $result = $groupedByCategory->map(function (Collection $item, int $key) {
            $category = Category::find($key);
            return [
                'category' => $category,
                'articles' => $item,
                'articles_count' => $item->count()
            ];
        });

        return $result->values();
    }
}

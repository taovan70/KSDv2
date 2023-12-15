<?php

namespace App\Services;

use App\Models\Article;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class SearchService
{
    public function search(Request $request): array
    {
        $itemsPerPage = $request->items_per_page ?? 5;

        if (request('category_slug')) {
            $articlesPaginate = Article::search($request->q)
                ->where('published', '1')
                ->with('category:id,slug,mini_pic_path', 'tags:id', 'author:id,name,surname,middle_name','media')
                ->whereHas('category', function ($q) use ($request) {
                    $categories = explode(',', $request->category_slug);
                    $q->whereIn('slug', $categories);
                })
                ->paginate($itemsPerPage);

            return [
                'articles' => $articlesPaginate,
            ];
        } else {
            $articlesAll = Article::search($request->q)
                ->where('published', '1')
                ->with('category:id,slug,name,mini_pic_path')
                ->get();

            $articlesPaginate = Article::search($request->q)
                ->where('published', '1')
                ->with('category:id,slug,name,mini_pic_path', 'tags:id', 'author:id,name,surname,middle_name','media')
                ->paginate($itemsPerPage);

            $groupedByCategory = $articlesAll->groupBy('category');

            return [
                'categories' => array_values($groupedByCategory->map(function (Collection $item, string $key) {
                    return [
                        'category' => json_decode($key)->name,
                        'category_slug' => json_decode($key)->slug,
                        'articles_count' => $item->count()
                    ];
                })->toArray()),
                'articles' => $articlesPaginate,
            ];
        }
    }
}

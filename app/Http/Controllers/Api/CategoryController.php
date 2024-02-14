<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\api\Category\CategoryResource;
use App\Models\Category;
use App\Services\CategoryService;
use App\Services\UtilsService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class CategoryController extends Controller
{

    public function __construct(
        private readonly UtilsService $utilsService,
    ) {
    }
    public function fetchCategories(Request $request, CategoryService $service): Collection
    {
        return $service->getCategories($request->q);
    }

    public function fetchCategoriesAsTree(Request $request, CategoryService $service): array
    {
        $data =  $service->getCategories($request->q);
        return $this->utilsService->dataToTree($data);
    }

    public function show(string $slug)
    {
        $article = Category::where('slug', $slug)->with('children', function ($query) {
            $query->with('children', function ($query) {
                $query->with('children', function ($query) {
                    $query->with('children');
                });
            });
        })->first();
        if (!$article) {
            return [];
        }
        return new CategoryResource($article);
    }
}

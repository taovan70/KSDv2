<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\api\Category\CategoryResource;
use App\Models\Category;
use App\Services\CategoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class CategoryController extends Controller
{
    public function fetchCategories(Request $request, CategoryService $service): Collection
    {
       return $service->getCategories($request->q);
    }

    public function show(string $slug)
    {
        $article = Category::where('slug', $slug)->firstOrFail();
        return new CategoryResource($article);
    }
}

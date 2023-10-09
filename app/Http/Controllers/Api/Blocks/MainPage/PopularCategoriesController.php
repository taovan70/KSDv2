<?php

namespace App\Http\Controllers\Api\Blocks\MainPage;

use App\Http\Controllers\Controller;
use App\Http\Resources\api\Blocks\MainPage\PopularCategoriesResource;
use App\Models\Blocks\MainPage\PopularCategories;


class PopularCategoriesController extends Controller
{

    public function __construct()
    {
    }

    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $result = PopularCategories::with(['category' => function ($query) {
            $query->withCount('articles');
        }])
            ->orderBy('lft', 'ASC')
            ->get();
            
        return PopularCategoriesResource::collection($result);
    }
}

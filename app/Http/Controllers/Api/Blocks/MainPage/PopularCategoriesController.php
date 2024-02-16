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

    public function index()
    {
        $result = PopularCategories::with(['category' => function ($query) {
            $query->withCount('articles')->with(['children' => function ($query) {
                $query->withCount('articles');
            }]);
        }])
            ->orderBy('lft', 'ASC')
            ->get();


        foreach ($result as  $item) {
            $categoryCount = $item['category']['articles_count'];
            $categoryChildren = $item['category']['children'];
            foreach ($categoryChildren as $child) {
                $childCount = $child['articles_count'];
                $categoryCount += $childCount;
            }
            $item['category']['articles_count'] = $categoryCount;
        }
        
        return PopularCategoriesResource::collection($result);
    }
}

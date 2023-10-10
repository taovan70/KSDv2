<?php

namespace App\Http\Controllers\Api\Blocks\MainPage;

use App\Http\Controllers\Controller;
use App\Http\Resources\api\Blocks\MainPage\MostTalkedArticleResource;
use App\Http\Resources\api\Blocks\MainPage\PopularCategoriesResource;
use App\Models\Blocks\MainPage\MostTalkedArticle;
use App\Models\Blocks\MainPage\PopularCategories;


class MostTalkedArticlesController extends Controller
{

    public function __construct()
    {
    }

    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $result = MostTalkedArticle::with('article')
            ->orderBy('lft', 'ASC')
            ->get();

        return MostTalkedArticleResource::collection($result);
    }
}

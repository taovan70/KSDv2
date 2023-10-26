<?php

namespace App\Http\Controllers\Api\Blocks\MainPage;

use App\Http\Controllers\Controller;
use App\Http\Resources\api\Blocks\MainPage\MostTalkedArticleResource;
use App\Models\Blocks\MainPage\MostTalkedArticle;


class MostTalkedArticlesController extends Controller
{

    public function __construct()
    {
    }

    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $result = MostTalkedArticle::with(['article' => function ($query) {
            $query->where('published', 1);
        }])
            ->orderBy('lft', 'ASC')
            ->take(3)
            ->get();

        return MostTalkedArticleResource::collection($result);
    }
}

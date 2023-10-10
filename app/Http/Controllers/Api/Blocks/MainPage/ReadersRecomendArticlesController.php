<?php

namespace App\Http\Controllers\Api\Blocks\MainPage;

use App\Http\Controllers\Controller;
use App\Http\Resources\api\Blocks\MainPage\ReadersRecomendArticleResource;
use App\Models\Blocks\MainPage\ReadersRecomendArticle;


class ReadersRecomendArticlesController extends Controller
{

    public function __construct()
    {
    }

    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $result = ReadersRecomendArticle::with('article')
            ->orderBy('lft', 'ASC')
            ->get();

        return ReadersRecomendArticleResource::collection($result);
    }
}
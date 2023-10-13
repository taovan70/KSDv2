<?php

namespace App\Http\Controllers\Api\Blocks\MainPage;

use App\Http\Controllers\Controller;
use App\Models\Blocks\MainPage\BigCardArticle;
use App\Http\Resources\api\Blocks\MainPage\BigCardArticleResource;


class BigCardArticlesController extends Controller
{

    public function __construct()
    {
    }

    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $result = BigCardArticle::with('article')->get();

        return BigCardArticleResource::collection($result);
    }

    public function show(string $id)
    {
        $infoBlock = BigCardArticle::findOrFail($id);
        return new BigCardArticleResource($infoBlock);
    }
}

<?php

namespace App\Http\Controllers\Api\Blocks\NotFound;

use App\Http\Controllers\Controller;
use App\Http\Resources\api\Blocks\NotFound\PopularNotFoundArticlesResource;
use App\Models\Blocks\NotFound\PopularNotFoundArticles;


class PopularNotFoundArticlesController extends Controller
{

    public function __construct()
    {
    }

    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $result = PopularNotFoundArticles::with(['article' => function ($query) {
            $query->where('published', 1);
            $query->with('author');
            $query->with('category');
        }])->get();

        return PopularNotFoundArticlesResource::collection($result);
    }
}

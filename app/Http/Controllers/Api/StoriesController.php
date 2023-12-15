<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\api\Blocks\NotFound\PopularNotFoundTwoWeeksArticlesResource;
use App\Http\Resources\api\Stories\StoriesResource;
use App\Models\Stories;


class StoriesController extends Controller
{

    public function __construct()
    {
    }

    public function index(string $category_slug): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $result = Stories::whereHas('category', function ($q) use ($category_slug) {
                $q->where('slug', '=', $category_slug);
            })
            ->get();

        return StoriesResource::collection($result);
    }
}

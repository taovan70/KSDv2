<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\api\DidYouKnowInArticlesResource;
use App\Models\DidYouKnowInArticles;


class DidYouKnowInArticlesController extends Controller
{

    public function __construct()
    {
    }

    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        return DidYouKnowInArticlesResource::collection(DidYouKnowInArticles::all());
    }

    public function random(string $count, ? string $category_id = null): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        if($category_id) {
            $items = DidYouKnowInArticles::where('category_id', $category_id)->inRandomOrder()->take($count)->get();
        } else {
            $items = DidYouKnowInArticles::inRandomOrder()->take($count)->get();
        }
        return DidYouKnowInArticlesResource::collection($items);
    }

}

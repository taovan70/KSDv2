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

}

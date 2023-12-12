<?php

namespace App\Http\Controllers\Api\Blocks\Authors;

use App\Http\Controllers\Controller;
use App\Http\Resources\api\Blocks\Authors\PopularExpertArticlesResource;
use App\Models\Blocks\Authors\PopularExpertArticles;



class PopularExpertArticlesController extends Controller
{

    public function __construct()
    {
    }

    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $result = PopularExpertArticles::with(['article' => function ($query) {
            $query->where('published', 1);
            $query->with('author');
            $query->with('category');
        }])->paginate(6);

        return PopularExpertArticlesResource::collection($result);
    }
}

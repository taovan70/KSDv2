<?php

namespace App\Http\Controllers\Api\Blocks\NotFound;

use App\Http\Controllers\Controller;
use App\Http\Resources\api\Blocks\NotFound\PopularNotFoundCategoriesResource;
use App\Models\Blocks\NotFound\PopularNotFoundCategories;


class PopularNotFoundCategoriesController extends Controller
{

    public function __construct()
    {
    }

    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $result = PopularNotFoundCategories::with('category')->get();

        return PopularNotFoundCategoriesResource::collection($result);
    }
}

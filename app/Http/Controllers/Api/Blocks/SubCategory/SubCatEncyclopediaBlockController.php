<?php
namespace App\Http\Controllers\Api\Blocks\SubCategory;


use App\Http\Controllers\Controller;
use App\Http\Resources\api\Blocks\SubCategory\SubCatBehindTheScenesBlockResource;
use App\Http\Resources\api\Blocks\SubCategory\SubCatEncyclopediaBlockResource;
use App\Http\Resources\api\Blocks\SubCategory\SubCatGameOneBlockResource;
use App\Models\Blocks\SubCategory\SubCatBehindTheScenesBlock;
use App\Models\Blocks\SubCategory\SubCatEncyclopediaBlock;
use App\Models\Blocks\SubCategory\SubCatGameOneBlock;

class SubCatEncyclopediaBlockController extends Controller
{

    public function __construct()
    {
    }

    public function index(string $category_slug): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $result = SubCatEncyclopediaBlock::whereHas('category', function ($q) use ($category_slug) {
            $q->where('slug', '=', $category_slug);
        })->with(['article_one' => function ($query) {
            $query->where('published', 1);
        }])->with(['article_two' => function ($query) {
            $query->where('published', 1);
        }])->with(['article_three' => function ($query) {
            $query->where('published', 1);
        }])->with(['article_four' => function ($query) {
            $query->where('published', 1);
        }])->get();
        return SubCatEncyclopediaBlockResource::collection($result);
    }
}

<?php
namespace App\Http\Controllers\Api\Blocks\SubCategory;


use App\Http\Controllers\Controller;
use App\Http\Resources\api\Blocks\SubCategory\SubCatTopFactsBlockResource;
use App\Models\Blocks\SubCategory\SubCatTopFactsBlock;

class SubCatTopFactsBlockController extends Controller
{

    public function __construct()
    {
    }

    public function index(string $category_slug): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $result = SubCatTopFactsBlock::whereHas('category', function ($q) use ($category_slug) {
            $q->where('slug', '=', $category_slug);
        })->with(['article_one' => function ($query) {
            $query->where('published', 1);
        }])->with(['article_two' => function ($query) {
            $query->where('published', 1);
        }])->get();
        return SubCatTopFactsBlockResource::collection($result);
    }
}

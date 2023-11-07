<?php
namespace App\Http\Controllers\Api\Blocks\SubCategory;


use App\Http\Controllers\Controller;
use App\Http\Resources\api\Blocks\SubCategory\SubCatGameTwoBlockResource;
use App\Models\Blocks\SubCategory\SubCatGameTwoBlock;

class SubCatGameTwoBlockController extends Controller
{

    public function __construct()
    {
    }

    public function index(string $category_slug): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $result = SubCatGameTwoBlock::whereHas('category', function ($q) use ($category_slug) {
            $q->where('slug', '=', $category_slug);
        })->with(['article' => function ($query) {
            $query->where('published', 1);
        }])->get();
        return SubCatGameTwoBlockResource::collection($result);
    }
}

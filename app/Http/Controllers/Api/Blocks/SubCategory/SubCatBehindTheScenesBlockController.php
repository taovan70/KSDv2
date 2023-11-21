<?php
namespace App\Http\Controllers\Api\Blocks\SubCategory;


use App\Http\Controllers\Controller;
use App\Http\Resources\api\Blocks\SubCategory\SubCatBehindTheScenesBlockResource;
use App\Http\Resources\api\Blocks\SubCategory\SubCatGameOneBlockResource;
use App\Models\Blocks\SubCategory\SubCatBehindTheScenesBlock;
use App\Models\Blocks\SubCategory\SubCatGameOneBlock;

class SubCatBehindTheScenesBlockController extends Controller
{

    public function __construct()
    {
    }

    public function index(string $category_slug): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $result = SubCatBehindTheScenesBlock::whereHas('category', function ($q) use ($category_slug) {
            $q->where('slug', '=', $category_slug);
        })->with(['article' => function ($query) {
            $query->where('published', 1);
        }])->get();
        return SubCatBehindTheScenesBlockResource::collection($result);
    }
}

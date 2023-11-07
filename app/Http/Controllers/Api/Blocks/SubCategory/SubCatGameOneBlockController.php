<?php
namespace App\Http\Controllers\Api\Blocks\SubCategory;


use App\Http\Controllers\Controller;
use App\Http\Resources\api\Blocks\SubCategory\SubCatGameOneBlockResource;
use App\Models\Blocks\SubCategory\SubCatGameOneBlock;

class SubCatGameOneBlockController extends Controller
{

    public function __construct()
    {
    }

    public function index(string $category_slug): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $result = SubCatGameOneBlock::whereHas('category', function ($q) use ($category_slug) {
            $q->where('slug', '=', $category_slug);
        })->with(['article' => function ($query) {
            $query->where('published', 1);
        }])->get();
        return SubCatGameOneBlockResource::collection($result);
    }
}

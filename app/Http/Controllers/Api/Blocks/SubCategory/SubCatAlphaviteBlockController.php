<?php

namespace App\Http\Controllers\Api\Blocks\SubCategory;


use App\Http\Controllers\Controller;
use App\Models\Blocks\SubCategory\SubCatAlphaviteBlock;
use App\Http\Resources\api\Blocks\SubCategory\SubCatAlphaviteBlockResource;

class SubCatAlphaviteBlockController extends Controller
{

    public function __construct()
    {
    }

    public function index(string $category_slug): SubCatAlphaviteBlockResource
    {
        $result = SubCatAlphaviteBlock::whereHas('category', function ($q) use ($category_slug) {
            $q->where('slug', '=', $category_slug);
        })->firstOrFail();
        return new SubCatAlphaviteBlockResource($result);
    }
}

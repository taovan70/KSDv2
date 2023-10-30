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

    public function index(string $category_slug): SubCatGameTwoBlockResource
    {
        $result = SubCatGameTwoBlock::whereHas('category', function ($q) use ($category_slug) {
            $q->where('slug', '=', $category_slug);
        })->firstOrFail();
        return new SubCatGameTwoBlockResource($result);
    }
}

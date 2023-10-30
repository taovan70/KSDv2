<?php
namespace App\Http\Controllers\Api\Blocks\SubCategory;


use App\Http\Controllers\Controller;
use App\Http\Resources\api\Blocks\SubCategory\SubCatInterestingBlockResource;
use App\Models\Blocks\SubCategory\SubCatInterestingBlock;

class SubCatInterestingBlockController extends Controller
{

    public function __construct()
    {
    }

    public function index(string $category_slug): SubCatInterestingBlockResource
    {
        $result = SubCatInterestingBlock::whereHas('category', function ($q) use ($category_slug) {
            $q->where('slug', '=', $category_slug);
        })->firstOrFail();
        return new SubCatInterestingBlockResource($result);
    }
}

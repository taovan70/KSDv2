<?php
namespace App\Http\Controllers\Api\Blocks\SubCategory;


use App\Http\Controllers\Controller;
use App\Http\Resources\api\Blocks\SubCategory\SubCatExpertAdviceBlockResource;
use App\Models\Blocks\SubCategory\SubCatExpertAdvice;

class SubCatExpertAdviceBlockController extends Controller
{

    public function __construct()
    {
    }

    public function index(string $category_slug): SubCatExpertAdviceBlockResource
    {
        $result = SubCatExpertAdvice::whereHas('category', function ($q) use ($category_slug) {
            $q->where('slug', $category_slug);
        })->with('category.authors')->with('category.authors.categories')->firstOrFail();
        return new SubCatExpertAdviceBlockResource($result);
    }
}

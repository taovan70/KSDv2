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

    public function index(): SubCatInterestingBlockResource
    {
        $result = SubCatInterestingBlock::where('id', 1)->first();
        return new SubCatInterestingBlockResource($result);
    }
}

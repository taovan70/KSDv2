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

    public function index(): SubCatAlphaviteBlockResource
    {
        $result = SubCatAlphaviteBlock::where('id', 1)->first();
        return new SubCatAlphaviteBlockResource($result);
    }
}

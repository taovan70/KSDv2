<?php

namespace App\Http\Controllers\Api\Blocks\Category;


use App\Http\Controllers\Controller;
use App\Http\Resources\api\Blocks\Category\QACategoryResource;
use App\Models\Blocks\Category\QACategory;

class QACategoryController extends Controller
{

    public function __construct()
    {
    }

    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $result = QACategory::with('article')
            ->orderBy('lft', 'ASC')
            ->take(6)
            ->get();

        return QACategoryResource::collection($result);
    }
}

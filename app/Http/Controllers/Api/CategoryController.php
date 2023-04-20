<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\CategoryService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;

class CategoryController extends Controller
{
    public function fetchCategories(Request $request, CategoryService $service): Collection
    {
       return $service->getCategories($request->q);
    }
}

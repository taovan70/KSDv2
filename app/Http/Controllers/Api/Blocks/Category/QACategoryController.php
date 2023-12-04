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

    public function index(string $category_slug): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $result = QACategory::whereHas('category', function ($q) use ($category_slug) {
            $q->where('slug', '=', $category_slug);
        })->with(['article_one' => function ($query) {
            $query->where('published', 1);
            $query->with('author');
            $query->with('category');
            $query->with('media');
        }])->with(['article_two' => function ($query) {
            $query->where('published', 1);
            $query->with('author');
            $query->with('category');
            $query->with('media');
        }])->with(['article_three' => function ($query) {
            $query->where('published', 1);
            $query->with('author');
            $query->with('category');
            $query->with('media');
        }])->with(['article_four' => function ($query) {
            $query->where('published', 1);
            $query->with('author');
            $query->with('category');
            $query->with('media');
        }])->with(['article_five' => function ($query) {
            $query->where('published', 1);
            $query->with('author');
            $query->with('category');
            $query->with('media');
        }])->with(['article_six' => function ($query) {
            $query->where('published', 1);
            $query->with('author');
            $query->with('category');
            $query->with('media');
        }])->get();


        return QACategoryResource::collection($result);
    }
}

<?php

namespace App\Http\Controllers\Api\Blocks\Category;


use App\Http\Controllers\Controller;
use App\Http\Resources\api\Blocks\Category\EveryoneTalkingAboutResource;
use App\Models\Blocks\Category\EveryoneTalkingAbout;


class EveryoneTalkingAboutsController extends Controller
{

    public function __construct()
    {
    }

    public function index(string $category_slug): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $result = EveryoneTalkingAbout::whereHas('category', function ($q) use ($category_slug) {
            $q->where('slug', '=', $category_slug);
        })->with(['article_one' => function ($query) {
            $query->exclude(['content_html', 'content_markdown']);
            $query->where('published', 1);
            $query->with('author');
            $query->with('category');
        }])->with(['article_two' => function ($query) {
             $query->exclude(['content_html', 'content_markdown']);
            $query->where('published', 1);
            $query->with('author');
            $query->with('category');
        }])->with(['article_three' => function ($query) {
             $query->exclude(['content_html', 'content_markdown']);
            $query->where('published', 1);
            $query->with('author');
            $query->with('category');
        }])->with(['article_four' => function ($query) {
             $query->exclude(['content_html', 'content_markdown']);
            $query->where('published', 1);
            $query->with('author');
            $query->with('category');
        }])->with(['article_five' => function ($query) {
             $query->exclude(['content_html', 'content_markdown']);
            $query->where('published', 1);
            $query->with('author');
            $query->with('category');
        }])->with(['article_six' => function ($query) {
             $query->exclude(['content_html', 'content_markdown']);
            $query->where('published', 1);
            $query->with('author');
            $query->with('category');
        }])->get();

        return EveryoneTalkingAboutResource::collection($result);
    }
}

<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use App\Models\Author;
use App\Models\Category;
use App\Services\ArticleService;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Redirect;

class ArticleController extends Controller
{
    /**
     * @param Request $request
     * @param ArticleService $service
     * @return Collection
     */
    public function fetchAuthors(Request $request, ArticleService $service): Collection
    {
        return $service->getAuthorsByCategory($request);
    }

    public function show(Article $article): ArticleResource
    {
        $article->load('category', 'author', 'tags');
        return new ArticleResource($article);
    }

    /**
     * @param Request $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:500',
            'category' => 'required|numeric|exists:categories,id',
            'author' => 'required|numeric|exists:authors,id',
            'tag' => 'array|min:1',
            'tag.*' => 'numeric|exists:tags,id',
            'publish_date' => 'required|date',
            'content' => 'required|string|max:100000',
        ]);

        $article = Article::create($request->post());
        $category = Category::where(['id'=> $request->category])->firstOrFail();
        $author = Author::where(['id'=> $request->author])->firstOrFail();
        $category->articles()->save($article);
        $author->articles()->save($article);
        $article->tags()->sync($request->tag);

        return Redirect::back()->with([
            'data' => 'Something you want to pass to front-end',
        ]);
    }
}

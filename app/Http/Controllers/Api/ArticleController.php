<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Article\ArticleStoreRequest;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use App\Services\ArticleService;
use Illuminate\Http\RedirectResponse;
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

    /**
     * @param Article $article
     * @return ArticleResource
     */
    public function show(Article $article): ArticleResource
    {
        $article->load( 'tags');
        return new ArticleResource($article);
    }

    /**
     * @param ArticleStoreRequest $request
     * @return RedirectResponse
     */
    public function store(ArticleStoreRequest $request): RedirectResponse
    {
        $article = Article::create($request->post());
        $article->tags()->sync($request->tags);

        return Redirect::back()->with([
            'data' => 'Some data',
        ]);
    }

    /**
     * @param ArticleStoreRequest $request
     * @param Article $article
     * @return RedirectResponse
     */
    public function update(ArticleStoreRequest $request, Article $article): RedirectResponse
    {
        $article->fill($request->validated());
        $article->tags()->sync($request->tags);
        $article->save();

        return Redirect::back()->with([
            'data' => 'Some data',
        ]);
    }
}

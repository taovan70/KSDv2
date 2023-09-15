<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Article\ArticleStoreRequest;
use App\Http\Resources\api\ArticleResource;
use App\Models\Article;
use App\Services\ArticleService;
use App\Services\EmbedService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Redirect;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;


class ArticleController extends Controller
{

    public function __construct(
        private readonly EmbedService $embedService,
        private readonly ArticleService $articleService
    ) {
    }

    public function index(): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $articles = Article::all();
        return ArticleResource::collection($articles);
    }


    public function fetchAuthors(Request $request, ArticleService $service): Collection
    {
        return $service->getAuthorsByCategory($request);
    }


    public function show(Article $article): ArticleResource
    {
        $article->load('tags');
        return new ArticleResource($article);
    }


    public function store(ArticleStoreRequest $request): RedirectResponse
    {
        $article = Article::create($request->validated());
        try {
            $content = $this->articleService->convertImageUrls($request, $article);
        } catch (FileDoesNotExist|FileIsTooBig $e) {
            return Redirect::back()->withErrors($e->getMessage());
        }

        $article->content_markdown = $content;
        $article->content_html = $this->embedService->handleMarkdown($content);
        $article->save();

        $article->tags()->sync($request->tags);

        return Redirect::back()->with([
            'data' => 'Some data',
        ]);
    }


    public function update(
        ArticleStoreRequest $request,
        Article $article,
    ): RedirectResponse {
        $newData = $request->validated();
        try {
            $content = $this->articleService->convertImageUrls($request, $article);
            $newData['content_markdown'] = $this->embedService->stripTags($content);
        } catch (FileDoesNotExist|FileIsTooBig $e) {
            return Redirect::back()->withErrors($e->getMessage());
        }

        $article->content_html = $this->embedService->handleMarkdown($content);;
        $article->fill($newData);
        $article->tags()->sync($request->tags);
        $article->save();

        return Redirect::back()->with([
            'data' => 'Some data',
        ]);
    }
}

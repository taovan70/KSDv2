<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Article\ArticleStoreRequest;
use App\Http\Resources\ArticleResource;
use App\Models\Article;
use App\Services\ArticleService;
use App\Services\EmbedService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Redirect;
use League\CommonMark\Exception\CommonMarkException;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;
use Spatie\MediaLibrary\MediaCollections\Exceptions\MediaCannotBeDeleted;


class ArticleController extends Controller
{

    public function __construct(
        private readonly EmbedService $embedService,
        private readonly ArticleService $articleService
    ) {
    }

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
        $article->load('tags');
        return new ArticleResource($article);
    }

    /**
     * @param ArticleStoreRequest $request
     * @return RedirectResponse
     * @throws CommonMarkException
     * @throws MediaCannotBeDeleted
     */
    public function store(ArticleStoreRequest $request): RedirectResponse
    {
        $article = Article::create($request->validated());
        try {
            $content = $this->articleService->convertImageUrls($request, $article);
        } catch (FileDoesNotExist|FileIsTooBig $e) {
            return Redirect::back()->withErrors($e->getMessage());
        }

        $article->content_markdown = $content;
        $article->content_html = $this->embedService->convertToHtml($content);
        $article->save();

        $article->tags()->sync($request->tags);

        return Redirect::back()->with([
            'data' => 'Some data',
        ]);
    }

    /**
     * @param ArticleStoreRequest $request
     * @param Article $article
     * @return RedirectResponse
     * @throws CommonMarkException
     * @throws MediaCannotBeDeleted
     */
    public function update(
        ArticleStoreRequest $request,
        Article $article,
    ): RedirectResponse {
        $newData = $request->validated();
        try {
            $content = $this->articleService->convertImageUrls($request, $article);
            $newData['content_markdown'] = $this->articleService->stripTags($content);
        } catch (FileDoesNotExist|FileIsTooBig $e) {
            return Redirect::back()->withErrors($e->getMessage());
        }

        $article->content_html = $this->embedService->convertToHtml($content);;
        $article->fill($newData);
        $article->tags()->sync($request->tags);
        $article->save();

        return Redirect::back()->with([
            'data' => 'Some data',
        ]);
    }
}

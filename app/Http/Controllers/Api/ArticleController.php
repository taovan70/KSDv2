<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Article\ArticleStoreRequest;
use App\Http\Resources\api\Article\ArticleForBlocksResource;
use App\Http\Resources\api\Article\ArticleResource;
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
        $articles = Article::filter()->where('published', true)->with(['category', 'author', 'tags'])->get();
        return ArticleResource::collection($articles);
    }

    public function fetchArticles(Request $request, ArticleService $service): Collection
    {
        return $service->getArticles($request->q, $request->status);
    }


    public function fetchAuthors(Request $request, ArticleService $service): Collection
    {
        return $service->getAuthorsByCategory($request);
    }


    public function show(string $slug): ArticleResource
    {
        $article = Article::where('slug', $slug)->where('published', true)->with(['category', 'author', 'tags'])->firstOrFail();
        $article->load('tags');
        return new ArticleResource($article);
    }

    public function showPreview(string $id, string $key): ArticleResource
    {
        if (empty($key) || $key !== env('PREVIEW_ARTICLE_TOKEN')) {
            abort(404, "Sorry! Article not found");
        }
        $article = Article::where('id', $id)->with(['category', 'author', 'tags'])->firstOrFail();
        $article->load('tags');
        return new ArticleResource($article);
    }

    public function random(string $count): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {
        $articles = Article::inRandomOrder()->with(['category', 'author', 'tags', 'media'])->take($count)->where('published', true)->get();
        return ArticleResource::collection($articles);
    }

    public function recent(string $count): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {

        $articles = Article::filter()->latest()->with('category', 'author', 'tags')->take($count)->where('published', true)->get();

        return ArticleForBlocksResource::collection($articles);
    }

    public function recentPagination(Request $request): \Illuminate\Http\Resources\Json\AnonymousResourceCollection
    {

        $articles = Article::filter()->latest()->with('category', 'author', 'tags')->where('published', true)->paginate($request->items_per_page ?? 6);

        return ArticleForBlocksResource::collection($articles);
    }


    public function store(ArticleStoreRequest $request): RedirectResponse
    {
        $newData = $request->validated();
        $article = Article::create($newData);
        try {
            $content = $this->articleService->convertImageUrls($request, $article);
        } catch (FileDoesNotExist | FileIsTooBig $e) {
            return Redirect::back()->withErrors($e->getMessage());
        }

        $article->content_markdown = $content;
        $article->content_html = $this->embedService->handleMarkdown($content);
        if (isset($newData['mainPic'])) {
            $article->addMediaFromRequest('mainPic')->toMediaCollection('mainPic');
        }
        $article->save();

        $article->tags()->sync($request->tags);

        return Redirect::back()->with([
            'data' => 'Some data',
        ]);
    }

    public function preview(ArticleStoreRequest $request): RedirectResponse
    {
        $newData = $request->validated();
        $newData['slug'] = '';
        $newData['published'] = false;
        $article = Article::create($newData);
        try {
            $content = $this->articleService->convertImageUrls($request, $article);
        } catch (FileDoesNotExist | FileIsTooBig $e) {
            return Redirect::back()->withErrors($e->getMessage());
        }

        $article->content_markdown = $content;
        $article->content_html = $this->embedService->handleMarkdown($content);
        if (isset($newData['mainPic'])) {
            $article->addMediaFromRequest('mainPic')->toMediaCollection('mainPic');
        }
        $article->save();

        $article->tags()->sync($request->tags);

        return redirect()->route('article.make-article')->with('message', ['previewUrl' => env('FRONT_URL') . "/article-preview-" . $article->id]);
    }

    public function updatePreview(ArticleStoreRequest $request, Article $article,): RedirectResponse
    {
        $prevPreview = Article::where('preview_for', $article->id)->first();
        if ($prevPreview) {
            $prevPreview->delete();
        }
        $newData = $request->validated();
        $newData['slug'] = '';
        $newData['published'] = false;
        $newData['preview_for'] = $article->id;
        $article = Article::create($newData);
        try {
            $content = $this->articleService->convertImageUrls($request, $article);
        } catch (FileDoesNotExist | FileIsTooBig $e) {
            return Redirect::back()->withErrors($e->getMessage());
        }

        $article->content_markdown = $content;
        $article->content_html = $this->embedService->handleMarkdown($content);
        if (isset($newData['mainPic'])) {
            $article->addMediaFromRequest('mainPic')->toMediaCollection('mainPic');
        }
        $article->save();

        $article->tags()->sync($request->tags);

        return redirect()->route('article.make-article')->with('message', ['previewUrl' => env('FRONT_URL') . "/article-preview-" . $article->id]);
    }


    public function update(
        ArticleStoreRequest $request,
        Article $article,
    ): RedirectResponse {
        $newData = $request->validated();
        try {
            $content = $this->articleService->convertImageUrls($request, $article);
            $newData['content_markdown'] = $this->embedService->stripTags($content);
        } catch (FileDoesNotExist | FileIsTooBig $e) {
            return Redirect::back()->withErrors($e->getMessage());
        }

        $article->content_html = $this->embedService->handleMarkdown($content);
        if (isset($newData['mainPic'])) {
            $mainPics = $article->getMedia('mainPic');
            if (count($mainPics) > 0) {
                $mainPics[0]->delete();
            }
            $article->addMediaFromRequest('mainPic')->toMediaCollection('mainPic');
        }
        $article->fill($newData);
        $article->tags()->sync($request->tags);
        $article->save();

        return Redirect::back()->with([
            'data' => 'Some data',
        ]);
    }
}

<?php

namespace App\Services;

use App\Helpers\DOMParser\DOMParser;
use App\Helpers\DOMParser\DOMTags;
use App\Jobs\Article\SaveArticleImages;
use App\Models\Article;
use App\Models\ArticleElement;
use App\Models\Author;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;

class ArticleService
{
    private DOMParser $parser;
    private ArticleElementService $articleElementService;

    public function __construct()
    {
        $this->parser = new DOMParser();
        $this->articleElementService = app(ArticleElementService::class);
    }

    /**
     * @param Request $request
     * @return Collection
     */
    public function getAuthorsByCategory(Request $request): Collection
    {
        $search          = $request->input('q');
        $categoryField = Arr::first($request->form, function ($value) {
            return $value['name'] === 'category_id';
        });

        $authors =  Author::query()
            ->when(isset($categoryField['value']), function (Builder $query) use ($categoryField) {
                $query->whereHas('categories', function (Builder $query) use ($categoryField) {
                    $query->where('category_id', $categoryField['value']);
                });
            })
            ->when(isset($search), function (Builder $query) use ($search) {
                $query->where('name', 'LIKE', "%{$search}%")
                    ->orWhere('surname', 'LIKE', "%{$search}%")
                    ->orWhere('middle_name', 'LIKE', "%{$search}%");
            })
            ->get();

        if ($authors->isEmpty()) $authors = Author::all();

        return $authors->map(fn($author) => [
            'id' => $author->id,
            'fullName' => $author->fullName
        ]);
    }

    /**
     * @param Request $request
     * @param Article $article
     * @return void
     */
    public function parseArticle(Request $request, Article $article): void
    {
        $tags = $this->parser->parseContentOnTags($request->article_text);
        $tags = $this->parser->filterTagsForArticle($tags);

        foreach ($tags as $i => $tag) {
            $this->articleElementService->store($tag['tagName'], $tag['content'], $article->id, $i);
        }

        SaveArticleImages::dispatch($article);
    }

    /**
     * @param array $data
     * @return void
     */
    public function updateArticleElements(array $data): void
    {
        $article = Article::find($data['id']);

        $elementIds = [];
        foreach ($data['elements'] as $i => $element) {
            $tagName = $this->parser->getTagFromString($element['content']);

            if (isset($tagName)) {
                $articleElement = $this->articleElementService->store($tagName, $element['content'], $article->id, $i, $element['id']);
                $elementIds[] = $articleElement->id;
            }
        }

        $article->elements()->whereNotIn('id', $elementIds)->delete();
        SaveArticleImages::dispatch($article);
    }

    /**
     * @param Collection $articleElements
     * @return string
     */
    public function createArticleContent(Collection $articleElements): string
    {
        $content = '';

        foreach ($articleElements as $articleElement) {
            $content .= $articleElement->content . PHP_EOL;
        }

        return $content;
    }

    /**
     * @param Collection $articleHeaders
     * @return string
     * @throws \DOMException
     */
    public function createArticleStructure(Collection $articleHeaders): string
    {
        $structure = [];
        $parentH1 = null;
        $parentH2 = null;

        $articleHeaders = $articleHeaders->each(fn(ArticleElement $header) => $header->stripTags());

        foreach ($articleHeaders as $articleHeader) {
            if ($articleHeader->html_tag === DOMTags::HEADER_H1) {
                $structure[$articleHeader->content] = [];
                $parentH1 = $articleHeader->content;
                $parentH2 = null;

                continue;
            }

            if ($articleHeader->html_tag === DOMTags::HEADER_H2) {
                $parentH2 = $articleHeader->content;

                if (isset($parentH1) && isset($structure[$parentH1])) {
                    $structure[$parentH1][$parentH2] = [];
                } else {
                    $structure[$articleHeader->content] = [];
                }

                continue;
            }

            if ($articleHeader->html_tag === DOMTags::HEADER_H3) {
                if (isset($parentH1) && isset($structure[$parentH1])) {
                    if (isset($parentH2) && isset($structure[$parentH1][$parentH2])) {
                        $structure[$parentH1][$parentH2][$articleHeader->content] = [];
                    } else {
                        $structure[$parentH1][$articleHeader->content] = [];
                    }
                } else {
                    if (isset($parentH2) && isset($structure[$parentH2])) {
                        $structure[$parentH2][$articleHeader->content] = [];
                    } else {
                        $structure[$articleHeader->content] = [];
                    }
                }
            }
        }

        return $this->parser->createArticleStructure($structure);
    }
}

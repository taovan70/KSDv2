<?php

namespace App\Services;

use App\Helpers\DOMParser\DOMTags;
use App\Models\ArticleElement;
use DOMDocument;
use DOMElement;

class ArticleElementService
{
    /**
     * @param DOMElement $tag
     * @param DOMDocument $dom
     * @param int $articleId
     * @param int $order
     * @return void
     */
    public function store(DOMElement $tag, DOMDocument $dom, int $articleId, int $order): void
    {
        if (in_array($tag->tagName, DOMTags::PRESERVED_TAGS)) {
            ArticleElement::create([
                'article_id' => $articleId,
                'html_tag' => $tag->tagName,
                'content' => $dom->saveHTML($tag),
                'order' => $order
            ]);
        }
    }
}
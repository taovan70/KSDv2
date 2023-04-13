<?php

namespace App\Services;

use App\Models\ArticleElement;

class ArticleElementService
{
    /**
     * @param string $tagName
     * @param string $content
     * @param int $articleId
     * @param int $order
     * @param int|null $elementId
     * @return void|ArticleElement
     */
    public function store(string $tagName, string $content, int $articleId, int $order, int $elementId = null)
    {
        return ArticleElement::updateOrCreate(
            [
                'id' => $elementId
            ],
            [
                'article_id' => $articleId,
                'html_tag' => $tagName,
                'content' => $content,
                'order' => $order
            ]
        );
    }
}

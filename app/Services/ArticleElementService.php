<?php

namespace App\Services;

use App\Helpers\DOMParser\DOMParser;
use App\Helpers\FileOperator\ImageOperator;
use App\Models\Article;
use App\Models\ArticleElement;

class ArticleElementService
{
    private DOMParser $parser;

    public function __construct()
    {
        $this->parser = new DOMParser();
    }

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

    /**
     * @param Article $article
     * @return void
     */
    public function saveImages(Article $article): void
    {
        $images = $article->images;

        foreach ($images as $i => $image) {
            $imgDom    = $this->parser->getImgDom($image->content);
            $imagePath = ImageOperator::save($imgDom->getAttribute('src'), $article->imagesStoragePath, $i);

            if (isset($imagePath)) {
                $imgDom->setAttribute('src', $imagePath);

                $image->content = $imgDom->ownerDocument->saveHTML($imgDom);
                $image->save();
            }
        }
    }

    /**
     * @param Article $article
     * @return void
     */
    public function deleteImages(Article $article): void
    {
        $images = $article->images;

        if ($images->isNotEmpty()) {
            ImageOperator::deleteFolder("articles/{$article->id}");
        }
    }
}

<?php

namespace App\Helpers\DOMParser;

use App\Services\ArticleElementService;
use DOMDocument;
use DOMElement;
use DOMNodeList;
use DOMXPath;

class DOMParser
{
    private DOMDocument $dom;

    public function __construct()
    {
        $this->articleElementService = app(ArticleElementService::class);
        $this->dom = new DOMDocument();
    }

    /**
     * @param array $headers
     * @return string
     * @throws \DOMException
     */
    public function createArticleStructure(array $headers): string
    {
        $h1Ol = $this->createOlList($headers);
        $this->dom->appendChild($h1Ol);

        return $this->dom->saveHTML();
    }

    /**
     * @param array $headers
     * @return \DOMElement
     * @throws \DOMException
     */
    private function createOlList(array $headers): \DOMElement
    {
        $ol = $this->dom->createElement(DOMTags::LIST_OL);

        foreach ($headers as $header => $subHeaders) {
            $li = $this->dom->createElement(DOMTags::LIST_LI, $header);
            $ol->appendChild($li);

            if (count($subHeaders) > 0) {
                $subOl = $this->createOlList($subHeaders);
                $li->appendChild($subOl);
            }
        }

        return $ol;
    }

    /**
     * @param DOMElement $tag
     * @return bool|string
     */
    public function getHtmlString(DOMElement $tag): bool|string
    {
        return $this->dom->saveHTML($tag);
    }

    /**
     * @param string $articleText
     * @return DOMNodeList|false|mixed
     */
    public function parseContentOnTags(string $articleText): mixed
    {
        $this->dom->loadHTML($articleText);
        $xpath = new DOMXPath($this->dom);

        return  $xpath->query('//*');
    }

    /**
     * @param DOMNodeList $tags
     * @return array
     */
    public function filterTagsForArticle(DOMNodeList $tags): array
    {
        $filteredTags = [];

        /** @var DOMElement $tag */
        foreach ($tags as $tag) {
            if (!in_array($tag->tagName, DOMTags::PRESERVED_TAGS)) {
                continue;
            }

            if ($tag->hasChildNodes() && in_array($tag->firstElementChild?->tagName, DOMTags::SEPARATED_TAGS)) {
                continue;
            }

            $filteredTags[] = [
                'tagName' => $tag->tagName,
                'content' => $this->getHtmlString($tag)
            ];
        }

        return $filteredTags;
    }

    /**
     * @param string $htmlString
     * @return string
     */
    public function getTagFromString(string $htmlString): string
    {
        $this->dom->loadHTML($htmlString);

        foreach ($this->dom->getElementsByTagName('*') as $tag) {
            if (in_array($tag->tagName, DOMTags::PRESERVED_TAGS)) {
                return $tag->tagName;
            }
        }

        return '';
    }
}
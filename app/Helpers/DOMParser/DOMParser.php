<?php

namespace App\Helpers\DOMParser;

use App\Services\ArticleElementService;
use DOMDocument;
use DOMNodeList;
use DOMXPath;
use Illuminate\Support\Facades\DB;

class DOMParser
{
    private ArticleElementService $articleElementService;
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
     * @param string $articleText
     * @param int $articleId
     * @return void
     */
    public function parseDOMContent(string $articleText, int $articleId): void
    {
        $this->dom->loadHTML($articleText);
        $xpath = new DOMXPath($this->dom);
        $tags  = $xpath->query('//*');

        $this->saveTagsAsArticleElements($tags, $articleId);
    }

    /**
     * @param DOMNodeList $tags
     * @param int $articleId
     * @return void
     */
    private function saveTagsAsArticleElements(DOMNodeList $tags, int $articleId): void
    {
        foreach ($tags as $i => $tag) {
            $this->articleElementService->store($tag, $this->dom, $articleId, $i);
        }
    }
}
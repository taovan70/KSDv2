<?php

namespace App\Helpers\DOMParser;

use DOMDocument;
use DOMElement;
use DOMNodeList;
use DOMXPath;

class DOMParser
{
    private DOMDocument $dom;

    public function __construct()
    {
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

            if (in_array($tag->firstElementChild?->tagName, DOMTags::SEPARATED_TAGS)) {
                continue;
            }

            $filteredTags[] = [
                'tagName' => $tag->tagName,
                'content' => $this->decodeString($this->getHtmlString($tag))
            ];
        }

        return $filteredTags;
    }

    /**
     * @param string $string
     * @return string
     */
    private function decodeString(string $string): string
    {
        $string = utf8_decode($string);

        if (!mb_check_encoding($string, "UTF-8")) {
            $string = mb_convert_encoding($string, 'UTF-8', 'auto');
        }

        return $string;
    }

    /**
     * @param string $htmlString
     * @return string|null
     */
    public function getTagFromString(string $htmlString): ?string
    {
        $tags = $this->parseContentOnTags($htmlString);
        $tags = $this->filterTagsForArticle($tags);

        if (count($tags) === 1) {
            return $tags[0]['tagName'];
        }

        return null;
    }

    /**
     * @param string $htmlString
     * @return \DOMNode
     */
    public function getImgDom(string $htmlString): \DOMNode
    {
        $this->dom->loadHTML($htmlString);

        return $this->dom->getElementsByTagName('img')->item(0);
    }
}
<?php

namespace App\Services;

use Embed\Embed;
use League\CommonMark\Environment\Environment;
use League\CommonMark\Exception\CommonMarkException;
use League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension;
use League\CommonMark\Extension\DisallowedRawHtml\DisallowedRawHtmlExtension;
use League\CommonMark\Extension\Embed\Bridge\OscaroteroEmbedAdapter;
use League\CommonMark\Extension\Embed\EmbedExtension;
use League\CommonMark\MarkdownConverter;

class EmbedService
{
    /**
     * @throws CommonMarkException
     */
    public function convertToHtml(string $text): string
    {
        $embedLibrary = new Embed();
        $embedLibrary->setSettings([
            'oembed:query_parameters' => [
                'maxwidth' => 800,
                'maxheight' => 600,
            ],
        ]);


        $config = [
            'embed' => [
                'adapter' => new OscaroteroEmbedAdapter($embedLibrary), // See the "Adapter" documentation below
                'allowed_domains' => ['youtube.com', 'twitter.com', 'github.com', 'rutube.ru'],
                'fallback' => 'link',
            ],
            'disallowed_raw_html' => [
                'disallowed_tags' => [
                    'title',
                    'textarea',
                    'style',
                    'xmp',
                    'iframe',
                    'noembed',
                    'noframes',
                    'script',
                    'plaintext'
                ],
            ],
        ];

        $environment = new Environment($config);
        $environment->addExtension(new CommonMarkCoreExtension());
        $environment->addExtension(new EmbedExtension());
        $environment->addExtension(new DisallowedRawHtmlExtension());
        $converter = new MarkdownConverter($environment);

        // add customs symbols
        $text = $this->replaceCustomSymbols($text, '++', '<strong>', '</strong>');
        $text = $this->replaceCustomSymbols($text, '+info+', '<div class="block-info">', '</div>');

        return $converter->convert($text);
    }

    public function replaceCustomSymbols($inputString, $symbol, $openingTag, $closingTag): array|string|null
    {
        $pattern = '/' . preg_quote($symbol, '/') . '(.*?)' . preg_quote($symbol, '/') . '/';

        // Replace the symbols with HTML tags
        // Return the modified string
        return preg_replace($pattern, $openingTag . '$1' . $closingTag, $inputString);
    }
}

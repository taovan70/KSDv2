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
    public function stripTags(string $text): string
    {
        $res =  strip_tags($text, '<table><tr><td><th>');
        $res = htmlspecialchars($res);
        // remain some tags
        $res = str_replace("&lt;table&gt;", "<table>", $res);
        $res = str_replace("&lt;/table&gt;", "</table>", $res);
        $res = str_replace("&lt;td&gt;", "<td>", $res);
        $res = str_replace("&lt;/td&gt;", "</td>", $res);
        $res = str_replace("&lt;th&gt;", "<th>", $res);
        $res = str_replace("&lt;/th&gt;", "</th>", $res);
        $res = str_replace("&lt;tr&gt;", "<tr>", $res);
        $res = str_replace("&lt;/tr&gt;", "</tr>", $res);
        return $res;
    }
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
        $text = $this->replaceCustomSymbols($text, '+caption+', '<div class="image-caption">', '</div>');
        $text = $this->replaceCustomSymbols($text, '+m1+', '<m1 class="image-caption">', '</m1>');

        return $converter->convert($text);
    }

    public function handleMarkdown(string $text): string
    {

        $text = $this->stripTags($text);

        // add customs symbols
        $text = $this->replaceCustomSymbols($text, '++', '<strong>', '</strong>');
        $text = $this->replaceCustomSymbols($text, '+info+', '<div class="block-info">', '</div>');
        $text = $this->replaceCustomSymbols($text, '+caption+', '<div class="image-caption">', '</div>');
        $text = $this->replaceCustomSymbols($text, '+m1+', '<m1 class="image-caption">', '</m1>');
        $text = $this->replaceCustomSymbols($text, '+H1Component+', '<H1Component>', '</H1Component>');

        return $text;
    }

    public function replaceCustomSymbols($inputString, $symbol, $openingTag, $closingTag): array|string|null
    {
        $pattern = '/' . preg_quote($symbol, '/') . '(\r\n|\n|\r)*(.*?)(\r\n|\n|\r)*' . preg_quote($symbol, '/') . '/'; // /\+H1Component\+(.*?)\+H1Component\+/

        // Replace the symbols with HTML tags
        // Return the modified string
        return preg_replace($pattern, $openingTag . '$2' . $closingTag, str_replace(array(" "), '', $inputString));
    }
}

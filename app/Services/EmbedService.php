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

        return $converter->convert($text);
    }

    public function handleMarkdown(string $text): string
    {

        $text = $this->stripTags($text);

        // add customs symbols
        $text = $this->replaceCustomSymbols($text, '++', '<strong>', '</strong>');
        $text = $this->replaceCustomSymbols($text, '+Advice+', '<Advice>', '</Advice>');
        $text = $this->replaceCustomSymbols($text, '+DidYouKnowInArticle+', '<DidYouKnowInArticle>', '</DidYouKnowInArticle>');
        $text = $this->replaceCustomSymbols($text, '+InfoGreen+', '<InfoGreen>', '</InfoGreen>');
        $text = $this->replaceCustomSymbols($text, '+InfoRed+', '<InfoRed>', '</InfoRed>');
        $text = $this->replaceCustomSymbols($text, '+InfoBlue+', '<InfoBlue>', '</InfoBlue>');
        $text = $this->replaceCustomSymbols($text, '+H1Component+', '<H1Component>', '</H1Component>');
        $text = $this->replaceCustomSymbols($text, '+ProsConsBlueMark+', '<ProsConsBlueMark data="elcontent">', '</ProsConsBlueMark>');
        $text = $this->replaceCustomSymbols($text, '+ProsConsEmptyPlus+', '<ProsConsEmptyPlus data="elcontent">', '</ProsConsEmptyPlus>');
        $text = $this->replaceCustomSymbols($text, '+ProsConsGreenPlus+', '<ProsConsGreenPlus data="elcontent">', '</ProsConsGreenPlus>');
        $text = $this->replaceCustomSymbols($text, '+ProsConsGreenMark+', '<ProsConsGreenMark data="elcontent">', '</ProsConsGreenMark>');
        $text = $this->replaceCustomSymbols($text, '+ProsConsGreenMarkDashed+', '<ProsConsGreenMarkDashed data="elcontent">', '</ProsConsGreenMarkDashed>');
        $text = $this->replaceCustomSymbols($text, '+TextBlockFirst+', '<TextBlockFirst data="elcontent">', '</TextBlockFirst>');
        $text = $this->replaceCustomSymbols($text, '+QuoteDashed+', '<QuoteDashed data="elcontent">', '</QuoteDashed>');
        $text = $this->replaceCustomSymbols($text, '+QuoteSolid+', '<QuoteSolid data="elcontent">', '</QuoteSolid>');
        $text = $this->replaceCustomSymbols($text, '+LinksAlsoDashed+', '<LinksAlsoDashed data="elcontent">', '</LinksAlsoDashed>');
        $text = $this->replaceCustomSymbols($text, '+LinksAlsoSolid+', '<LinksAlsoSolid data="elcontent">', '</LinksAlsoSolid>');
        $text = $this->replaceCustomSymbols($text, '+Advice+', '<Advice>', '</Advice>');
        $text = $this->replaceCustomSymbols($text, '+tableOfContents+', '<div className="tbcont">', '</div>');
        $text = $this->replaceCustomSymbols($text, '+caption+', '<ArticleImageCaption>', '</ArticleImageCaption>');
        $text = $this->replaceCustomSymbols($text, '+TOC+', '<TOC>', '</TOC>');
        return $text;
    }

    public function replaceCustomSymbols($inputString, $symbol, $openingTag, $closingTag): array|string|null
    {
        $pattern = '/' . preg_quote($symbol, '/') . '(\r\n|\n|\r)*((.|\n)*?)(\r\n|\n|\r)*' . preg_quote($symbol, '/') . '/'; // /\+H1Component\+(.*?)\+H1Component\+/

        $matches = [];
        $content = '';
        $contentChildren = '';
        $contentProps = '';
        preg_match($pattern, $inputString, $matches);

        if (!empty($matches[2])) {
            $content = strip_tags($matches[2]);
            $contentChildren = str_replace(["\r\n", "\r", "\n"], "<br />", $content);
            $contentProps = str_replace(["\r\n", "\r", "\n"], "", $content);
        }
        // Replace the symbols with HTML tags
        // Return the modified string
        return preg_replace($pattern, str_replace("elcontent", "$contentProps", $openingTag) . "$contentChildren" . $closingTag, str_replace([" ", "", ""], [" ", "", ""], $inputString));
    }
}

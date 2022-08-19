<?php
/**
 * Create two Classes - KinoukrDomCrawlerParserAdapter and FilmixParserStrategy.
 * Implement the ParserInterface and following methods:
 * parseContent() - should return Movie object with following properties:
 * $title, $poster, $description.
 *
 * For Class KinoukrDomCrawlerParserAdapter use Symfony DomCrawler Component for parsing page content.
 * Note: Use next namespace for KinoukrDomCrawlerParserAdapter Class - "namespace src\oop\app\src\Parsers;" (Like in this Interface)
 * Note: About Symfony DomCrawler Component you can read here:
 * https://symfony.com/doc/current/components/dom_crawler.html
 * Attention: Think about why this Parser might have a Adapter word in name!!!
 *
 * For Class FilmixParserStrategy use simple PHP methods without any library for parsing page content.
 * Note: Use next namespace for FilmixParserStrategy Class - "namespace src\oop\app\src\Parsers;" (Like in this Interface)
 * Note: For this Parser (for example) you can user regular expression.
 * Attention: Think about why this Parser might have a Strategy word in name!!!
 */

namespace src\oop\app\src\Parsers;

use src\oop\app\src\Models\Movie;
use src\oop\app\src\Models\MovieInterface;

/**
 * Returns a parsed content of a webpage
 */
class FilmixParserStrategy implements ParserInterface
{
    /**
     * @param string $siteContent
     * @return mixed
     */
    public function parseContent(string $siteContent) : MovieInterface
    {
        $siteContent = $this->convertToUTF8($siteContent);
        $movie = new Movie();
        foreach($movie->getPropertiesNames() as $paramName){
            $movie->set($paramName, $this->parse($paramName, $siteContent));
        }
        return $movie;
    }

    /**
     * Abstract parser
     *
     * @param string $movieParamName
     * @param string $siteContent
     * @return mixed
     */
    private function parse(string $movieParamName, string $siteContent): mixed
    {
        $parserName = 'parse' . ucfirst($movieParamName);
        return $this->$parserName($siteContent);
    }

    /**
     * @param string $string
     * @return mixed
     */
    private function parseTitle(string $string) : string
    {
        $tagname = 'h1';
        $attributesValues = 'class="name" itemprop="name"';
        $pattern = "/<$tagname $attributesValues ?.*>(.*)<\/$tagname>/";
        preg_match($pattern, $string, $matches);
        return $matches[0];
    }

    /**
     * @param string $string
     * @return mixed
     */
    private function parseDescription(string $string) : string
    {
        $tagname = 'div';
        $attributesValues ='class="full-story"';
        $pattern = "/<$tagname $attributesValues ?.*>(.*)<\/$tagname>/";
        preg_match($pattern, $string, $matches);
        return $matches[0];
    }

    /**
     * @param string $string
     * @return mixed
     */
    private function parsePoster(string $string) : string
    {
        $tagname = 'a';
        $attributesValues ='class="fancybox" rel="group" href="';
        $pattern = "/<$tagname $attributesValues(.*).\"*>/";
        preg_match($pattern, $string,$matches);
        return $matches[1];
    }

    /**
     * @param string $siteContent
     * @return string
     */
    private function convertToUTF8(string $string) : string
    {
        return mb_convert_encoding(
            $string,
            'UTF-8',
            mb_detect_encoding($string, 'UTF-8, windows-1251'));
    }
}
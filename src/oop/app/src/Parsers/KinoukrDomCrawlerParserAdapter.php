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
 * Note: For this Parser (for example) you can use regular expression.
 * Attention: Think about why this Parser might have a Strategy word in name!!!
 */

namespace src\oop\app\src\Parsers;

use src\oop\app\src\Models\Movie;
use src\oop\app\src\Models\MovieInterface;
use Symfony\Component\DomCrawler\Crawler;

class KinoukrDomCrawlerParserAdapter implements ParserInterface
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
        return strip_tags($this->$parserName($siteContent));
    }

    /**
     * @param string $html
     * @return mixed
     */
    public function parseTitle(string $html)
    {
        $crawler = new Crawler($html);
        $html = $crawler->filter('h1[itemprop="name"]')->first()->html();
        return $html ?? Movie::DEFAULT_TITLE;
    }

    /**
     * @param string $html
     * @return mixed
     */
    public function parseDescription(string $html) : string
    {
        $crawler = new Crawler($html);
        $html = $crawler->filter('div.fdesc')->first()->html();
        return $html ?? Movie::DEFAULT_DESCRIPTION;
    }

    /**
     * @param string $string
     * @return mixed
     */
    public function parsePoster(string $html)
    {
        $crawler = new Crawler($html);
        $html = $crawler->filter('a[data-fancybox="gallery"]')->attr('href');
        return $html  ?? Movie::DEFAULT_POSTER;
    }

    /**
     * @param string $string
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
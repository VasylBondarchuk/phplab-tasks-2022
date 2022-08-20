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

use Exception;
use src\oop\app\src\Models\Movie;
use src\oop\app\src\Models\MovieInterface;
use Symfony\Component\DomCrawler\Crawler;

/**
 *
 */
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
        $crawler = new Crawler($siteContent);
        foreach($movie->getPropertiesNames() as $paramName){
            $movie->set($paramName, $this->parse($paramName, $crawler));
        }
        return $movie;
    }

    /**
     * Abstract parser
     *
     * @param string $movieParamName
     * @param Crawler $crawler
     * @return mixed
     */
    private function parse(string $movieParamName, Crawler $crawler)
    {
        $parserName = 'parse' . ucfirst($movieParamName);
        return strip_tags($this->$parserName($movieParamName, $crawler));
    }

    /**
     * @param string $movieParamName
     * @param Crawler $crawler
     * @return mixed
     */
    public function parseTitle(string $movieParamName, Crawler $crawler)
    {
        $parsedHtml = MovieInterface::DEFAULT_TITLE;
        try {
            $parsedHtml  = $crawler->filter('h1[itemprop="name"]')->first()->html();
        } catch (Exception $e) {
            echo "Error of $movieParamName parsing: ",  $e->getMessage(). '<br>';
        }
        return $parsedHtml;
    }

    /**
     * @param string $movieParamName
     * @param Crawler $crawler
     * @return mixed
     */
    public function parseDescription(string $movieParamName, Crawler $crawler) : string
    {
        $parsedHtml = MovieInterface::DEFAULT_DESCRIPTION;
        try {
            $parsedHtml  = $crawler->filter('div.fdesc')->first()->html();
        } catch (Exception $e) {
            echo "Error of $movieParamName parsing: ",  $e->getMessage(). '<br>';
        }
        return $parsedHtml;

    }

    /**
     * @param Crawler $crawler
     * @return mixed
     */
    public function parsePoster(string $movieParamName, Crawler $crawler)
    {
        try {
            $parsedHtml  = $crawler->filter('a[data-fancybox="gallery"]')->attr('href');
        } catch (Exception $e) {
            echo "Error of $movieParamName parsing: ",  $e->getMessage(). '<br>';
            $parsedHtml = MovieInterface::DEFAULT_POSTER;
        }
        return $parsedHtml;
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
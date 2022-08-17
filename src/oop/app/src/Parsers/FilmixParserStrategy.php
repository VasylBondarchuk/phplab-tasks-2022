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

use src\oop\app\src\Transporters\TransportInterface;

class FilmixParserStrategy implements ParserInterface
{
    private TransportInterface $curlStrategy;

    /**
     * @param TransportInterface $curlStrategy
     */
    public function __construct(TransportInterface $curlStrategy)
    {
        $this->curlStrategy = $curlStrategy;
    }

    /**
     * @param string $siteContent
     * @return mixed
     */
    public function parseContent(string $siteContent)
    {
        $content = $this->curlStrategy->getContent('https://filmix.ac/filmi/triller/151413-lost-ledyanoy-drayv-2021.html');
        //echo $content;exit;
        return $content;

    }
}
<?php

namespace src\oop\app\src;

use src\oop\app\src\Parsers\KinoukrDomCrawlerParserAdapter;
use src\oop\app\src\Parsers\FilmixParserStrategy;
use src\oop\app\src\Transporters\CurlStrategy;
use src\oop\app\src\Transporters\GuzzleAdapter;
use Exception;

/**
 *
 */
class ScrapperFactory
{
    const FILMIX = 'filmix';
    const KINOUKR = 'kinoukr';

    /**
     * @param string $domain
     * @return Scrapper
     * @throws Exception
     */
    public function create(string $domain): Scrapper
    {
        return match ($domain) {
            self::FILMIX  => new Scrapper(new CurlStrategy(), new FilmixParserStrategy()),
            self::KINOUKR => new Scrapper(new GuzzleAdapter(), new KinoukrDomCrawlerParserAdapter()),
            default => throw new Exception('Resource not found!'),
        };
    }
}
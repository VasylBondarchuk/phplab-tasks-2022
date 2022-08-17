<?php

namespace src\oop\app\src;

use src\oop\app\src\Parsers\ParserInterface;
use src\oop\app\src\Transporters\TransportInterface;
use src\oop\app\src\Models\Movie;

/**
 * Create Class - Scrapper with method getMovie().
 * getMovie() - should return Movie Class object.
 *
 * Note: Use next namespace for Scrapper Class - "namespace src\oop\app\src;"
 * Note: Dont forget to create variables for TransportInterface and ParserInterface objects.
 * Note: Also you can add your methods if needed.
 */

class Scrapper
{

    public TransportInterface $transportInterface;
    public ParserInterface $parserInterface;

    /**
     * @param TransportInterface $transportInterface
     * @param ParserInterface $parserInterface
     */
    public function __construct(TransportInterface $transportInterface, ParserInterface $parserInterface)
    {
        $this->transportInterface = $transportInterface;
        $this->parserInterface = $parserInterface;
    }

    /**
     * @param string $url
     * @return Movie
     */
    public function getMovie(string $url): Movie
    {
        return new Movie('Title','Poster','Description');
    }

}
